<?php

/**
 * sfGuardUserAdminForm for admin generators
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardUserAdminForm.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardUserAdminForm extends BasesfGuardUserAdminForm
{
  /**
   * @see sfForm
   */
  public function configure()
  {
    if(!$this->isNew() && !$this->getUser()->isSuperAdmin()) {
      unset($this['is_active'], $this['is_super_admin'], $this['groups_list'], $this['permissions_list']);
      $this->widgetSchema['username'] = new sfWidgetFormInputHidden();
    }
    elseif($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['username'] = new sfWidgetFormKeyboard();
    }
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['first_name'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['last_name'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['email_address'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['password'] = new sfWidgetFormKeyboard(array('renderer_class' => 'sfWidgetFormInputPassword'));
      $this->widgetSchema['password_again'] = new sfWidgetFormKeyboard(array('renderer_class' => 'sfWidgetFormInputPassword'));
    }
  }
}
