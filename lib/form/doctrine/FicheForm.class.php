<?php

/**
 * Fiche form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FicheForm extends BaseFicheForm {

  /**
   * Init form
   */
  public function configure() {
    unset($this['created_at'], $this['updated_at'], $this['deleted_at']);
    // Hidden fields
    $this->widgetSchema['parent_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['category_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['resolved_author_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['finished_author_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['is_finished'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['is_resolved'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['number'] = new sfWidgetFormInputHidden();
    $this->validatorSchema['number']->setOption('required', false);

    // Specific fields
    if($this->getUser()->hasGroup('technicien')) {
      $this->widgetSchema['sf_guard_user_id'] = new sfWidgetFormInputHidden();
    }
    else {
      $this->widgetSchema['sf_guard_user_id']->setOption('order_by', array('first_name', 'ASC'));
      $this->widgetSchema['sf_guard_user_id']->setOption('table_method', 'findActive');
    }
    $this->widgetSchema['poste_id']->setOption('order_by', array('id', 'ASC'));
    $this->widgetSchema['poste_id']->setOption('table_method', 'findActive');
    $this->widgetSchema['appareil_id']->setOption('table_method', 'findActive');
    $this->widgetSchema['demandeur_id']->setOption('table_method', 'findActive');
    $this->widgetSchema['batiment_id']->setOption('table_method', 'findActive');
    $this->widgetSchema['atelier_id']->setOption('table_method', 'findActive');
    $this->widgetSchema['annexe_id']->setOption('table_method', 'findActive');
    $this->widgetSchema['tags'] = new sfWidgetFormInputToken(array('url' => $this->genUrl('@fiche_tags_autocomplete')));
    $this->validatorSchema['tags'] = new sfValidatorString(array('required' => false));
    $caseCode = CaseCodeTable::getInstance()->findOneByIsActive(true);
    if(!$caseCode) {
      $this->getUser()->setFlash('error', 'Aucun code affaire actif. Veuillez contacter un administrateur.');
      $this->getContext()->getController()->redirect("@fiche");
    }
    $this->setDefault('case_code_id', $caseCode->getPrimaryKey());
    $this->widgetSchema['case_code_id'] = new sfWidgetFormInputPlain(array('value' => $this->isNew() ? $caseCode : $this->getObject()->getCaseCode()));
    $this->widgetSchema['time_spent'] = new sfWidgetFormInputPlain();
    $this->widgetSchema['fiche_date'] = new sfWidgetFormDateJQueryUI();
    $this->widgetSchema['unsolved_date'] = new sfWidgetFormDateJQueryUI();
    $this->validatorSchema['appel_hour'] = new sfValidatorDateTime(array('required' => false, 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4}) (?P<hour>\d{2})h(?P<minute>\d{2})~'));
    $this->validatorSchema['start_hour'] = new sfValidatorDateTime(array('required' => false, 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4}) (?P<hour>\d{2})h(?P<minute>\d{2})~'));
    $this->validatorSchema['end_hour'] = new sfValidatorDateTime(array('required' => false, 'date_format' => '~(?P<day>\d{2})/(?P<month>\d{2})/(?P<year>\d{4}) (?P<hour>\d{2})h(?P<minute>\d{2})~'));
    $this->widgetSchema['elements_list'] = new sfWidgetFormMultiple(array(
                'add_empty' => !$this->isNew() && $this->getObject()->getElements()->count(),
                'callback' => $this->getUser()->getAttribute('enable_keyboard', true) ? 'addElementsKeyboard' : null,
                'widgets' => array(
                    'element_changed_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Element', 'table_method' => 'findActive', 'add_empty' => true), array('widgetClass' => 'changed_id')),
                    'element_changed_serial' => $this->getUser()->getAttribute('enable_keyboard', true) ? new sfWidgetFormKeyboard(array(), array('renderer_options' => array('widgetClass' => 'changed_serial'))) : new sfWidgetFormInputText(array(), array('widgetClass' => 'changed_serial')),
                    'element_installed_id' => new sfWidgetFormDoctrineChoice(array('model' => 'Element', 'table_method' => 'findActive', 'add_empty' => true), array('widgetClass' => 'installed_id')),
                    'element_installed_serial' => $this->getUser()->getAttribute('enable_keyboard', true) ? new sfWidgetFormKeyboard(array(), array('renderer_options' => array('widgetClass' => 'changed_serial'))) : new sfWidgetFormInputText(array(), array('widgetClass' => 'installed_serial'))
                    )));
    $this->validatorSchema['elements_list'] = new sfValidatorMultiple(array('required' => false, 'validators' => array(
                    'element_changed_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Element')),
                    'element_changed_serial' => new sfValidatorString(array('required' => false)),
                    'element_installed_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Element')),
                    'element_installed_serial' => new sfValidatorString(array('required' => false))
                    )));
    $this->getWidgetSchema()->setHelp('elements_list', sprintf("<a href='%s' class='fancybox'>Créer un nouvel élément</a>", $this->genUrl('@element_new')));
    $this->getWidgetSchema()->setHelp('unsolved_name', "Si problème non résolu");

    /**
     * Set generic keyboard widget for text fields
     * Set jQuery mask for hours fields
     * Add validationEngine implementation for required fields
     */
    foreach($this->getValidatorSchema()->getFields() as $name => $validator) {
      if(isset($this->widgetSchema[$name])) {
        // Hour
        if(preg_match('/hour$/i', $name)) {
          if(!$this->getUser()->getAttribute('enable_keyboard', true)) {
            $this->widgetSchema[$name] = new sfWidgetFormInputMask(array('mask' => '99/99/9999 99h99', 'formatter' => array($this, 'parseTimestamp')));
          }
          else {
            $this->widgetSchema[$name] = new sfWidgetFormInputText();
          }
        }
        // Keyboard
        if($this->getUser()->getAttribute('enable_keyboard', true)
                && ($this->widgetSchema[$name] instanceof sfWidgetFormInputText || $this->widgetSchema[$name] instanceof sfWidgetFormTextarea)
                && !in_array(get_class($this->widgetSchema[$name]), array('sfWidgetFormDateJQueryUI', 'sfWidgetFormKeyboard'))) {
          $options = array();
          // Hour fields
          if(preg_match('/hour$/i', $name)) {
            $options['maxLength'] = 5;
            $options['layout'] = 'hour';
          }
          $options['renderer_class'] = get_class($this->widgetSchema[$name]);
          $options['renderer_options'] = $this->widgetSchema[$name]->getOptions();
          if($this->widgetSchema[$name] instanceof sfWidgetFormInputToken) {
            unset($options['renderer_options']['url']);
            $options['renderer_class'] = 'sfWidgetFormInputText';
          }
          $this->widgetSchema[$name] = new sfWidgetFormKeyboard($options, $this->widgetSchema[$name]->getAttributes());
        }
        // Switch
        if($this->widgetSchema[$name] instanceof sfWidgetFormInputCheckbox) {
          $this->widgetSchema[$name] = new sfWidgetFormInputSwitch(array('choices' => array('Non', 'Oui')));
        }
        // Add class required
        if($validator->getOption('required')) {
          if(!$this->widgetSchema[$name]->getAttribute('class')) {
            $this->widgetSchema[$name]->setAttribute('class', 'validate[required]');
          }
          elseif(!preg_match('/validate\[/i', $this->widgetSchema[$name]->getAttribute('class'))) {
            $this->widgetSchema[$name]->setAttribute('class', $this->widgetSchema[$name]->getAttribute('class').' validate[required]');
          }
        }
        // DoctrineCollection
        if($this->widgetSchema[$name] instanceof sfWidgetFormDoctrineChoice && !$this->widgetSchema[$name]->getOption('order_by')) {
          $this->widgetSchema[$name]->setOption('order_by', array('name', 'ASC'));
        }
      }
    }

    // Defaults from request & user
    $this->setDefault('parent_id', $this->getRequest()->getParameter('parent_id', null));
    if(!$this->getUser()->isSuperAdmin()) {
      $this->setDefault('sf_guard_user_id', $this->getUser()->getGuardUser()->getPrimaryKey());
    }
    $this->setDefault('category_id', $this->getRequest()->getParameter('category_id', CategoryTable::getInstance()->findByIsActive(true)->getFirst()->getPrimaryKey()));

    // Specific form from category
    $fields = array('id', 'parent_id', 'case_code_id', 'tags', 'finished_author_id', 'resolved_author_id', 'category_id', 'number', 'fiche_date', 'poste_id', 'ppi_number', 'mo_number', 'acr_number', 'is_resolved', 'is_finished', 'start_hour', 'end_hour', 'time_spent', 'solution', 'sf_guard_user_id', 'batiment_id', 'atelier_id', 'annexe_id');
    $category = CategoryTable::getInstance()->find($this->getDefault('category_id'));
    if($category) {
      switch($category->getCode()) {
        default:
        case 481:
          $fields = array_merge($fields, array('appareil_id', 'ppc_number', 'is_cmr', 'demandeur_id', 'appel_hour', 'unsolved_name', 'unsolved_date', 'is_tested', 'test_mechanic', 'test_operator', 'is_stopped', 'is_ips', 'is_controlled', 'elements_list', 'problem', 'cause'));
          break;

        case 472:
          $fields = array_merge($fields, array('label', 'solution'));
          break;

        case 490:
          $fields = array_merge($fields, array('label', 'solution'));
          $this->setDefault('label', 'Entretien des éclairages');
          break;
      }
      $this->useFields($fields);
    }
  }

  /**
   * Parse a timestamp
   *
   * @param string $value Timestamp to parse
   * @return mixed Parsed timestamp
   */
  public function parseTimestamp($value) {
    return $value ? date('d/m/Y H:i:s') : null;
  }

  /**
   * Get the javascript files list
   *
   * @return array Javascript files list
   */
  public function getJavaScripts() {
    return array_merge(parent::getJavaScripts(), array(
        '/sfEPFactoryFormPlugin/js/jquery.min.js',
        'fancybox/jquery.fancybox-1.3.4.pack.js',
        'ficheForm.js'
    ));
  }

  /**
   * Get the stylesheet files list
   *
   * @return array Stylesheet files list
   */
  public function getStylesheets() {
    return array_merge(parent::getStylesheets(), array('/js/fancybox/jquery.fancybox-1.3.4.css' => 'screen'));
  }

  /**
   * Update form fields with object values
   */
  public function updateDefaultsFromObject() {
    parent::updateDefaultsFromObject();
    // Tags
    if(isset($this->widgetSchema['tags'])) {
      $tags = $this->getObject()->getTags();
      if($this->getUser()->getAttribute('enable_keyboard', true)) {
        $tags = implode(', ', $tags);
      }
      $this->widgetSchema['tags']->setDefault($tags);
    }
    // Elements
    if(isset($this->widgetSchema['elements_list'])) {
      $this->widgetSchema['elements_list']->setDefault($this->getObject()->getElements());
    }
  }

  /**
   * Hydrate object and save form
   * 
   * @param Doctrine_Connection $con Connection
   */
  protected function doSave($con = null) {
    $this->saveElementsList($con);
    parent::doSave($con);
  }

  /**
   * Save fiche elements list
   * 
   * @param Doctrine_Connection $con Connection
   * @return void
   */
  public function saveElementsList($con = null) {
    if(!$this->isValid()) {
      throw $this->getErrorSchema();
    }
    if(!isset($this->widgetSchema['elements_list'])) {
      return;
    }
    if(null === $con) {
      $con = $this->getConnection();
    }
    $existing = $this->getObject()->setElements(new Doctrine_Collection('FicheElement'));
    $values = $this->getValue('elements_list');
    if(!is_array($values)) {
      $values = array();
    }
    foreach($values as $element) {
      if(($element['element_changed_id'] && $element['element_changed_serial']) || ($element['element_installed_id'] && $element['element_installed_serial'])) {
        $ficheElement = new FicheElement();
        if($element['element_changed_id'] && $element['element_changed_serial']) {
          $ficheElement->setElementChangedId($element['element_changed_id']);
          $ficheElement->setElementChangedSerial($element['element_changed_serial']);
        }
        if($element['element_installed_id'] && $element['element_installed_serial']) {
          $ficheElement->setElementInstalledId($element['element_installed_id']);
          $ficheElement->setElementInstalledSerial($element['element_installed_serial']);
        }
        $this->getObject()->getElements()->add($ficheElement);
      }
    }
  }

}