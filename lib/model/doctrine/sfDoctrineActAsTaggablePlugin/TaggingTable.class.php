<?php

/**
 * TaggingTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class TaggingTable extends PluginTaggingTable {

  /**
   * Returns an instance of this class.
   *
   * @return object TaggingTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('Tagging');
  }

}