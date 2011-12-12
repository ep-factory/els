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
class Fiche extends BaseFiche
{

  /**
   * Force PPC active
   * 
   * @param Doctrine_Event $event 
   */
  public function preSave($event)
  {
    parent::preSave($event);
    if(!$this->getPpcId())
    {
      $ppc = PpcTable::getInstance()->findOneByIsActive(true);
      if(!$ppc)
      {
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
  public function __toString()
  {
    return sprintf("Fiche n°%s %s %s", $this->getNumber(), $this->getCaseCode(), $this->getCategoryCode());
  }

  /**
   * Get time spent for render
   * 
   * @return string
   */
  public function getTimeSpent()
  {
    if(!strtotime($this->getEndHour()) || !strtotime($this->getStartHour()))
    {
      return;
    }
    $from = new DateTime($this->getStartHour());
    $to = new DateTime($this->getEndHour());
    return date_diff($from, $to)->format('%Hh%I');
    /* $diff = strtotime($this->getEndHour()) - strtotime($this->getStartHour());
      return myUser::convert_seconds_to_time($diff ? $diff*1000 : 0); */
  }

  /**
   * Get time spent for export
   * 
   * @return string
   */
  public function getExportTimeSpent()
  {
    if(!strtotime($this->getEndHour()) || !strtotime($this->getStartHour()))
    {
      return;
    }
    $timeDebut = strtotime($this->getStartHour());
    $timeFin = strtotime($this->getEndHour());
    $nbHeure = 0;
    if($timeFin > $timeDebut)
    {
      $diff = ($timeFin - $timeDebut);
      if($diff >= 3600)
      {
        $nbHeure = floor($diff / 3600);
        $diff = $diff - ($nbHeure * 3600);
      }
      $nbMinuteCent = floor((($diff / 60) * 100) / 60);
      return $nbHeure.','.$nbMinuteCent;
    }
    return;
  }

  /**
   * Check if current fiche has parent
   *
   * @return boolean Condition
   */
  public function hasParent()
  {
    return $this->getParentNumber() != $this->getNumber() && $this->getParentNumber() && $this->getParent() && !$this->getParent()->isNew() && $this->getParent()->getPrimaryKey();
  }

  /**
   * Unresolve current fiche, parent and children
   *
   * @return mixed Void or list of ids
   */
  public function unresolve()
  {
    $this->setIsResolved(false)->save();
  }

  /**
   * Resolve current fiche
   *
   * @return Fiche
   */
  public function resolve()
  {
    if(!count($this->getHierarchyIds()))
    {
      return;
    }
    $this->getTable()->createQuery()
            ->set('is_resolved', true)
            ->set('resolved_date', '"'.date('Y-m-d H:i:s').'"')
            ->set('resolved_author_id', sfContext::getInstance()->getUser()->getGuardUser()->getPrimaryKey())
            ->whereIn('id', $this->getHierarchyIds())
            ->update()->execute();
  }

  /**
   * Close current fiche
   *
   * @return Fiche
   */
  public function close()
  {
    $nbFicheEncoreOuverte = $this->getTable()->createQuery()
            ->where('is_finished = 0 AND parent_number = ?', $this->getNumber())
            ->count();
    if($nbFicheEncoreOuverte == 0)
    {
      $this->setIsFinished(true)
              ->setFinishedDate(date('Y-m-d H:i:s'))
              ->setFinishedAuthorId(sfContext::getInstance()->getUser()->getGuardUser()->getPrimaryKey())
              ->save();
      return $this;
    }
    return false;
  }

  /**
   *
   * @return array Ids
   */
  public function getHierarchyIds()
  {
    if(!sfContext::hasInstance() || !sfContext::getInstance()->getUser()->isAuthenticated())
    {
      return array();
    }
    $ids = array($this->getPrimaryKey());
    // Retrieve parent ids
    if($this->getParentNumber())
    {
      $ids = array_merge($ids, $this->getParent()->getHierarchyIds());
    }
    // Retrieve children ids
    /*if($this->getChildrens()->count())
    {
      $ids = array_merge($ids, $this->getChildrens()->getPrimaryKeys());
    }*/
    return $ids;
  }

  /**
   * Get category code
   *
   * @return string
   */
  public function getCategoryCode()
  {
    return $this->getCategory()->getCode();
  }

  /**
   * Get first tag in tags list
   * 
   * @return string
   */
  public function getFirstTag()
  {
    foreach($this->getTags() as $tag)
    {
      return $tag;
    }
    return false;
  }

  /**
   * Force fiche number
   * 
   * @param Doctrine_Event $event Event
   */
  public function preInsert($event)
  {
    parent::preInsert($event);
    if(!$this->getNumber())
    {
      // Force number
      if(!sfContext::hasInstance())
      {
        $this->setNumber(substr(md5(rand()), 0, 12).sfConfig::get('app_machine_id'));
      }
      else
      {
        $this->setNumber(date('ymdHis').sfConfig::get('app_machine_id'));
      }
    }
  }

}