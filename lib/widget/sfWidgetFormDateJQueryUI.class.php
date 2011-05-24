<?php

/**
 * @author     Artur Rozek
 * @version    1.0.0
 */
class sfWidgetFormDateJQueryUI extends sfWidgetForm
{

  /**
   * Configures the current widget.
   *
   * Available options:
   *
   * @param string   culture           Sets culture for the widget
   * @param boolean  change_month      If date chooser attached to widget has month select dropdown, defaults to false
   * @param boolean  change_year       If date chooser attached to widget has year select dropdown, defaults to false
   * @param integer  number_of_months  Number of months visible in date chooser, defaults to 1
   * @param boolean  show_button_panel If date chooser shows panel with 'today' and 'done' buttons, defaults to false
   * @param string   theme             css theme for jquery ui interface, defaults to '/sfJQueryUIPlugin/css/ui-lightness/jquery-ui.css'
   *
   * @see sfWidgetForm
   */
  protected function configure($options = array(), $attributes = array())
  {
    $this->addOption('culture', "fr");
    $this->addOption('change_month', false);
    $this->addOption('change_year', false);
    $this->addOption('number_of_months', 1);
    $this->addOption('show_button_panel', false);
    $this->addOption('show_previous_dates', true);
    $this->addOption('theme', 'jqueryui/jquery-ui-1.8.4.custom.css');
    parent::configure($options, $attributes);
  }

  /**
   * @param  string $name        The element name
   * @param  string $value       The date displayed in this widget
   * @param  array  $attributes  An array of HTML attributes to be merged with the default HTML attributes
   * @param  array  $errors      An array of errors for the field
   *
   * @return string An HTML tag string
   *
   * @see sfWidgetForm
   */
  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    if(preg_match("/(\d{4})\-(\d{2})\-(\d{2})/i", $value, $matches)) $value = $matches[3]."/".$matches[2]."/".$matches[1];
    $attributes = $this->getAttributes();
    $input = new sfWidgetFormInput(array(), $attributes);
    $html = $input->render($name, $value);
    $id = $input->generateId($name);
    $culture = $this->getOption('culture');
    $cm = $this->getOption("change_month") ? "true" : "false";
    $cy = $this->getOption("change_year") ? "true" : "false";
    $nom = $this->getOption("number_of_months");
    $minDate = !$this->getOption("show_previous_dates") ? "new Date()" : "";
    $sbp = $this->getOption("show_button_panel") ? "true" : "false";
    if($culture != 'en')
    {
      $html .= <<<EOHTML
<script type="text/javascript">
  $(function() {
    var params = {
      regional : '$culture',
      changeMonth : $cm,
      changeYear : $cy,
      numberOfMonths : $nom,
      dateFormat: 'dd/mm/yy',
      showButtonPanel : $sbp
    };
    $.datepicker.regional['$culture'];
    $("#$id").datepicker(params);
  });
</script>
EOHTML;
    }
    else
    {
      $html .= <<<EOHTML
<script type="text/javascript">
  $(function() {
    var params = {
    changeMonth : $cm,
    changeYear : $cy,
    numberOfMonths : $nom,
    dateFormat: 'dd/mm/yy',
    showButtonPanel : $sbp };
    $("#$id").datepicker(params);
  });
</script>
EOHTML;
    }
    return $html;
  }

  /*
   *
   * Gets the stylesheet paths associated with the widget.
   *
   * @return array An array of stylesheet paths
   */

  public function getStylesheets()
  {
    return array($this->getOption('theme') => 'screen');
  }

  /**
   * Gets the JavaScript paths associated with the widget.
   *
   * @return array An array of JavaScript paths
   */
  public function getJavaScripts()
  {
    $js = array('jqueryui/jquery-ui-1.8.4.custom.min.js');
    $culture = $this->getOption('culture');
    if($culture != 'en') $js[] = "jqueryui/i18n/ui.datepicker-$culture.js";
    return $js;
  }

}
