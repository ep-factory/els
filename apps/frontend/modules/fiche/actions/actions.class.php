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
class ficheActions extends autoFicheActions
{
  public function executeEnableKeyboard(sfWebRequest $request) {
    $this->forward404Unless($request->isXmlHttpRequest() && $request->hasParameter('enable'));
    $this->getUser()->setAttribute('enable_keyboard', $request->getParameter('enable', false) ? true : false);
    return sfView::NONE;
  }
  
  public function executeTags_autocomplete(sfWebRequest $request)
  {
    $this->forward404Unless($request->isXmlHttpRequest());
    $tags = TagTable::getInstance()->createQuery("tag")->select("tag.name AS id, tag.name AS name")->where("tag.name LIKE ?", "%".$request->getParameter("q")."%")->limit(10)->fetchArray();
    return $this->renderText(json_encode($tags));
  }
}
