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

  public function configure() {
    unset($this['created_at'], $this['updated_at'], $this['deleted_at']);
    // Hidden fields
    $this->widgetSchema['parent_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['category_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['resolved_author_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['finished_author_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['number'] = new sfWidgetFormInputHidden();

    // Specific fields
    $this->widgetSchema['tags'] = new sfWidgetFormInputText();
    $this->validatorSchema['tags'] = new sfValidatorString(array('required' => false));
    $this->widgetSchema['time_spent'] = new sfWidgetFormInputPlain();
    $this->widgetSchema['fiche_date'] = new sfWidgetFormDateJQueryUI();
    $this->validatorSchema['start_hour'] = new sfValidatorDateCustom();
    $this->validatorSchema['end_hour'] = new sfValidatorDateCustom();
    $this->widgetSchema['intervenants_list'] = new sfWidgetFormMultiple(array(
                'max_number' => sfGuardUserTable::getInstance()->findActive(null, "count"),
                'add_empty' => false,
                'widgets' => array(
                    'id' => new sfWidgetFormDoctrineChoice(array('model' => 'sfGuardUser', 'table_method' => 'findActive', 'add_empty' => true))
                    )));
    $this->validatorSchema['intervenants_list'] = new sfValidatorMultiple(array('required' => false, 'validators' => array(
                    'id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'sfGuardUser'))
                    )));
    $this->widgetSchema['elements_list'] = new sfWidgetFormMultiple(array(
                'max_number' => ElementTable::getInstance()->findActive(null, "count"),
                'add_empty' => false,
                'widgets' => array(
                    'id' => new sfWidgetFormDoctrineChoice(array('model' => 'Element', 'table_method' => 'findActive', 'add_empty' => true))
                    )));
    $this->validatorSchema['elements_list'] = new sfValidatorMultiple(array('required' => false, 'validators' => array(
                    'id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Element'))
                    )));
    $this->widgetSchema['ateliers_list'] = new sfWidgetFormMultiple(array(
                'max_number' => AtelierTable::getInstance()->findActive(null, "count"),
                'add_empty' => false,
                'widgets' => array(
                    'id' => new sfWidgetFormDoctrineChoice(array('model' => 'Atelier', 'table_method' => 'findActive', 'add_empty' => true))
                    )));
    $this->validatorSchema['ateliers_list'] = new sfValidatorMultiple(array('required' => false, 'validators' => array(
                    'id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => 'Atelier'))
                    )));

    // Validators & keyboards
    foreach($this->getValidatorSchema()->getFields() as $name => $validator) {
      if(isset($this->widgetSchema[$name])) {
        // Hour
        if(preg_match('/hour$/i', $name)) {
          $this->widgetSchema[$name] = new sfWidgetFormInputMask(array('mask' => '99h99'));
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
            $options['renderer_class'] = "sfWidgetFormInputMask";
            $options['renderer_options'] = array('mask' => '99h99');
          }
          // Add autocomplete
          if($this->widgetSchema[$name] instanceof sfWidgetFormInputToken) {
            $options['renderer_class'] = 'sfWidgetFormInputText';
            $options['url'] = $this->widgetSchema[$name]->getOption('url');
          }
          // Default field
          else {
            $options['renderer_class'] = get_class($this->widgetSchema[$name]);
            $options['renderer_options'] = $this->widgetSchema[$name]->getOptions();
          }
          $this->widgetSchema[$name] = new sfWidgetFormKeyboard($options);
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
      }
    }

    // Defaults
    $this->setDefault('parent_id', $this->getRequest()->getParameter('parent_id', null));
    $this->setDefault('category_id', $this->getRequest()->getParameter('category_id', CategoryTable::getInstance()->findByIsActive(true)->getFirst()->getPrimaryKey()));

    // Specific fiche
    $fields = array('id', 'parent_id', 'tags', 'finished_author_id', 'resolved_author_id', 'category_id', 'number', 'fiche_date', 'poste_id', 'ppi_number', 'mo_number', 'acr_number', 'is_resolved', 'is_finished', 'start_hour', 'end_hour', 'time_spent', 'problem', 'cause', 'solution', 'intervenants_list', 'batiment_id');
    $category = CategoryTable::getInstance()->find($this->getDefault('category_id'));
    if($category) {
      switch($category->getCode()) {
        // CRI
        default:
        case 481:
          $fields = array_merge($fields, array('appareil_id', 'ppc_number', 'is_cmr', 'demandeur_id', 'appel_hour', 'unsolved_name', 'is_tested', 'test_mechanic', 'test_operator', 'is_stopped', 'is_ips', 'is_controlled', 'elements_list', 'ateliers_list'));
          break;

        // TP 472
        case 472:
          $fields = array_merge($fields, array('ateliers_list', 'label'));
          break;

        // TP 490
        case 490:
          break;
      }
      $this->useFields($fields);
    }
  }

  public function updateDefaultsFromObject() {
    parent::updateDefaultsFromObject();
    if(isset($this->widgetSchema['tags'])) {
      $tags = $this->getObject()->getTags();
      if($this->getUser()->getAttribute('enable_keyboard', true)) {
        $tags = implode(', ', $tags);
      }
      $this->widgetSchema['tags']->setDefault($tags);
    }
  }

  protected function doBind(array $values) {
    parent::doBind($values);
    foreach(array('intervenants_list', 'elements_list', 'ateliers_list') as $name) {
      if(isset($values[$name])) {
        $ids = array();
        foreach($values as $value) {
          if($value && is_array($value) && isset($value['id']) && $value['id']) {
            $ids[] = $value['id'];
          }
        }
        $values[$name] = $ids;
      }
    }
  }

  public function saveIntervenantsList($con = null) {
    if(!$this->isValid()) {
      throw $this->getErrorSchema();
    }
    if(!isset($this->widgetSchema['intervenants_list'])) {
      return;
    }
    $values = $this->getValue('intervenants_list');
    if(!is_array($values)) {
      $values = array();
    }
    $ids = array();
    foreach($values as $value) {
      if($value && is_array($value) && isset($value['id']) && $value['id']) {
        $ids[] = $value['id'];
      }
    }
    $this->values['intervenants_list'];



    if(!$this->isValid()) {
      throw $this->getErrorSchema();
    }
    if(!isset($this->widgetSchema['intervenants_list'])) {
      return;
    }
    if(null === $con) {
      $con = $this->getConnection();
    }
    $existing = $this->object->Intervenants->getPrimaryKeys();
    $values = $this->getValue('intervenants_list');
    if(!is_array($values)) {
      $values = array();
    }
    $ids = array();
    foreach($values as $value) {
      if($value && is_array($value) && isset($value['id']) && $value['id']) {
        $ids[] = $value['id'];
      }
    }
    $unlink = array_diff($existing, $values);
    if(count($unlink)) {
      $this->object->unlink('Intervenants', array_values($unlink));
    }
    $link = array_diff($values, $existing);
    if(count($link)) {
      $this->object->link('Intervenants', array_values($link));
    }
  }

}
