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
  public function configure()
  {
    // Search
    $this->widgetSchema['search'] = new sfWidgetFormInputText();
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
  }
  
  public function addSearchColumnQuery(Doctrine_Query $query, $field, $values)
  {
    foreach($this->getTable()->getColumns() as $name => $options)
    {
      if(in_array($options['type'], array('string', 'clob'))) {
        $query->orWhere($query->getRootAlias().".$name = ?", $values);
      }
    }
  }
}
