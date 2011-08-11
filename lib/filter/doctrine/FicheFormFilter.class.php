<?php

/**
 * Fiche filter form.
 *
 * @package    els
 * @subpackage filter
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormFilterTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class FicheFormFilter extends BaseFicheFormFilter
{
  /**
   * Init filters
   */
  public function configure()
  {
    // Search
    $this->widgetSchema['search'] = new sfWidgetFormInputText();
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['search'] = new sfWidgetFormKeyboard();
    }
    $this->widgetSchema['search']->setAttribute("title", "Rechercher");
    $this->widgetSchema['search']->setAttribute("placeholder", "Rechercher");
    $this->widgetSchema['search']->setAttribute("alt", "Rechercher");
    $this->validatorSchema['search'] = new sfValidatorPass();

    // Is finished
    $this->widgetSchema['is_finished']->setOption('renderer_class', 'sfWidgetFormSelectRadio');
    $this->widgetSchema['is_finished']->setOption('choices', array('' => 'tous', 1 => 'oui', 0 => 'non'));

    // Fiche date
    $this->widgetSchema['fiche_date']->setOption('from_date', new sfWidgetFormDateJQueryUI(array(), array('title' => 'Depuis le', 'placeholder' => 'Depuis le')));
    $this->widgetSchema['fiche_date']->setOption('to_date', new sfWidgetFormDateJQueryUI(array(), array('title' => "Jusqu'au", 'placeholder' => "Jusqu'au")));
    $this->validatorSchema['fiche_date']->setOption('from_date', new sfValidatorDateCustom(array('required' => false)));
    $this->validatorSchema['fiche_date']->setOption('to_date', new sfValidatorDateCustom(array('required' => false)));

    // Created at
    $this->widgetSchema['created_at']->setOption('from_date', new sfWidgetFormDateJQueryUI(array(), array('title' => 'Depuis le', 'placeholder' => 'Depuis le')));
    $this->widgetSchema['created_at']->setOption('to_date', new sfWidgetFormDateJQueryUI(array(), array('title' => "Jusqu'au", 'placeholder' => "Jusqu'au")));
    $this->validatorSchema['created_at']->setOption('from_date', new sfValidatorDateCustom(array('required' => false)));
    $this->validatorSchema['created_at']->setOption('to_date', new sfValidatorDateCustom(array('required' => false)));

    // Start hour
    $this->widgetSchema['start_hour']->setOption('from_date', new sfWidgetFormTimestamp(array(), array('title' => 'Depuis le', 'placeholder' => 'Depuis le')));
    $this->widgetSchema['start_hour']->setOption('to_date', new sfWidgetFormTimestamp(array(), array('title' => "Jusqu'au", 'placeholder' => "Jusqu'au")));
    $this->validatorSchema['start_hour']->setOption('from_date', new sfValidatorTimestamp(array('required' => false)));
    $this->validatorSchema['start_hour']->setOption('to_date', new sfValidatorTimestamp(array('required' => false)));

    // End hour
    $this->widgetSchema['end_hour']->setOption('from_date', new sfWidgetFormTimestamp(array(), array('title' => 'Depuis le', 'placeholder' => 'Depuis le')));
    $this->widgetSchema['end_hour']->setOption('to_date', new sfWidgetFormTimestamp(array(), array('title' => "Jusqu'au", 'placeholder' => "Jusqu'au")));
    $this->validatorSchema['end_hour']->setOption('from_date', new sfValidatorTimestamp(array('required' => false)));
    $this->validatorSchema['end_hour']->setOption('to_date', new sfValidatorTimestamp(array('required' => false)));
  }

  /**
   * Init search query using all text columns, and tags if table has template
   *
   * @param Doctrine_Query $query Search query
   * @param string $field Form field name
   * @param string $values Search value
   */
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
