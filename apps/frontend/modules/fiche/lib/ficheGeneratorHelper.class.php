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
    if(!$this->getUser()->hasCredential('create')) {
      return null;
    }
    return '<li class="sf_admin_action_add">'.link_to(__($params['label'], array(), 'sf_admin'), '@'.$this->getUrlForAction('new').(isset($params['category_id']) ? "?category_id=".$params['category_id'] : null), array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }

  public function linkToEdit($object, $params) {
    return !$object->isNew() && $this->getUser()->canEdit($object->getRawValue()) ? parent::linkToEdit($object, $params) : null;
  }

  public function linkToResolve($object, $params) {
    // La fiche est déjà fermée
    // L'utilisateur n'a pas la permission de fermer une fiche
    // L'utilisateur a la permission de fermer sa fiche mais il n'en est pas le propriétaire
    if($object->isNew() || $object->getIsResolved() || !$this->getUser()->hasCredential(array('resolve', 'resolve-own'), false) || ($this->getUser()->hasCredential('resolve-own') && $object->getSfGuardUserId() != $this->getUser()->getGuardUser()->getPrimaryKey())) {
      return null;
    }
    return '<li class="sf_admin_action_resolve">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('resolve'), $object, array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }

  public function linkToUnresolve($object, $params) {
    // La fiche n'est pas fermée
    // L'utilisateur n'a pas la permission de rouvrir une fiche
    // L'utilisateur a la permission de rouvrir sa fiche mais il n'en est pas le propriétaire
    // La fiche est close et l'utilisateur n'est pas Dieu
    if($object->isNew() || !$object->getIsResolved() || !$this->getUser()->hasCredential(array('reopen', 'reopen-own'), false) || ($this->getUser()->hasCredential('reopen-own') && $object->getSfGuardUserId() != $this->getUser()->getGuardUser()->getPrimaryKey()) || ($object->getIsFinished() && !$this->getUser()->isSuperAdmin())) {
      return null;
    }
    return '<li class="sf_admin_action_unresolve">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('unresolve'), $object, array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }

  public function linkToClose($object, $params) {
    // La fiche est déjà close
    // L'utilisateur n'a pas la permission de clore une fiche
    if($object->isNew() || $object->getIsFinished() || !$this->getUser()->hasCredential('close')) {
      return null;
    }
    // La fiche n'a aucun appareil et l'utilisateur n'est pas Dieu
    if(!$this->getUser()->isSuperAdmin() && !$object->getAppareilId()) {
      return link_to_function(__($params['label'], array(), 'sf_admin'), "alert('Vous devez associer un appareil à cette fiche.');");
    }
    return '<li class="sf_admin_action_close">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('close'), $object, array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }

  public function linkToShow($object, $params) {
    if($object->isNew()) {
      return null;
    }
    $action = sfContext::getInstance()->getRequest()->getParameter('action');
    // Nouvelle fiche
    if($object->isNew()) {
      return null;
    }
    // La fiche est close
    if($object->getIsFinished()) {
      // La navigation n'est pas dans la recherche
      // L'utilisateur n'est pas Dieu
      if($action != 'dashboard' && !$this->getUser()->isSuperAdmin()) {
        return null;
      }
      // La navigation est dans la recherche
      // L'utilisateur n'a pas la permission de consulter une fiche close
      if($action == 'dashboard' && !$this->getUser()->hasCredential('show-closed')) {
        return null;
      }
    }
    // La fiche est fermée
    // L'utilisateur n'a pas la permission de consulter une fiche fermée
    if($object->getIsResolved() && !$this->getUser()->hasCredential('show-resolved')) {
      return null;
    }
    return '<li class="sf_admin_action_show">'.link_to(__($params['label'], array(), 'sf_admin'), $this->getUrlForAction('show'), $object, array('title' => __($params['label'], array(), 'sf_admin'))).'</li>';
  }

  /**
   *
   * @return myUser
   */
  protected function getUser() {
    return sfContext::getInstance()->getUser();
  }

}
