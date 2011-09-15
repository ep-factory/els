<?php

/**
 * Element form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ElementForm extends BaseElementForm {

  /**
   * Init form
   */
  public function configure() {
    unset($this['created_at'], $this['updated_at'], $this['deleted_at']);
    $this->widgetSchema['server_id'] = new sfWidgetFormInputHidden();
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['marque'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['type'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['ref'] = new sfWidgetFormKeyboard();
    }
  }

}
