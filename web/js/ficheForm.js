function addElementsKeyboard(clone) {
  $('input:text', clone).each(function(){
    $(this).keyboard({
      layout: "arabic-azerty",
      maxLength: false,
      autoAccept: true
    });
  });
}

$(document).ready(function(){
  $('#fancybox-wrap .sf_admin_form form').live('submit', function(event){
    event.preventDefault();
    var datas = '';
    $('input, textarea, select', $(this)).each(function(){
      datas += (datas.length ? "&" : null) + $(this).attr('name') + "=" + $(this).val();
    });
    $.ajax({
      url: $(this).attr('action'),
      type: $(this).attr('method'),
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
        $('.sf_admin_form_field_elements_list').prepend('<div class="status success"><p class="closestatus"><a href="#" title="Close">x</a></p><p><img src="/sfAdminTemplatePlugin/images/icon_success.png" /><span>Succès!</span> L\'élément a été correctement créé.</p></div>');
        $('.sf_admin_form_field_elements_list select').append("<option value='" + data.id + "'>" + data.name + "</option>");
      },
      error: function() {
        $('#fancybox-wrap .sf_admin_form form').prepend('<div class="status error"><p class="closestatus"><a href="#" title="Close">x</a></p><p><img src="/sfAdminTemplatePlugin/images/icon_error.png" /><span>Erreur!</span> Une erreur est survenue.</p></div>');
      }
    });
  });
});