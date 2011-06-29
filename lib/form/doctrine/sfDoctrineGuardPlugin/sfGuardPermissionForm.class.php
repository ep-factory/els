<?php

/**
 * sfGuardPermission form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrinePluginFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class sfGuardPermissionForm extends PluginsfGuardPermissionForm {

  /**
   * Init form
   */
  public function configure() {
    unset($this['created_at'], $this['updated_at']);
  }

}
