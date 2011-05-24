<?php

/**
 * Poste form.
 *
 * @package    els
 * @subpackage form
 * @author     Vincent CHALAMON <vincentchalamon@gmail.com>
 * @version    SVN: $Id: sfDoctrineFormTemplate.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class PosteForm extends BasePosteForm
{
  public function configure()
  {
    unset($this['created_at'], $this['updated_at'], $this['deleted_at']);
  }
}