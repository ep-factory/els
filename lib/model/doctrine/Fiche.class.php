<?php

/**
 * Fiche
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @package    els
 * @subpackage model
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
class Fiche extends BaseFiche {

  public function preSave($event) {
    parent::preSave($event);
    if(!$this->getPpcId()) {
      $ppc = PpcTable::getInstance()->findOneByIsActive(true);
      if(!$ppc) {
        throw new sfException('Aucun PPC actif. Veuillez contacter un administrateur.');
      }
      $this->setPpcId($ppc->getPrimaryKey());
    }
  }

  /**
   * Render fiche to string
   *
   * @return string Label
   */
  public function __toString() {
    return sprintf("Fiche n°%s %s %s", $this->getNumber(), $this->getCaseCode(), $this->getCategoryCode());
  }

  public function getTimeSpent() {
    if(!strtotime($this->getEndHour()) || !strtotime($this->getStartHour())) {
      return;
    }
    $from = new DateTime($this->getStartHour());
    $to = new DateTime($this->getEndHour());
    return date_diff($from, $to)->format('%Hh%I');
    /*$diff = strtotime($this->getEndHour()) - strtotime($this->getStartHour());
    return myUser::convert_seconds_to_time($diff ? $diff*1000 : 0);*/
  }

  /**
   * Check if current fiche has parent
   *
   * @return boolean Condition
   */
  public function hasParent() {
    return $this->getParentId() != $this->getPrimaryKey() && $this->getParentId() && $this->getParent() && !$this->getParent()->isNew();
  }

  /**
   * Unresolve current fiche, parent and children
   *
   * @return mixed Void or list of ids
   */
  public function unresolve() {
    $this->setIsResolved(false)->save();
  }

  /**
   * Resolve current fiche
   *
   * @return Fiche
   */
  public function resolve() {
    $this->setIsResolved(true)
         ->setResolvedDate(date('Y-m-d H:i:s'))
         ->setResolvedAuthorId(sfContext::getInstance()->getUser()->getGuardUser()->getPrimaryKey())
         ->save();
    return $this;
    /*if(!count($this->getHierarchyIds())) {
      return;
    }
    if($return) {
      return $this->getHierarchyIds();
    }
    $this->getTable()->createQuery()
            ->set('is_resolved', true)
            ->set('resolved_date', '"'.date('Y-m-d H:i:s').'"')
            ->set('resolved_author_id', sfContext::getInstance()->getUser()->getGuardUser()->getPrimaryKey())
            ->whereIn('id', $this->getHierarchyIds())
            ->update()->execute();*/
  }

  /**
   * Close current fiche
   *
   * @return Fiche
   */
  public function close() {
    $this->setIsFinished(true)
         ->setFinishedDate(date('Y-m-d H:i:s'))
         ->setFinishedAuthorId(sfContext::getInstance()->getUser()->getGuardUser()->getPrimaryKey())
         ->save();
    return $this;
    /*if(!count($this->getHierarchyIds())) {
      return;
    }
    $this->getTable()->createQuery()
            ->set('is_finished', true)
            ->set('finished_date', '"'.date('Y-m-d H:i:s').'"')
            ->set('finished_author_id', sfContext::getInstance()->getUser()->getGuardUser()->getPrimaryKey())
            ->whereIn('id', $this->getHierarchyIds())
            ->update()->execute();*/
  }

  /**
   *
   * @return array Ids
   */
  protected function getHierarchyIds() {
    if(!sfContext::hasInstance() || !sfContext::getInstance()->getUser()->isAuthenticated()) {
      return array();
    }
    $ids = array($this->getPrimaryKey());
    if($this->hasParent()) {
      $ids = array_merge($ids, $this->getParent()->resolve(true));
    }
    if($this->getChildrens()->count()) {
      $ids = array_merge($ids, $this->getChildrens()->getPrimaryKeys());
    }
    return $ids;
  }

  /**
   * Get category code
   *
   * @return string
   */
  public function getCategoryCode() {
    return $this->getCategory()->getCode();
  }

  /**
   * Force fiche number and time spent
   * 
   * @param Doctrine_Event $event Event
   */
  public function preInsert($event) {
    parent::preInsert($event);
    // Force number
    if(!$this->getNumber()) {
      $this->setNumber(date('YmdHis').sfConfig::get('app_machine_id'));
    }
  }

}