<?php

/**
 * CaseCode
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    els
 * @subpackage model
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class CaseCode extends BaseCaseCode {

  /**
   * Unactive all code except current
   * 
   * @param Doctrine_Event $event Event
   */
  public function postSave($event) {
    parent::postSave($event);
    if($this->is_active) {
      $query = $this->getTable()->createQuery()
                      ->set('is_active', 0)
                      ->where('id != ?', $this->getPrimaryKey())
                      ->update()->execute();
    }
  }

}
