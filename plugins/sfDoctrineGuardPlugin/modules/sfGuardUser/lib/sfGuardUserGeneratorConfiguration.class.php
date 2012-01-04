<?php

/**
 * sfGuardUser module configuration.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: sfGuardUserGeneratorConfiguration.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardUserGeneratorConfiguration extends BaseSfGuardUserGeneratorConfiguration
{
  public function getFieldsDefault() {
    $fields = parent::getFieldsDefault();
    $fields['groups_list']['is_real'] = true;
    $fields['permissions_list']['is_real'] = true;
    return $fields;
  }
}
