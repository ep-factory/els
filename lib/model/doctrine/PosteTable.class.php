<?php

/**
 * PosteTable
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class PosteTable extends Doctrine_Table {

  /**
   * Returns an instance of this class.
   *
   * @return object PosteTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable('Poste');
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