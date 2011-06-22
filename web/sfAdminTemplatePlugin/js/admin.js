function getMenuWidth()
{
  var width = 0;
  $('#main_menu li').each(function(){
    width += $(this).outerWidth(true);
  });
  return width;
}

$(document).ready(function(){
  // Main menu
  if(getMenuWidth() > $('#main_menu').outerWidth(true))
  {
    $('#main_menu').append($('<a href="#" id="main_menu_previous" style="display: none;">&lang;</a>'));
    $('#main_menu').prepend($('<a href="#" id="main_menu_next">&rang;</a>'));
  }
  $('#main_menu_previous').live('click', function(event){
    event.preventDefault();
    $('#main_menu ul').animate({left: 0}, "slow");
    $('#main_menu_previous').fadeOut();
    $('#main_menu_next').fadeIn();
  });
  $('#main_menu_next').live('click', function(event){
    event.preventDefault();
    $('#main_menu ul').animate({left: "-=" + (getMenuWidth() - $('#main_menu').outerWidth(true))}, "slow");
    $('#main_menu_previous').fadeIn();
    $('#main_menu_next').fadeOut();
  });

  // Login form
  $('#innerlogin form').live('submit', function(){
    $('input:submit', $(this)).hide()
    $('.loading', $(this)).show();
  });

  // Form placeholder
  if(!navigator.userAgent.match('Chrome')) {
    $('input:text, textarea').focus(function(){
      if($(this).val() == $(this).attr('title')) {
        $(this).val('');
      }
    }).blur(function(){
      if(!$(this).val()) {
        $(this).val($(this).attr('title'));
      }
    }).each(function(){
      if(!$(this).val()) {
        $(this).val($(this).attr('title'));
      }
    });
  }
  else {
    $('body').addClass('chrome');
  }

  // Flashes
  $('.closestatus a').live('click', function(event){
    event.preventDefault();
    $(this).parents('.status').fadeOut();
  });

  // Fancybox
  $('.fancybox').fancybox({
    overlayColor: "#000",
    padding: 0,
    margin: 0
  });

  // jqTransform
  $(".sf_admin_form select:visible:not(.noTransform)").jqTransSelect();
  $(".sf_admin_form input:visible:radio:not(.noTransform)").jqTransRadio();
  $(".sf_admin_form input:visible:checkbox:not(.noTransform)").jqTransCheckBox();
  $('input:visible:text:not(.noTransform), input:visible:password:not(.noTransform)').jqTransInputText();
  $('textarea:visible:not(.ckeditorDone, .noTransform)').jqTransTextarea();
  $('input:visible:submit:not(.btn, .btnalt, .noTransform), input:visible:reset:not(.btn, .btnalt, .noTransform), input:visible:button:not(.btn, .btnalt, .noTransform)').jqTransInputButton();
});