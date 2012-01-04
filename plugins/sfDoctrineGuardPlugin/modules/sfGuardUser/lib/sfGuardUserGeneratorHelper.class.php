<?php

/**
 * sfGuardUser module helper.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: sfGuardUserGeneratorHelper.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardUserGeneratorHelper extends BaseSfGuardUserGeneratorHelper
{
  public function linkToEnable($object, $params) {
    if($object->getIsActive()) {
      return null;
    }
    return '<li class="sf_admin_action_enable">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('enable'), $object, array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }
  
  public function linkToDisable($object, $params) {
    if(!$object->getIsActive()) {
      return null;
    }
    return '<li class="sf_admin_action_disable">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('disable'), $object, array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }
}
