<?php

require_once dirname(__FILE__).'/../lib/ficheGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/ficheGeneratorHelper.class.php';

/**
 * fiche actions.
 *
 * @package    els
 * @subpackage fiche
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class ficheActions extends autoFicheActions {

  public function executeDashboard(sfWebRequest $request) {
    $this->categories = CategoryTable::getInstance()->findAll();
  }

  public function executeEdit(sfWebRequest $request) {
    parent::executeEdit($request);
    $this->forwardIf(($this->getUser()->hasGroup('technicien') && !is_null($this->fiche->getSfGuardUserId()) && ($this->fiche->getIsResolved() || $this->fiche->getIsClosed() || $this->fiche->getSfGuardUserId() != $this->getUser()->getGuardUser()->getPrimaryKey())) || ($this->getUser()->hasGroup('coordinateur') && $this->fiche->getIsClosed()), sfConfig::get('sf_secure_module'), sfConfig::get('sf_secure_action'));
  }

  public function executeEnableKeyboard(sfWebRequest $request) {
    $this->forward404Unless($request->isXmlHttpRequest() && $request->hasParameter('enable'));
    $this->getUser()->setAttribute('enable_keyboard', $request->getParameter('enable', false) ? true : false);
    return sfView::NONE;
  }

  public function executeTags_autocomplete(sfWebRequest $request) {
    $this->forward404Unless($request->isXmlHttpRequest());
    $tags = TagTable::getInstance()->createQuery("tag")->select("tag.name AS id, tag.name AS name")->where("tag.name LIKE ?", "%".$request->getParameter("q")."%")->limit(10)->fetchArray();
    return $this->renderText(json_encode($tags));
  }

  public function executeResolve(sfWebRequest $request) {
    $this->getRoute()->getObject()->resolve();
    $this->getUser()->setFlash('notice', "L'intervention a été correctement résolue.");
    $this->redirect("@fiche");
  }

  public function executeClose(sfWebRequest $request) {
    $this->getRoute()->getObject()->close();
    $this->getUser()->setFlash('notice', "L'intervention a été correctement fermée.");
    $this->redirect("@fiche");
  }

  public function executeAdd(sfWebRequest $request) {
    $object = $this->getRoute()->getObject();
    $class = get_class($object);
    $values = $object->toArray();
    unset($values['id'], $values['fiche_date'], $values['sf_guard_user_id'], $values['start_hour'], $values['end_hour'], $values['time_spent']);
    $values['parent_id'] = $request->getParameter('id');
    $values['tags'] = $object->getTags();
    $values['Elements'] = $object->getElements();
    $this->fiche = new $class();
    $this->fiche->fromArray($values);
    $this->form = $this->configuration->getForm($this->fiche);
    $this->setTemplate('new');
  }

}