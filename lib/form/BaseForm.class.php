<?php

/**
 * Base project form.
 * 
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: BaseForm.class.php 20147 2009-07-13 11:46:57Z FabianLange $
 */
class BaseForm extends sfFormSymfony
{
  /**
   * Retrieve context object
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
   * Generate an url
   * 
   * @return String
   */
  protected function genUrl($url){
    return $this->getContext()->getController()->genUrl($url);
  }

  /**
   * Retrieve request object
   * 
   * @return sfWebRequest
   */
  protected function getRequest() {
    return $this->getContext()->getRequest();
  }

  /**
   * Retrieve user object
   * 
   * @return sfGuardSecurityUser
   */
  protected function getUser() {
    return $this->getContext()->getUser();
  }
}
