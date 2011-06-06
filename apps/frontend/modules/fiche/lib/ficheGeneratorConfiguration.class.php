<?php

/**
 * fiche module configuration.
 *
 * @package    els
 * @subpackage fiche
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: configuration.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ficheGeneratorConfiguration extends BaseFicheGeneratorConfiguration
{
  public function getFieldsDefault() {
    $fields = parent::getFieldsDefault();
    $fields['intervenants_list']['is_real'] = true;
    $fields['elements_list']['is_real'] = true;
    $fields['ateliers_list']['is_real'] = true;
    $fields['tags']['is_real'] = true;
    return $fields;
  }
}
