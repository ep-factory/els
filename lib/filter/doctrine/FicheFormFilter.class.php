<?php

/**
 * Fiche filter form.
 *
 * @package    els
 * @subpackage filter
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FicheFormFilter extends BaseFicheFormFilter
{
  /**
   * Init filters
   */
  public function configure()
  {
    // Is finished
    $this->widgetSchema['is_finished']->setOption('renderer_class', 'sfWidgetFormSelectRadio');
    $this->widgetSchema['is_finished']->setOption('choices', array('' => 'tous', 1 => 'oui', 0 => 'non'));

    // Fiche date
    $this->widgetSchema['fiche_date']->setOption('template', 'du %from_date%<br />au %to_date%');
    $this->widgetSchema['fiche_date']->setOption('from_date', new sfWidgetFormDateJQueryUI(array(), array('title' => 'Depuis le', 'placeholder' => 'Depuis le')));
    $this->widgetSchema['fiche_date']->setOption('to_date', new sfWidgetFormDateJQueryUI(array(), array('title' => "Jusqu'au", 'placeholder' => "Jusqu'au")));
    $this->validatorSchema['fiche_date']->setOption('from_date', new sfValidatorDateCustom(array('required' => false)));
    $this->validatorSchema['fiche_date']->setOption('to_date', new sfValidatorDateCustom(array('required' => false)));

    // Created at
    $this->widgetSchema['created_at']->setOption('template', 'du %from_date%<br />au %to_date%');
    $this->widgetSchema['created_at']->setOption('from_date', new sfWidgetFormDateJQueryUI(array(), array('title' => 'Depuis le', 'placeholder' => 'Depuis le')));
    $this->widgetSchema['created_at']->setOption('to_date', new sfWidgetFormDateJQueryUI(array(), array('title' => "Jusqu'au", 'placeholder' => "Jusqu'au")));
    $this->validatorSchema['created_at']->setOption('from_date', new sfValidatorDateCustom(array('required' => false)));
    $this->validatorSchema['created_at']->setOption('to_date', new sfValidatorDateCustom(array('required' => false)));

    // Start hour
    $this->widgetSchema['start_hour']->setOption('template', 'du %from_date%<br />au %to_date%');
    $this->widgetSchema['start_hour']->setOption('from_date', new sfWidgetFormTimestamp(array(), array('title' => 'Depuis le', 'placeholder' => 'Depuis le')));
    $this->widgetSchema['start_hour']->setOption('to_date', new sfWidgetFormTimestamp(array(), array('title' => "Jusqu'au", 'placeholder' => "Jusqu'au")));
    $this->validatorSchema['start_hour']->setOption('from_date', new sfValidatorTimestamp(array('required' => false)));
    $this->validatorSchema['start_hour']->setOption('to_date', new sfValidatorTimestamp(array('required' => false)));

    // End hour
    $this->widgetSchema['end_hour']->setOption('template', 'du %from_date%<br />au %to_date%');
    $this->widgetSchema['end_hour']->setOption('from_date', new sfWidgetFormTimestamp(array(), array('title' => 'Depuis le', 'placeholder' => 'Depuis le')));
    $this->widgetSchema['end_hour']->setOption('to_date', new sfWidgetFormTimestamp(array(), array('title' => "Jusqu'au", 'placeholder' => "Jusqu'au")));
    $this->validatorSchema['end_hour']->setOption('from_date', new sfValidatorTimestamp(array('required' => false)));
    $this->validatorSchema['end_hour']->setOption('to_date', new sfValidatorTimestamp(array('required' => false)));
  }
}
