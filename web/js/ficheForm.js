$(document).ready(function(){
  $('#fancybox-wrap .sf_admin_form form').live('submit', function(event){
    event.preventDefault();
    var $form = $(this);
    var datas = '';
    $('input:not(:submit), textarea, select', $form).each(function(){
      datas += (datas.length ? "&" : null) + $(this).attr('name') + "=" + $(this).val();
    });
    $.ajax({
      url: $form.attr('action'),
      type: $form.attr('method'),
      data: datas,
      dataType: 'json',
      beforeSend: function(){
        $.fancybox.showActivity();
        $('#fancybox-wrap .sf_admin_form form .status').remove();
      },
      complete: function(){
        $.fancybox.hideActivity();
      },
      success: function(data) {
        $.fancybox.close();
        $(data.selector).prepend('<div class="status success"><p class="closestatus"><a href="#" title="Close">x</a></p><p><img src="/sfAdminTemplatePlugin/images/icon_success.png" /><span>Succès!</span> ' + data.message + '</p></div>');
        $(data.selector + ' select:visible').append("<option value='" + data.id + "'>" + data.name + "</option>");
      },
      error: function() {
        $('#fancybox-wrap .sf_admin_form form .loading').hide();
        $('#fancybox-wrap .sf_admin_form form input:submit').show();
        $('#fancybox-wrap .sf_admin_form form').prepend('<div class="status error"><p class="closestatus"><a href="#" title="Close">x</a></p><p><img src="/sfAdminTemplatePlugin/images/icon_error.png" /><span>Erreur!</span> Une erreur est survenue.</p></div>');
      }
    });
  });
});