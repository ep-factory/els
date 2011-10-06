<?php

/**
 * fiche module configuration.
 *
 * @package    els
 * @subpackage fiche
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ficheGeneratorConfiguration extends BaseFicheGeneratorConfiguration {

  public function getFilterDefaults() {
    return array('fiche_date' => array('from' => date('Y-m-d', sfContext::getInstance()->getUser()->hasGroup('consultant') ? strtotime('+7 days') : strtotime('-7 days')), 'to' => null));
  }

  public function getCategoryFilters(BaseForm $form, Category $category, $isResolved = null) {
    return array(
        $form->getCSRFFieldName() => $form->getCSRFToken(),
        'fiche_date' => array('from' => null, 'to' => null),
        'category_id' => $category->getPrimaryKey(),
        'is_finished' => 0,
        'is_resolved' => $isResolved
        );
  }
  
  public function getFieldsDefault() {
    $allowed = parent::getFieldsDefault();
    $allowed['elements_list']['is_real'] = true;
    $allowed['tags']['is_real'] = true;
    $allowed['time_spent'] = array('is_link' => false, 'is_real' => true, 'is_partial' => false, 'is_component' => false, 'type' => 'Text', 'label' => 'Temps passé');
    $allowed['elements'] = array('is_link' => false, 'is_real' => true, 'is_partial' => false, 'is_component' => false, 'type' => 'Text', 'label' => 'Eléments');
    return $allowed;
  }

  public function getShowDisplay() {
    $fiche = FicheTable::getInstance()->find(sfContext::getInstance()->getRequest()->getParameter('id'));
    $category = $fiche ? $fiche->getCategory()->getCode() : 481;
    switch($category) {
      default:
      case 481:
        return array(
            'Intervenant' => array(
                'sfGuardUser',
                'Poste'),
            'Sécurité' => array(
                'Ppc',
                'ppi_number',
                'acr_number',
                'mo_number'),
            'Intervention' => array(
                '_parent',
                'fiche_date',
                'criticity',
                'Demandeur',
                'appel_hour',
                'start_hour',
                'end_hour',
                '_time_spent'),
            'Localisation' => array(
                'Appareil',
                'tags',
                'Batiment',
                'Atelier',
                'Annexe',
                'is_cmr'),
            'Symptomes' => array(
                'problem',
                'cause',
                'solution'),
            'Résolution' => array(
                'is_tested',
                'test_mechanic',
                'test_operator',
                'is_stopped',
                'is_ips',
                'is_controlled',
                'unsolved_name',
                'unsolved_date'),
            'Maintenance' => array('_elements'),
            'Filles' => array('_childrens'));
        break;

      case 472:
        return array(
            'Intervenant' => array(
                'sfGuardUser',
                'Poste',
                'label'),
            'Sécurité' => array(
                'ppi_number',
                'acr_number',
                'mo_number'),
            'Intervention' => array(
                '_parent',
                'fiche_date',
                'criticity',
                'start_hour',
                'end_hour',
                '_time_spent'),
            'Localisation' => array(
                'tags',
                'Batiment',
                'Atelier',
                'Annexe'),
            'Symptomes' => array('solution'),
            'Filles' => array('_childrens'));
        break;

      case 490:
        array(
        'solution');
        return array(
            'Intervenant' => array(
                'sfGuardUser',
                'Poste',
                'label'),
            'Sécurité' => array(
                'ppi_number',
                'acr_number',
                'mo_number'),
            'Intervention' => array(
                '_parent',
                'fiche_date',
                'criticity',
                'start_hour',
                'end_hour',
                '_time_spent'),
            'Localisation' => array(
                'tags',
                'Batiment',
                'Atelier',
                'Annexe',
                'is_cmr'),
            'Symptomes' => array('solution'),
            'Filles' => array('_childrens'));
        break;
    }
  }

  public function retrieveValue(Fiche $object, $name)
  {
    $options = $object->getTable()->getColumnDefinition($name);
    // Default value
    $value = $object->{"get".ucfirst(sfInflector::classify($name))}();
    // Field is relation
    /*if (preg_match('/_id$/i', $name) && $this->retrieveRelationName($object, $name))
    {
      $value = $this->retrieveRelationName($object, $name);
    }*/
    // Field is date
    if ($value && $options['type'] == 'date')
    {
      $value = format_date(strtotime($value), "EEEE dd MMMM yyyy");
    }
    // Field is time
    if ($value && $options['type'] == 'time')
    {
      $value = date('H\hi', strtotime($value));
    }
    // Field is timestamp
    if ($value && $options['type'] == 'timestamp')
    {
      $value = format_date(strtotime($value), "EEEE dd MMMM yyyy")." ".date('H\hi', strtotime($value));
    }
    // Field is boolean
    if ($options['type'] == 'boolean')
    {
      $value = $value ? "<img src='/sfAdminTemplatePlugin/images/icon_ticklist.png' />" : null;
    }
    // Convert array to string
    if (is_array($value) || $value instanceof Doctrine_Collection)
    {
      $html = "";
      foreach ($value as $element)
      {
        $html.= "<li>$element</li>";
      }
      $value = "<ul>$html</ul>";
    }
    // Test if relation exists
    if ($object->getTable()->hasRelation($name))
    {
      if ($object->getTable()->getRelation($name)->getType() == Doctrine_Relation::MANY)
      {
        $relationValues = array();
        foreach ($object[$name] as $relationValue)
        {
          $relationValues[] = (string)$relationValue;
        }
        $value = implode(', ', $relationValues);
      }
      else
      {
        $value = $object[$name] && !$object[$name]->isNew() ? $object[$name] : false;
      }
    }
    return $value;
  }

}
