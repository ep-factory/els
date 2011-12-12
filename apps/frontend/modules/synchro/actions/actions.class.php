<?php

/**
 * synchro actions.
 *
 * @package    els
 * @subpackage synchro
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class synchroActions extends sfActions
{
  /**
   * Disable debug bar
   */
  public function preExecute() {
    parent::preExecute();
    sfConfig::set('sf_web_debug', false);
  }
  
 /**
  * Display synchronization page
  *
  * @param sfRequest $request A request object
  */
  public function executeIndex(sfWebRequest $request)
  {
    if($request->isXmlHttpRequest())
    {
      // Retrieve new fiches
      $values = FicheTable::getInstance()->createQuery('fiche')
              ->leftJoin('fiche.Elements fiche_element')
              ->leftJoin('fiche_element.ElementChanged changed')
              ->leftJoin('fiche_element.ElementInstalled installed')
              ->leftJoin('fiche.Demandeur demandeur')
              ->fetchArray();
      foreach($values as $key => $value) {
        $tags = FicheTable::getInstance()->find($value['id'])->getTags();
        if($tags) {
          $values[$key]['tags'] = array_values($tags);
        }
      }
      // Send values in rest
      try {
        if($this->sendRest($values)) {
          // Load values from server export
          if(!is_dir(sfConfig::get('sf_upload_dir'))) {
            mkdir(sfConfig::get('sf_upload_dir'), 0777);
          }
          $filename = sfConfig::get('sf_upload_dir').DIRECTORY_SEPARATOR.date('Y-m-d-H-i-s').'.sql';
          file_put_contents($filename, file_get_contents(sfConfig::get('app_synchronization_export')));
          list($username, $password, $dbname, $host) = $this->getConfig();
          if($password) {
            exec("mysql -h $host -u $username -p$password $dbname<$filename");
          }
          else {
            exec("mysql -h $host -u $username $dbname<$filename");
          }
          unlink($filename);
          return $this->renderText(json_encode(array('code' => 'success', 'message'=> "Les données ont été correctement importées.")));
        }
        throw new sfException("Une erreur inconnue est survenue durant la synchronisation.");
      }
      catch(Exception $error) {
        return $this->renderText(json_encode(array('code' => 'error', 'message'=> $error->getMessage())));
      }
    }
  }
  
  /**
   * Send a REST request through curl
   * 
   * @throw sfException
   * 
   * @param array $values Values to send (will be serialized)
   * @return boolean
   */
  protected function sendRest(array $values)
  {
    if(!$values) {
      return true;
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_URL, sfConfig::get('app_synchronization_import'));
    curl_setopt($curl, CURLOPT_POSTFIELDS, array('values' => serialize($values)));
    $return = curl_exec($curl);
    curl_close($curl);
    if($return !== false) {
      $return = json_decode($return);
      if($return->code == "error") {
        throw new sfException("Return error : ".$return->message);
        return false;
      }
      return true;
    }
    else {
      throw new sfException("Curl error : ".curl_error($curl));
      return false;
    }
  }
  
 /**
  * Execute import
  *
  * @param sfRequest $request A request object
  */
  public function executeImport(sfWebRequest $request)
  {
    $this->forward404Unless($request->isMethod("post") || $request->hasParameter('values'));
    $connection = FicheTable::getInstance()->getConnection();
    $connection->beginInternalTransaction();
    $this->logMessage(count(unserialize($request->getParameter("values")))." fiches reçues.");
    $count = 0;
    foreach(unserialize($request->getParameter("values")) as $fiche) {
      // Test si la fiche existe
      $record = FicheTable::getInstance()->findOneByNumber($fiche['number']);
      $elements = $fiche['Elements'];
      $demandeur = $fiche['Demandeur'];
      unset($fiche['Demandeur'], $fiche['Elements'], $fiche['id']);
      $fiche['demandeur_id'] = null;
      if(!$record) {
        $record = new Fiche();
      }
      elseif($record->getIsResolved()) {
        continue;
      }
      else {
        $record->unlink("Elements", $record->getElements()->getPrimaryKeys());
        // Conflict if fiche.sf_guard_user_id != record.sf_guard_user_id
        if(($record['sf_guard_user_id'] && $record['sf_guard_user_id'] != $fiche['sf_guard_user_id'])){
            //|| $record['is_resolved'] || $record['is_finished']) {
          unset($fiche['id']);
          $log = new FicheLog();
          $log->fromArray($fiche);
          // Manage Demandeur
          if(isset($demandeur['name']) && $demandeur['name']) {
            $existingDemandeur = DemandeurTable::getInstance()->findOneByName($demandeur['name']);
            if(!$existingDemandeur) {
              $existingDemandeur = new Demandeur();
              $existingDemandeur->setName($demandeur['name']);
            }
            $log->setDemandeur($existingDemandeur);
          }
          $log->setFiche($record);
          $log->save();
          continue;
        }
      }
      $record->fromArray($fiche);
      // Manage Demandeur
      if(isset($demandeur['name']) && $demandeur['name']) {
        $existingDemandeur = DemandeurTable::getInstance()->findOneByName($demandeur['name']);
        if(!$existingDemandeur) {
          $existingDemandeur = new Demandeur();
          $existingDemandeur->setName($demandeur['name']);
        }
        $record->setDemandeur($existingDemandeur);
      }
      // Manage Elements
      foreach($elements as $object) {
        $ficheElement = new FicheElement();
        // ElementChanged ElementInstalled
        foreach(array('ElementChanged', 'ElementInstalled') as $name) {
          if(isset($object[$name]) && $object[$name]) {
            // Try to retrieve existing Element
            if($object[$name]['server_id']) {
              $element = ElementTable::getInstance()->findOneByServerId($object[$name]['server_id']);
            }
            // Create new Element
            if(!isset($element) || !$element) {
              $element = new Element();
            }
            unset($object[$name]['id'], $object[$name]['server_id']);
            $element->fromArray($object[$name]);
            $ficheElement[$name] = $element;
          }
        }
        $ficheElement->setElementChangedSerial($object['element_changed_serial']);
        $ficheElement->setElementInstalledSerial($object['element_installed_serial']);
        $record->getElements()->add($ficheElement);
      }
      // Save current Fiche
      try {
        $record->save($connection);
        $count++;
      }
      catch(Exception $error) {
        $connection->rollback();
        $this->logMessage("Une erreur est survenue : ".$error->getMessage());
        return $this->renderText(json_encode(array('code' => 'error', 'message' => "Une erreur est survenue : ".$error->getMessage())));
      }
    }
    $connection->commit();
    // Save recursive Fiche & FicheElement & Element & Demandeur
    $this->logMessage("$count fiches ont été enregistrées.");
    return $this->renderText(json_encode(array('code' => 'success', 'message' => "Les fiches ont été correctement importées.")));
  }
  
 /**
  * Execute export
  *
  * @param sfRequest $request A request object
  */
  public function executeExport(sfWebRequest $request)
  {
    list($username, $password, $dbname, $host) = $this->getConfig();
    system("mysqldump -h $host -u $username -p$password $dbname --skip-comments --ignore-table=$dbname.fiche --ignore-table=$dbname.fiche_element --ignore-table=$dbname.fiche_log");
    system("mysqldump -h $host -u $username -p$password $dbname --skip-comments --where=\"fiche.fiche_date >= '".date('Y-m-d', strtotime("-14 days"))."'\" --tables fiche");
    system("mysqldump -h $host -u $username -p$password $dbname --skip-comments --tables fiche_element");
    return sfView::NONE;
  }
  
  protected function getConfig() {
    $config = sfYaml::load(sfConfig::get('sf_config_dir').DIRECTORY_SEPARATOR.'databases.yml');
    $config = isset($config[sfConfig::get('sf_environment', 'prod')]) ? array_merge($config[sfConfig::get('sf_environment', 'prod')], $config['all']) : $config['all'];
    $username = $config['doctrine']['param']['username'];
    $password = $config['doctrine']['param']['password'];
    $dbname = preg_replace('/.*dbname=([^;]+).*/i', '$1', $config['doctrine']['param']['dsn']);
    $host = preg_replace('/.*hostname=([^;]+).*/i', '$1', $config['doctrine']['param']['dsn']);
    return array($username, $password, $dbname, $host);
  }
}