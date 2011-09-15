<?php

/**
 * Project filter form base class.
 *
 * @package    els
 * @subpackage filter
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterBaseTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
abstract class BaseFormFilterDoctrine extends sfFormFilterDoctrine {

  public function setup() {
    // Search
    $this->widgetSchema['search'] = new sfWidgetFormInputText();
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['search'] = new sfWidgetFormKeyboard();
    }
    $this->widgetSchema['search']->setAttribute("title", "Rechercher");
    $this->widgetSchema['search']->setAttribute("placeholder", "Rechercher");
    $this->widgetSchema['search']->setAttribute("alt", "Rechercher");
    $this->validatorSchema['search'] = new sfValidatorPass();
  }

  /**
   * Init search query using all text columns, and tags if table has template
   *
   * @param Doctrine_Query $query Search query
   * @param string $field Form field name
   * @param string $values Search value
   */
  public function addSearchColumnQuery(Doctrine_Query $query, $field, $values) {
    $where = "";
    $params = array();
    foreach($this->getTable()->getColumns() as $name => $options) {
      if(in_array($options['type'], array('string', 'clob'))) {
        $where.= sprintf("%s %s.%s LIKE ?", count($params) ? " OR" : null, $query->getRootAlias(), "%$name%");
        $params[] = "%$values%";
      }
    }
    // Tags
    if($this->getTable()->hasTemplate('Taggable')) {
      $objects = TagTable::getObjectTaggedWithQuery($this->getTable()->getClassnameToReturn(), $values, $this->getTable()->createQuery()->where('deleted_at IS NULL'))->execute();
      if($objects) {
        $ids = array();
        foreach($objects as $object) {
          if(!$object->getDeletedAt() && !in_array($object->getPrimaryKey(), $ids)) {
            $ids[] = $object->getPrimaryKey();
          }
        }
        if($ids) {
          $where.= sprintf("%s %s.id IN (%s)", count($params) ? " OR" : null, $query->getRootAlias(), implode(', ', $ids));
        }
      }
    }
    if(count($params)) {
      $query->andWhere("($where)", $params);
    }
  }

}
