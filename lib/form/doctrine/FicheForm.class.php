<?php

/**
 * Fiche form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FicheForm extends BaseFicheForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['deleted_at']);
    // Hidden fields
    $this->widgetSchema['parent_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['category_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['author_id'] = new sfWidgetFormInputHidden();
    $this->widgetSchema['number'] = new sfWidgetFormInputHidden();

    // Specific fields
    $this->widgetSchema['time_spent'] = new sfWidgetFormInputPlain();
    $this->widgetSchema['fiche_date'] = new sfWidgetFormDateJQueryUI();
    $this->widgetSchema['start_hour'] = new sfWidgetFormKeyboard(array('maxLength' => 5, 'layout' => 'hour', 'renderer_class' => 'sfWidgetFormInputMask', 'renderer_options' => array('mask' => '99h99')));
    $this->validatorSchema['start_hour'] = new sfValidatorDateCustom();
    $this->widgetSchema['end_hour'] = new sfWidgetFormKeyboard(array('maxLength' => 5, 'layout' => 'hour', 'renderer_class' => 'sfWidgetFormInputMask', 'renderer_options' => array('mask' => '99h99')));
    $this->validatorSchema['end_hour'] = new sfValidatorDateCustom();
    $this->widgetSchema['ppc_number'] = new sfWidgetFormKeyboard();
    $this->widgetSchema['ppi_number'] = new sfWidgetFormKeyboard();
    $this->widgetSchema['mo_number'] = new sfWidgetFormKeyboard();
    $this->widgetSchema['acr_number'] = new sfWidgetFormKeyboard();

    // Validators
    foreach($this->getValidatorSchema()->getFields() as $name => $validator) {
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

    // Request
    if($this->getRequest()->hasParameter('parent_id')) {
      $this->setDefault('parent_id', $this->getRequest()->getParameter('parent_id'));
    }
    if($this->getRequest()->hasParameter('category_id')) {
      $this->setDefault('category_id', $this->getRequest()->getParameter('category_id'));
    }
  }
}
