<?php

/**
 * Batiment form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BatimentForm extends BaseBatimentForm {

  /**
   * Init form
   */
  public function configure() {
    unset($this['created_at'], $this['updated_at'], $this['deleted_at']);
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['name'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['sigle'] = new sfWidgetFormKeyboard();
    }
  }

}
