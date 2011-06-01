<?php

class sfWidgetFormMultiple extends sfWidgetForm {

  protected function configure($options = array(), $attributes = array()) {
    $this->addRequiredOption('widgets');
    $this->addOption("template", "<div class='template widget_multiple_element'>%%widgets%%<a href='#' class='retirer'>-</a></div>");
    $this->addOption("last_template", "<div class='template widget_multiple_element'>%%widgets%%<a href='#' class='ajouter'>+</a></div>");
    $this->addOption("widget_template", "<div class='widget_template %%widgetClass%%'>%%label%%%%input%%</div>");

    //Gestion add_empty FALSE
    $this->addOption("empty_add_label", '<div class="widget_multiple_empty_add_label"><a href="" title="Ajouter" class="ajouter">Ajouter</a></div>');
    $this->addOption("add_empty", true);
    $this->addOption("callback");

    //Gestion d'un max
    $this->addOption("max_number", null);
  }

  public function getJavaScripts() {
    $javascripts = array('/sfEPFactoryFormPlugin/js/jquery.min.js', '/sfEPFactoryFormPlugin/widgetMultiple/widgetMultiple.js');
    foreach($this->getOption("widgets") as $widget) {
      $javascripts = array_merge($javascripts, $widget->getJavaScripts());
    }
    return $javascripts;
  }

  public function getStylesheets() {
    $stylesheets = array('/sfEPFactoryFormPlugin/widgetMultiple/widgetMultiple.css' => 'screen');
    foreach($this->getOption("widgets") as $widget) {
      $stylesheets = array_merge($stylesheets, $widget->getStylesheets());
    }
    return $stylesheets;
  }

  public function getWidgetOption($name, $option, $default = null) {
    $widgets = $this->getOption("widgets");
    return isset($widgets[$name]) ? $widgets[$name]->getOption($option) : $default;
  }

  public function render($name, $value = null, $attributes = array(), $errors = array()) {
    $return = "";
    $count = 0;
    $widgets = $this->getOption("widgets");
    $maxNum = $this->getOption("max_number");

    if(!is_array($widgets) || !count($widgets)) {
      throw new sfException("Vous devez passer un tableau de widgets.", 500);
    }

    // Existing elements
    if(!is_null($value) && !empty($value) && count($value) > 0) {
      foreach($value as $i => $object) {
        //Si pas de add Empty et dernier element on rajoute la ligne avec "last_template"
        if($i == count($value) - 1 && !$this->getOption("add_empty") && $i < $this->getOption("max_number") - 1) {
          $return.= $this->addRow($name, $count, $object, "last_template");
        }
        // Sinon c'est une ligne normale
        else {
          $return.= $this->addRow($name, $count, $object);
        }
        $count++;
      }
    }
    // Si pas de valeurs et pas de "add_empty" on rajoute un champ caché (géré aprés en JS)
    elseif(!$this->getOption("add_empty")) {
      $return.= $this->addRow($name, $count, null, "last_template", true);
    }

    if($this->getOption("add_empty")) {
      $return .= $this->addRow($name, $count, null, "last_template");
    }

    return "<div class='widget_multiple'".($this->getOption("callback") ? sprintf(" callback='%s'", $this->getOption("callback")) : null).($maxNum ? " maxnum='$maxNum'" : null).">$return</div>";
  }

  protected function addRow($name, $count, $object = null, $template = "template", $hidden = false) {
    $widgets = $this->getOption("widgets");
    if(!is_array($widgets) || !count($widgets)) {
      throw new sfException("Vous devez passer un tableau de widgets.", 500);
    }
    $html = "";

    foreach($widgets as $widgetName => $widget) {
      if(!$widget instanceof sfWidgetForm) {
        throw new sfException("Vous devez passer des instances de sfWidgetForm.", 500);
      }
      $widgetValue = $object ? (isset($object[$widgetName]) ? $object[$widgetName] : false) : null;
      $html.= $widget->isHidden() ? $widget->render($name."[$count][$widgetName]", $widgetValue, $widget->getAttributes()) : str_ireplace(array("%%label%%", "%%input%%", "%%widgetClass%%"), array(!$widget->getOption("label") ? null : $this->renderContentTag("label", $widget->getLabel(), array('for' => $widget->generateId($name."[$count][$widgetName]"))), $widget->render($name."[$count][$widgetName]", $widgetValue, $widget->getAttributes()), $widget->getAttribute("widgetClass")), $this->getOption("widget_template"));
    }

    return ($hidden ? $this->getOption("empty_add_label")."<div style='display:none;'>" : null).str_ireplace("%%widgets%%", $html, $this->getOption($template)).($hidden ? "</div>" : null);
  }

}