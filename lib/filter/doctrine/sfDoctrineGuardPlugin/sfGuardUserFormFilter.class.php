<?php

/**
 * sfGuardUser filter form.
 *
 * @package    els
 * @subpackage filter
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrinePluginFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserFormFilter extends PluginsfGuardUserFormFilter
{
  /**
   * Init filters
   */
  public function configure()
  {
    // Search
    $this->widgetSchema['search'] = new sfWidgetFormInputText();
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['search'] = new sfWidgetFormKeyboard();
    }
    $this->widgetSchema['search']->setAttribute("title", "Rechercher");
    $this->widgetSchema['search']->setAttribute("placeholder", "Rechercher");
    $this->widgetSchema['search']->setAttribute("alt", "Rechercher");
    $this->validatorSchema['search'] = new sfValidatorPass();
  }
}
