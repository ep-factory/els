<?php

/**
 * ElementTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class ElementTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object ElementTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('Element');
  }

  public function findActive(Doctrine_Query $query = null, $action = null) {
    if(is_null($query)) {
      $query = $this->createQuery('q');
    }
    $query->andWhere($query->getRootAlias().'.is_active = 1')
            ->andWhere($query->getRootAlias().'.deleted_at IS NULL')
            ->addOrderBy($query->getRootAlias().'.marque ASC')
            ->addOrderBy($query->getRootAlias().'.type ASC')
            ->addOrderBy($query->getRootAlias().'.ref ASC')
            ->addOrderBy($query->getRootAlias().'.serial ASC');
    return $action ? $query->$action() : $query;
  }

}