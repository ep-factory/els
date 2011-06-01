<?php

/**
 * CaseCodeTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CaseCodeTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object CaseCodeTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('CaseCode');
  }

  public function findActive(Doctrine_Query $query = null, $action = null) {
    if(is_null($query)) {
      $query = $this->createQuery('q');
    }
    $query->andWhere($query->getRootAlias().'.is_active = 1')
            ->andWhere($query->getRootAlias().'.deleted_at IS NULL')
            ->addOrderBy($query->getRootAlias().'.name ASC');
    return $action ? $query->$action() : $query;
  }

}