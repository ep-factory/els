<?php

/**
 * sfGuardChangeUserPasswordForm for changing a users password
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Jonathan H. Wage <jonwage@gmail.com>
 * @version    SVN: $Id: sfGuardChangeUserPasswordForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardChangeUserPasswordForm extends BasesfGuardChangeUserPasswordForm
{
  /**
   * @see sfForm
   */
  public function configure()
  {
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['password'] = new sfWidgetFormKeyboard(array('renderer_class' => 'sfWidgetFormInputPassword'));
      $this->widgetSchema['password_again'] = new sfWidgetFormKeyboard(array('renderer_class' => 'sfWidgetFormInputPassword'));
    }
  }
}