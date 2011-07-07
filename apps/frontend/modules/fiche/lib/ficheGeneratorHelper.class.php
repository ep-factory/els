<?php

/**
 * fiche module helper.
 *
 * @package    els
 * @subpackage fiche
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: helper.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ficheGeneratorHelper extends BaseFicheGeneratorHelper {

  public function linkToNew($params) {
    return '<li class="sf_admin_action_add">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('new').(isset($params['category_id']) ? "?category_id=".$params['category_id'] : null), array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }

  public function linkToEdit($object, $params) {
    return $this->getUser()->canEdit($object->getRawValue()) ? parent::linkToEdit($object, $params) : null;
  }

  /**
   *
   * @return myUser
   */
  protected function getUser() {
    return sfContext::getInstance()->getUser();
  }

}
