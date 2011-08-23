<?php

/**
 * DocumentTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class DocumentTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object DocumentTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('Document');
  }

  /**
   * Find active elements
   *
   * @param Doctrine_Query $query Query
   * @param string $action Query action (count, execute, null to get query object)
   * @return mixed Doctrine_Query object or query results
   */
  public function findActive(Doctrine_Query $query = null, $action = null) {
    if(is_null($query)) {
      $query = $this->createQuery('q');
    }
    $query->andWhere($query->getRootAlias().'.deleted_at IS NULL')
          ->addOrderBy($query->getRootAlias().'.name ASC');
    return $action ? $query->$action() : $query;
  }

}