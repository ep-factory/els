<?php

/**
 * Base project form.
 * 
 * @package    els
 * @subpackage form
 * @author     Your name here 
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
  /**
   *
   * @return sfContext
   */
  protected function getContext() {
    if(!sfContext::hasInstance()) {
      throw new sfException('No default context');
    }
    return sfContext::getInstance();
  }

  /**
   *
   * @return String
   */
  protected function genUrl($url){
    return $this->getContext()->getController()->genUrl($url);
  }

  /**
   *
   * @return sfWebRequest
   */
  protected function getRequest() {
    return $this->getContext()->getRequest();
  }

  /**
   *
   * @return sfGuardSecurityUser
   */
  protected function getUser() {
    return $this->getContext()->getUser();
  }
}
