<?php

/**
 * sfGuardPermissionTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class sfGuardPermissionTable extends PluginsfGuardPermissionTable {

  /**
   * Returns an instance of this class.
   *
   * @return object sfGuardPermissionTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('sfGuardPermission');
  }

}