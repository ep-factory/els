<?php

/**
 * sfWidgetFormKeyboard represents a virtual keyboard.
 *
 * Available options:
 *
 *  * layout:           Keyboard layout (default: "arabic-azerty")
 *  * maxLength:        Input max length (default: "false")
 *  * renderer_class:   Widget rendering (default: "sfWidgetFormInputText")
 *  * renderer_options: Widget options (default: array())
 *  * theme:            jQuery path to css theme (default: "/sfEPFactoryFormPlugin/jqueryui/smoothness/jquery-ui.css")
 *  * url:              Url for autocomplete
 *
 * @package    symfony
 * @subpackage widget
 * @author     Daniel Santiago <daniel.santiago@highlevelwebs.com>
 * @version    SVN: $Id: sfWidgetFormInputPlain.class.php 68 2009-06-19 04:33:41Z hlwebs $
 */
class sfWidgetFormKeyboard extends sfWidgetForm {

  protected function configure($options = array(), $attributes = array()) {
    $this->addOption("layout", "arabic-azerty");
    $this->addOption("maxLength", "false");
    $this->addOption("renderer_class", "sfWidgetFormInputText");
    $this->addOption("renderer_options", array());
    $this->addOption('theme', '/sfEPFactoryFormPlugin/jqueryui/smoothness/jquery-ui.css');
    $this->addOption('url', null);
    $this->addOption('caching', true);
  }

  public function render($name, $value = null, $attributes = array(), $errors = array()) {
    // Prepare widget
    $class = $this->getOption('renderer_class');
    if(!class_exists($class)) {
      throw new InvalidArgumentException("Unable to find class $class.");
    }
    $widget = new $class($this->getOption('renderer_options'));
    return sprintf(<<<EOF
<script type="text/javascript">
  $(document).ready(function(){
    %s
    $("#%s").keyboard({
      layout: "%s",
      maxLength: %s,
      autoAccept: true
    })%s;
  });
</script>
EOF
            , $this->getOption('caching') ? sprintf('var cache_%s = {};', $this->generateId($name, $value)) : null
            , $this->generateId($name, $value)
            , $this->getOption("layout")
            , $this->getOption("maxLength")
            , $this->getOption('url') ? sprintf(<<<EOF
.autocomplete({
      source: function(request, response){
        var xhr = null;
        %s
        xhr = $.ajax({
          url: '%s',
          type: 'post',
          dataType: 'json',
          data: {
            term: request.term
          },
          success: function(data){
            var datas = new Array();
            for(var i in data) {
              datas.push(data[i].name);
            }
            %s
            response(datas);
          }
        });
      }
    }).addAutocomplete()
EOF
                            , $this->getOption('caching') ? sprintf(<<<EOF
if(request.term in cache_%s) {
          response(cache_%s[request.term]);
          return;
        }
EOF
                                            , $this->generateId($name, $value)
                                            , $this->generateId($name, $value)
                                    ) : null
                            , $this->getOption('url')
                            , $this->getOption('caching') ? sprintf('cache_%s[request.term] = datas;', $this->generateId($name, $value)) : null
                    ) : null
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
    if(count($this->getOption('url'))) {
      $javascripts[] = '/sfEPFactoryFormPlugin/jquery-keyboard/jquery.keyboard.extension-autocomplete.js';
    }
    if(!in_array($this->getOption('layout'), array('alpha', 'qwerty', 'international', 'dvorak', 'num'))) {
      $filename = preg_replace('/^([A-z]+)([\-A-z]+)?$/i', '$1', $this->getOption('layout'));
      if(!is_file(sfConfig::get('sf_web_dir')."/sfEPFactoryFormPlugin/jquery-keyboard/layouts/$filename.js")) {
        throw new InvalidArgumentException("You must create a $filename.js file into /sfEPFactoryPlugin/jquery-keyboard/layouts folder.");
      }
      else {
        $javascripts[] = "/sfEPFactoryFormPlugin/jquery-keyboard/layouts/$filename.js";
      }
    }
    return $javascripts;
  }

  public function getStylesheets() {
    return array($this->getOption('theme') => 'screen', '/sfEPFactoryFormPlugin/jquery-keyboard/keyboard.css' => 'screen');
  }

}