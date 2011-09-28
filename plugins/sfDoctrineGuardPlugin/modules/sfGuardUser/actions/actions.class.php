<?php

require_once dirname(__FILE__).'/../lib/sfGuardUserGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/sfGuardUserGeneratorHelper.class.php';

/**
 * sfGuardUser actions.
 *
 * @package    sfGuardPlugin
 * @subpackage sfGuardUser
 * @author     Fabien Potencier
 * @version    SVN: $Id: actions.class.php 23319 2009-10-25 12:22:23Z Kris.Wallsmith $
 */
class sfGuardUserActions extends autoSfGuardUserActions
{
  protected function buildQuery() {
    $query = parent::buildQuery();
    if(!$this->getUser()->isSuperAdmin()) {
      $query->andWhere($query->getRootAlias().".id = ?", $this->getUser()->getGuardUser()->getPrimaryKey());
    }
    return $query;
  }
  
  public function executeDisable(sfWebRequest $request) {
    $this->getRoute()->getObject()->setIsActive(false)->save();
    $this->getUser()->setFlash('notice', "Le compte utilisateur a été correctement désactivé.");
    $this->redirect('@sf_guard_user');
  }
  
  public function executeEnable(sfWebRequest $request) {
    $this->getRoute()->getObject()->setIsActive(true)->save();
    $this->getUser()->setFlash('notice', "Le compte utilisateur a été correctement activé.");
    $this->redirect('@sf_guard_user');
  }
}
