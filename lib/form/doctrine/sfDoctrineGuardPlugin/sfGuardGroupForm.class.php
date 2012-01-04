<?php

/**
 * sfGuardGroup form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardGroupForm extends PluginsfGuardGroupForm {

  /**
   * Init form
   */
  public function configure() {
    unset($this['created_at'], $this['updated_at']);
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['name'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['description'] = new sfWidgetFormKeyboard(array('renderer_class' => 'sfWidgetFormTextarea'));
    }
  }

}
