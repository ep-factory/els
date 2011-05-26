<?php

class sfWidgetFormKeyboard extends sfWidgetForm {

  protected function configure($options = array(), $attributes = array()) {
    $this->addOption("layout", "qwerty");
    $this->addOption("maxLength", "false");
    $this->addOption("renderer_class", "sfWidgetFormInputText");
    $this->addOption("renderer_options", array());
    $this->addOption('theme', '/sfEPFactoryFormPlugin/jqueryui/smoothness/jquery-ui.css');
  }

  public function render($name, $value = null, $attributes = array(), $errors = array()) {
    $class = $this->getOption('renderer_class');
    if(!class_exists($class)) {
      throw new InvalidArgumentException("Unable to find class $class.");
    }
    $widget = new $class($this->getOption('renderer_options'));
    return sprintf(<<<EOF
<script type="text/javascript">
  $(document).ready(function(){
    $("#%s").keyboard({
      layout: "%s",
      maxLength: %s,
      autoAccept: true
    });
  });
</script>
EOF
            , $this->generateId($name, $value)
            , $this->getOption("layout")
            , $this->getOption("maxLength")
            , $this->generateId($name, $value)
    ).$widget->render($name, $value, $attributes, $errors);
  }

  public function getJavaScripts() {
    $class = $this->getOption('renderer_class');
    $widget = new $class($this->getOption('renderer_options'));
    $javascripts = array_merge($widget->getJavaScripts(), array(
        '/sfEPFactoryFormPlugin/js/jquery.min.js',
        '/sfEPFactoryFormPlugin/jqueryui/jquery-ui.min.js',
        '/sfEPFactoryFormPlugin/jquery-keyboard/jquery.mousewheel.js',
        '/sfEPFactoryFormPlugin/jquery-keyboard/jquery.keyboard.min.js'
        ));
    if(!in_array($this->getOption('layout'), array('alpha', 'qwerty', 'international', 'dvorak', 'num'))) {
      if(!is_file(sfConfig::get('sf_web_dir').'/sfEPFactoryFormPlugin/jquery-keyboard/layouts/'.$this->getOption('layout').'.js')) {
        throw new InvalidArgumentException("You must create a ".$this->getOption('layout').".js file into /sfEPFactoryPlugin/jquery-keyboard/layouts folder.");
      }
      else {
        $javascripts[] = "/sfEPFactoryFormPlugin/jquery-keyboard/layouts/".$this->getOption('layout').".js";
      }
    }
    return $javascripts;
  }

  public function getStylesheets() {
    return array($this->getOption('theme') => 'screen', '/sfEPFactoryFormPlugin/jquery-keyboard/keyboard.css' => 'screen');
  }

}