<?php

/**
 * sfGuardFormSignin for sfGuardAuth signin action
 *
 * @package    sfDoctrineGuardPlugin
 * @subpackage form
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 * @version    SVN: $Id: sfGuardFormSignin.class.php 23536 2009-11-02 21:41:21Z Kris.Wallsmith $
 */
class sfGuardFormSignin extends BasesfGuardFormSignin
{
  /**
   * @see sfForm
   */
  public function configure()
  {
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['username'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['password'] = new sfWidgetFormKeyboard(array('renderer_class' => 'sfWidgetFormInputPassword'));
    }
    $this->widgetSchema['username']->setAttribute('class', 'logininput');
    $this->widgetSchema['password']->setAttribute('class', 'logininput');
    $this->getWidgetSchema()->setLabel('username', "Nom d'utilisateur ou email :");
    $this->getWidgetSchema()->setLabel('password', "Mot de passe :");
  }
}
