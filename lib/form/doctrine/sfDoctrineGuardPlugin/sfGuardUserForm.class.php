<?php

/**
 * sfGuardUser form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardUserForm extends PluginsfGuardUserForm {

  /**
   * Init form
   */
  public function configure() {
    unset($this['created_at'], $this['updated_at']);
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['first_name'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['last_name'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['email_address'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['username'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['password'] = new sfWidgetFormKeyboard(array('renderer_class' => 'sfWidgetFormInputPassword'));
    }
  }

}
