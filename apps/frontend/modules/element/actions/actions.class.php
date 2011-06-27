<?php

require_once dirname(__FILE__).'/../lib/elementGeneratorConfiguration.class.php';
require_once dirname(__FILE__).'/../lib/elementGeneratorHelper.class.php';

/**
 * element actions.
 *
 * @package    els
 * @subpackage element
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class elementActions extends autoElementActions
{
  protected function getRedirect(Element $element)
  {
    if($this->getUser()->hasCredential('fixtures')) {
      return "@element";
    }
    return "@element_close?id=".$element->getPrimaryKey();
  }

  public function executeClose(sfWebRequest $request) {
    sfConfig::set('sf_web_debug', false);
    $element = $this->getRoute()->getObject();
    return $this->renderText(json_encode(array('id' => $element->getPrimaryKey(), 'name' => (string)$element)));
  }
}
