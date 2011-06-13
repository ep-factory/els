<?php

/**
 * CategoryTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class CategoryTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object CategoryTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('Category');
  }

  /**
   *
   * @param Doctrine_Query $query
   * @param <type> $action
   * @return Doctrine_Query
   */
  public function findActive(Doctrine_Query $query = null, $action = null) {
    if(is_null($query)) {
      $query = $this->createQuery('q');
    }
    $query->andWhere($query->getRootAlias().'.is_active = 1')
            ->andWhere($query->getRootAlias().'.deleted_at IS NULL')
            ->addOrderBy($query->getRootAlias().'.name ASC')
            ->addOrderBy($query->getRootAlias().'.code ASC');
    return $action ? $query->$action() : $query;
  }

}