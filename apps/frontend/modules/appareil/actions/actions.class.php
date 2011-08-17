<?php

require_once dirname(__FILE__).'/../lib/appareilGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/appareilGeneratorHelper.class.php';

/**
 * appareil actions.
 *
 * @package    els
 * @subpackage appareil
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class appareilActions extends autoAppareilActions
{
  protected function getRedirect(Appareil $appareil)
  {
    if($this->getRequest()->isXmlHttpRequest()) {
      return "@appareil_close?id=".$appareil->getPrimaryKey();
    }
    elseif($this->getRequest()->hasParameter('_save_and_add')) {
      $this->getUser()->setFlash('notice', 'The item was created successfully. You can add another one below.');
      $this->redirect('@appareil_new');
    }
    return "@appareil";
  }

  public function executeClose(sfWebRequest $request) {
    sfConfig::set('sf_web_debug', false);
    $appareil = $this->getRoute()->getObject();
    return $this->renderText(json_encode(array('selector' => '.sf_admin_form_field_appareil_id', 'message' => "L'appareil a été correctement créé.", 'id' => $appareil->getPrimaryKey(), 'name' => (string)$appareil)));
  }
}
