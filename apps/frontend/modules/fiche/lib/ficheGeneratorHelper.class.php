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

  protected $categoryId;

  public function setCategoryId($categoryId) {
    $this->categoryId = $categoryId;
  }

  public function linkToNew($params) {
    return '<li class="sf_admin_action_add">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('new').($this->categoryId ? "?category_id=$this->categoryId" : null), array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }

  public function linkToEdit($object, $params) {
    if(($this->getUser()->hasGroup('technicien') && !is_null($this->fiche->getSfGuardUserId()) && ($this->fiche->getIsResolved() || $this->fiche->getIsClosed() || $this->fiche->getSfGuardUserId() != $this->getUser()->getGuardUser()->getPrimaryKey())) || ($this->getUser()->hasGroup('coordinateur') && $this->fiche->getIsClosed())) {
      return;
    }
    return parent::linkToEdit($object, $params);
  }

  /**
   *
   * @return sfGuardSecurityUser
   */
  protected function getUser() {
    return sfContext::getInstance()->getUser();
  }

}
