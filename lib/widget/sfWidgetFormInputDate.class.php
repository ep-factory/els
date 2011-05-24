<?php

class sfWidgetFormInputDate extends sfWidgetFormInputText
{
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if($value != null)
    {
      // Database format
      if(preg_match("/(\d{4})-(\d{2})-(\d{2})/", $value))
      {
        $value = substr($value, 8, 2).'/'.substr($value, 5, 2).'/'.substr($value, 0, 4);
      }
    }
    return parent::render($name, $value, $attributes, $errors);
  }

}
