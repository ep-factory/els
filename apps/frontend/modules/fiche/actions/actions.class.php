<?php

require_once dirname(__FILE__).'/../lib/ficheGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/ficheGeneratorHelper.class.php';

/**
 * fiche actions.
 *
 * @package    els
 * @subpackage fiche
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ficheActions extends autoFicheActions {
  /**
   * Update user filters in ajax post
   * 
   * @param sfWebRequest $request
   * @return sfView
   */
  public function executeUpdateFilters(sfWebRequest $request) {
    $this->forward404Unless($request->isXmlHttpRequest() && $request->isMethod("post"));
    $filters = array('category_id' => $request->getParameter("category_id"));
    if($this->getUser()->hasPermission('view')) {
      $filters['is_resolved'] = 0;
      $filters['is_finished'] = 0;
    }
    elseif($this->getUser()->hasPermission('view-resolved')) {
      $filters['is_resolved'] = 1;
      $filters['is_finished'] = 0;
    }
    $this->setFilters($filters);
    return sfView::NONE;
  }

  /**
   * Export fiches
   * 
   * @param sfWebRequest $request
   * @return sfView
   */
  public function executeExport(sfWebRequest $request) {
    $this->form = new ExportFormFilter();
    if($request->isMethod("post")) {
      $this->form->bind($request->getParameter($this->form->getName()));
      if($this->form->isValid()) {
        $this->setFilters($this->form->getValues());
        ini_set("max_execution_time", 3600);
        ini_set("memory_limit", '512M');
        $results = $this->buildQuery()->execute();
        $filename = sfConfig::get('sf_upload_dir').'/'.$this->getModuleName().'.csv';
        $handle = fopen($filename, 'w+');
        chmod($filename, 0777);
        // Headers
        $headers = array('N. de fiche', 'Catégorie', 'Code Affaire');
        foreach($this->configuration->getValue('show.display') as $header) {
          if(is_array($header)) {
            foreach($header as $name) {
              $headers[] = $this->retrieveLabel($name);
            }
          }
          else {
            $headers[] = $this->retrieveLabel($header);
          }
        }
        fputcsv($handle, $headers, ";", '"');
        // Results
        foreach($results as $result) {
          $line = array($result->getNumber(), $result->getCategoryCode(), $result->getCaseCode());
          foreach($this->configuration->getValue('show.display') as $column) {
            if(is_array($column)) {
              foreach($column as $name) {
                $line[] = $this->retrieveValue($result, $name);
              }
            }
            else {
              $line[] = $this->retrieveValue($result, $column);
            }
          }
          fputcsv($handle, $line, ";", '"');
        }
        fclose($handle);
        $this->setLayout(false);
        $response = $this->getResponse();
        $response->setHttpHeader('Content-Disposition', 'attachment; filename="'.basename($filename).'"');
        //$response->setContentType('text/csv; charset=windows-1252/Winlatin1');
        $response->setContentType('text/csv; charset=UTF-8');
        $response->setContent(file_get_contents($filename));
        unlink($filename);
        return sfView::NONE;
      }
      else {
        $this->getUser()->setFlash('error', "Le formulaire comporte des erreurs.", false);
      }
    }
  }

  /**
   * Retrieve element label for export
   * 
   * @param string $name
   * @return string
   */
  protected function retrieveLabel($name) {
    return utf8_decode($this->configuration->getFieldConfiguration("list", preg_replace('/^[_~](.*)$/i', '$1', $name))->getConfig('label'));
  }

  /**
   * Retrieve element value for export
   * 
   * @param Fiche $fiche
   * @param string $column
   * @return string
   */
  protected function retrieveValue(Fiche $fiche, $column) {
    $name = preg_replace('/^[_~](.*)$/i', '$1', $column);
    if(FicheTable::getInstance()->hasRelation(ucfirst($name))) {
      $name = ucfirst($name);
    }
    $method = "get".ucfirst(sfInflector::camelize($name));
    if(method_exists($fiche, "getExport".ucfirst(sfInflector::camelize($name)))) {
      $method = "getExport".ucfirst(sfInflector::camelize($name));
    }
    $value = $fiche->{$method}();
    // sfDoctrineRecord
    if(is_object($value) && $value instanceof sfDoctrineRecord) {
      $value = $value->isNew() ? null : $value->__toString();
    }
    // Array
    if(is_object($value) && $value instanceof Doctrine_Collection) {
      $objects = array();
      foreach($value as $object) {
        if($object && !$object->isNew()) {
          $objects[] = $object->__toString();
        }
      }
      $value = $objects;
    }
    if(is_array($value)) {
      $value = implode(", ", $value);
    }
    // Timestamp
    if(preg_match('/^(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})$/i', $value)) {
      $value = date('d/m/Y H\hi', strtotime($value));
    }
    // Date
    elseif(preg_match('/^(\d{4})-(\d{2})-(\d{2})$/i', $value)) {
      $value = date('d/m/Y', strtotime($value));
    }
    // Time
    elseif(preg_match('/^(\d{2}):(\d{2}):(\d{2})$/i', $value)) {
      $value = date('H\hi', strtotime($value));
    }
    // Replace line returns
    //$value = str_replace("\r", null, str_replace("\r\n", null, str_replace("\n", null, $value)));
    $value = preg_replace("/(\r\n|\n|\r)/", "~", $value);
    return utf8_decode($value);
  }

  /**
   * Enable/disable virtual keyboard
   * 
   * @param sfWebRequest $request
   * @return sfView
   */
  public function executeEnableKeyboard(sfWebRequest $request) {
    $this->forward404Unless($request->isXmlHttpRequest() && $request->hasParameter('enable'));
    $this->getUser()->setAttribute('enable_keyboard', $request->getParameter('enable', false) ? true : false);
    return sfView::NONE;
  }

  /**
   * Generate autocomplete on Demandeur table
   * 
   * @param sfWebRequest $request
   * @return string
   */
  public function executeDemandeur_autocomplete(sfWebRequest $request) {
    $this->forward404Unless($request->isXmlHttpRequest());
    $objects = DemandeurTable::getInstance()->createQuery()->select("id, name")->where("name LIKE ?", "%".$request->getParameter("q")."%")->andWhere('is_active = 1 AND deleted_at IS NULL')->limit(10)->fetchArray();
    return $this->renderText(json_encode($objects));
  }

  /**
   * Generate autocomplete on Tag table
   * 
   * @param sfWebRequest $request
   * @return sfView
   */
  public function executeTags_autocomplete(sfWebRequest $request) {
    $this->forward404Unless($request->isXmlHttpRequest());
    $tags = TagTable::getInstance()->createQuery("tag")->select("tag.name AS id, tag.name AS name")->where("tag.name LIKE ?", "%".$request->getParameter("q")."%")->limit(10)->fetchArray();
    return $this->renderText(json_encode($tags));
  }

  /**
   * Display dashboard
   * 
   * @param sfWebRequest $request 
   */
  public function executeDashboard(sfWebRequest $request) {
    // sorting
    if($request->getParameter('sort') && $this->isValidSortColumn($request->getParameter('sort')))
    {
      $this->setSort(array($request->getParameter('sort'), $request->getParameter('sort_type')));
    }
    if($request->getParameter('page'))
    {
      $this->setPage($request->getParameter('page'));
    }
    $this->pager = $this->getPager();
    $this->sort = $this->getSort();
  }

  /**
   * Display fiches list
   * 
   * @param sfWebRequest $request 
   */
  public function executeIndex(sfWebRequest $request) {
    parent::executeIndex($request);
    $this->categories = CategoryTable::getInstance()->findAll();
  }

  /**
   * Override admin generator filters
   * 
   * @param sfWebRequest $request 
   */
  public function executeFilter(sfWebRequest $request) {
    $this->setPage(1);
    if($request->hasParameter('_reset'))
    {
      $this->setFilters($this->configuration->getFilterDefaults());
      $this->redirect('@search');
    }
    $this->filters = $this->configuration->getFilterForm($this->getFilters());
    $this->filters->bind($request->getParameter($this->filters->getName()));
    if($this->filters->isValid())
    {
      $this->setFilters($this->filters->getValues());
      $this->redirect('@search');
    }
    $this->pager = $this->getPager();
    $this->sort = $this->getSort();
    $this->setTemplate('index');
  }

  /**
   * Display edit form
   * 
   * @param sfWebRequest $request 
   */
  public function executeEdit(sfWebRequest $request) {
    $this->fiche = $this->getRoute()->getObject();
    if($this->getUser()->hasGroup('technicien') && !$this->fiche->getSfGuardUserId()) {
      $this->fiche->setSfGuardUserId($this->getUser()->getGuardUser()->getPrimaryKey());
    }
    $this->form = $this->configuration->getForm($this->fiche);
    parent::executeEdit($request);
    $this->forwardIf(!$this->getUser()->canEdit($this->fiche), $this->getModuleName(), "index");
  }

  /**
   * Unresolve fiche
   * 
   * @param sfWebRequest $request 
   */
  public function executeUnresolve(sfWebRequest $request) {
    $this->forwardIf(!$this->getUser()->isSuperAdmin() && $this->getUser()->hasCredential('reopen-own') && $this->getRoute()->getObject()->getSfGuardUserId() != $this->getUser()->getGuardUser()->getPrimaryKey(), $this->getModuleName(), "index");
    $this->getRoute()->getObject()->unresolve();
    $this->getUser()->setFlash('notice', "L'intervention a été correctement rouverte.");
    $this->redirect($request->getReferer());
  }

  /**
   * Resolve fiche
   * 
   * @param sfWebRequest $request 
   */
  public function executeResolve(sfWebRequest $request) {
    $this->forwardIf(!$this->getUser()->isSuperAdmin() && $this->getUser()->hasCredential('resolve-own') && $this->getRoute()->getObject()->getSfGuardUserId() != $this->getUser()->getGuardUser()->getPrimaryKey(), $this->getModuleName(), "index");
    $this->getRoute()->getObject()->resolve();
    $this->getUser()->setFlash('notice', "L'intervention a été correctement fermée.");
    $this->redirect($request->getReferer());
  }

  /**
   * Close fiche
   * 
   * @param sfWebRequest $request 
   */
  public function executeClose(sfWebRequest $request) {
    if($this->getRoute()->getObject()->close()){
      $this->getUser()->setFlash('notice', "L'intervention a été correctement clôturée.");
    }
    else{
      $this->getUser()->setFlash('error', "Les Fiches filles doivent être clôturées avant.");
    }
    $this->redirect($request->getReferer());
  }

  /**
   * Create fiche
   * 
   * @param sfWebRequest $request 
   */
  public function executeCreate(sfWebRequest $request){
    $this->form = $this->configuration->getForm();
    $datas = $request->getParameter($this->form->getName());
    if(isset($datas['parent_number']) && $datas['parent_number'] && $object = FicheTable::getInstance()->findOneByNumber($datas['parent_number'])) {
      $class = get_class($object);
      $values = $object->toArray(false);
      unset($values['number'], $values['id'], $values['fiche_date'], $values['sf_guard_user_id'], $values['start_hour'], $values['end_hour'], $values['is_finished'], $values['is_resolved']);
      $values['parent_number'] = $object->getNumber();
      $values['tags'] = $object->getTags();
      $values['Elements'] = $object->getElements();
      $this->fiche = new $class();
      $this->fiche->fromArray($values);
      $this->form = $this->configuration->getForm($this->fiche);
    }
    else {
      $this->fiche = $this->form->getObject();
    }
    $this->processForm($request, $this->form);
    $this->setTemplate('new');
  }

  /**
   * Create fiche daughter
   * 
   * @param sfWebRequest $request 
   */
  public function executeAdd(sfWebRequest $request) {
    $object = $this->getRoute()->getObject();
    $class = get_class($object);
    $values = $object->toArray(false);
    unset($values['number'], $values['id'], $values['fiche_date'], $values['sf_guard_user_id'], $values['start_hour'], $values['end_hour'], $values['is_finished'], $values['is_resolved']);
    $values['parent_number'] = $object->getNumber();
    $values['tags'] = $object->getTags();
    $values['Elements'] = $object->getElements();
    $this->fiche = new $class();
    $this->fiche->fromArray($values);
    $this->form = $this->configuration->getForm($this->fiche);
    $this->setTemplate('new');
  }

}