<?php

/**
 * Document form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class DocumentForm extends BaseDocumentForm {

  public function configure() {
    unset($this['created_at'], $this['updated_at'], $this['deleted_at']);
    $this->widgetSchema['filename'] = new sfWidgetFormInputUploadify(array(
        'url' => $this->genUrl('@uploadify'),
        'path' => '/uploads',
        'max' => 1,
        'fileDesc' => 'Documents'
      ), array('class' => 'noTransform'));
    $this->widgetSchema['tags'] = new sfWidgetFormInputToken(array('url' => $this->genUrl('@fiche_tags_autocomplete')));
    $this->getWidgetSchema()->setHelp('tags', 'Séparez vos tags par des virgules : toto, tata, titi.');
    $this->validatorSchema['tags'] = new sfValidatorString(array('required' => false));
    if($this->getUser()->getAttribute('enable_keyboard', false)) {
      $this->widgetSchema['name'] = new sfWidgetFormKeyboard();
      $this->widgetSchema['tags'] = new sfWidgetFormKeyboard(array(
          'url' => $this->genUrl('@fiche_tags_autocomplete'),
          'multi' => true,
          'renderer_class' => sfWidgetFormInputText
      ));
    }
  }

  /**
   * Update form fields with object values
   */
  public function updateDefaultsFromObject() {
    parent::updateDefaultsFromObject();
    // Tags
    if(isset($this->widgetSchema['tags'])) {
      $tags = $this->getObject()->getTags();
      if($this->getUser()->getAttribute('enable_keyboard', false)) {
        $tags = implode(', ', $tags);
      }
      $this->widgetSchema['tags']->setDefault($tags);
    }
  }

}
