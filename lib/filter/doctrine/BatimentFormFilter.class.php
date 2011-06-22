<?php

/**
 * Batiment filter form.
 *
 * @package    els
 * @subpackage filter
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class BatimentFormFilter extends BaseBatimentFormFilter
{
  public function configure()
  {
    // Search
    $this->widgetSchema['search'] = new sfWidgetFormInputText();
    $this->widgetSchema['search']->setAttribute("title", "Rechercher");
    $this->widgetSchema['search']->setAttribute("placeholder", "Rechercher");
    $this->widgetSchema['search']->setAttribute("alt", "Rechercher");
    $this->validatorSchema['search'] = new sfValidatorPass();
  }

  public function addSearchColumnQuery(Doctrine_Query $query, $field, $values)
  {
    foreach($this->getTable()->getColumns() as $name => $options)
    {
      if(in_array($options['type'], array('string', 'clob'))) {
        $query->orWhere($query->getRootAlias().".$name LIKE ?", "%$values%");
      }
    }
    // Tags
    if($this->getTable()->hasTemplate('Taggable'))
    {
      $objects = TagTable::getObjectTaggedWith($values);
      if($objects)
      {
        $ids = array();
        foreach($objects as $object)
        {
          if(!in_array($object->getPrimaryKey(), $ids))
          {
            $ids[] = $object->getPrimaryKey();
          }
        }
        $query->orWhereIn($query->getRootAlias().".id", $ids);
      }
    }
  }
}
