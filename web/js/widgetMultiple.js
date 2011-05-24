$(document).ready(function(){
  // Ajouter une période
  $('.widget_multiple_element .ajouter').live('click', function(event){
    event.preventDefault();
    var $link = $(this);
    var widgetMultipleMainContainer = $(this).parents('.widget_multiple:first');
    var clone = $(this).parents('.widget_multiple_element:first').clone().insertAfter($(this).parent());
    clone.find('input, textarea').val('').removeAttr('checked');

    // On remplace les name de tous les inputs de l'objet du premier niveau
    var foundNameArray = widgetMultipleMainContainer.children('.widget_multiple_element').last().prev().find('input, select, textarea').attr("name").match(/(\d+)/ig);
    var newId = parseInt(foundNameArray[foundNameArray.length-1]) + 1;
    clone.find('input, select, textarea, label').each(function(){
      var pattern = "(.*";
      for(var i = 0; i < $link.parents('.widget_multiple').length-1; i++) {
        pattern += "\\[\\d+\\].*";
      }
      pattern += ")\\[(\\d+)\\](.*";
      for(var j = 0; j < $(this).parents('.widget_multiple').length-1-($link.parents('.widget_multiple').length-1); j++) {
        pattern += "\\[\\d+\\].*";
      }
      pattern += ")";
      var regexName = new RegExp(pattern, "i");
      var regexId = new RegExp(pattern.replace(/(\[|\])/ig, '_'), "i");
      if(this.nodeName == "LABEL") {
        $(this).attr('for', $(this).attr("for").replace(regexId, "$1_" + newId + "_$3"))
      }
      else {
        $(this).attr('name', $(this).attr("name").replace(regexName, "$1["+newId+"]$3"));
        $(this).attr('id', $(this).attr("id").replace(regexId, "$1_" + newId + "_$3"));
      }
    });
    if($(this).siblings(".retirer").length) {
      // S'il y a un bouton annuler dans l'élément courant on hide le bouton +
      $(this).hide();
    }
    else {
      // Sinon on remplace le + par un -
      $(this).removeClass('ajouter').addClass('retirer').html("-").attr('title', 'Retirer');
    }
    var maxNumValue = parseInt(widgetMultipleMainContainer.attr("maxnum"));
    if(widgetMultipleMainContainer.attr("maxnum") != undefined && maxNumValue > 0) {
      if($(".widget_multiple_element", widgetMultipleMainContainer).length >= maxNumValue) {
        $(".ajouter", clone).hide();
      }
    }
    if($(this).parents('.widget_multiple:last').attr('callback')) {
      eval($(this).parents('.widget_multiple:last').attr('callback') + "(clone)");
    }
  });

  // Retirer une période
  $('.widget_multiple_element .retirer').live('click', function(event){
    event.preventDefault();
    var widgetMultipleMainContainer = $(this).parents('.widget_multiple:first');
    $('div.formError').click();
    if($(".widget_multiple_element", widgetMultipleMainContainer).length == 1) {
      $(this).parents('.widget_multiple_element:first').hide();
    }
    else {
      $(this).parents('.widget_multiple_element:first').remove();
    }
    var maxNumValue = parseInt(widgetMultipleMainContainer.attr("maxnum"));
    if(maxNumValue != null && maxNumValue) {
      if($(".widget_multiple_element", widgetMultipleMainContainer).length < maxNumValue) {
        if($(".widget_multiple_element", widgetMultipleMainContainer).length == 1 && $(".widget_multiple_element:first", widgetMultipleMainContainer).css("display") == "none") {
          $('.widget_multiple_empty_add_label', widgetMultipleMainContainer).show();
          $(".widget_multiple_element:first", widgetMultipleMainContainer).find('input, select, textarea').attr("disabled", "disabled");
        }
        else {
          $('.widget_multiple_element:last .ajouter', widgetMultipleMainContainer).show();
        }
      }
    }
  });

  // Gestion Empty
  $('.widget_multiple_empty_add_label .ajouter').live('click', function(event){
    event.preventDefault();
    var widgetMultipleMainContainer = $(this).parents('.widget_multiple:first');
    $(".widget_multiple_element:first", widgetMultipleMainContainer).show();
    $(".widget_multiple_element:first", widgetMultipleMainContainer).find('input, select, textarea').removeAttr("disabled");
    $(".widget_multiple_element:first", widgetMultipleMainContainer).parent().show();
    $(this).parents('.widget_multiple_empty_add_label:first').hide();
  });

  // Si la première valeure doit être empty :
  if($('.widget_multiple_empty_add_label .ajouter').length) {
    $('.widget_multiple_empty_add_label .ajouter').each(function(){
      var widgetMultipleMainContainer = $(this).parents('.widget_multiple:first');
      var firstElement = $(".widget_multiple_element:first", widgetMultipleMainContainer);
      firstElement.find('input, select, textarea').attr("disabled", "disabled");
    });
  }
});