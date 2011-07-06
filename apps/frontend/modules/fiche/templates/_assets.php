<?php slot('menu', 'fiche') ?>
<?php use_javascript('/sfEPFactoryFormPlugin/js/tools.js') ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('.sf_admin_form_field_end_hour input, .sf_admin_form_field_start_hour input').live('change', function(){
      <?php if($sf_user->getAttribute('enable_keyboard', false)): ?>
        var $start = new Date($('#fiche_start_hour_date').val().replace(/(\d{2})\/(\d{2})\/(\d{4})/i, '$2 $1 $3') + " " + $('#fiche_start_hour_hour').val().replace(/(\d{2})h(\d{2})/i, '$1:$2:00'));
        var $end = new Date($('#fiche_end_hour_date').val().replace(/(\d{2})\/(\d{2})\/(\d{4})/i, '$2 $1 $3') + " " + $('#fiche_end_hour_hour').val().replace(/(\d{2})h(\d{2})/i, '$1:$2:00'));
      <?php else: ?>
        var $start = new Date($('#fiche_start_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/i, '$2 $1 $3 $4:$5:00'));
        var $end = new Date($('#fiche_end_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/i, '$2 $1 $3 $4:$5:00'));
      <?php endif ?>
      var $diff = $end.getTime()-$start.getTime() ? $end.getTime()-$start.getTime() : 0;
      $('.sf_admin_show_field_time_spent span').html(str_pad(Math.round($diff/(1000*60*60)), 2, 0, 'STR_PAD_LEFT') + 'h' + str_pad(Math.round(($diff%(1000*60*60))/(1000*60)), 2, 0, 'STR_PAD_LEFT'));
    }).change();
    $('.sf_admin_form_field_is_tested input:radio').live('change', function(){
      if($('.sf_admin_form_field_is_tested input:radio:checked').val() == 1) {
        $('.sf_admin_form_field_test_mechanic, .sf_admin_form_field_test_operator').show();
      }
      else {
        $('.sf_admin_form_field_test_mechanic, .sf_admin_form_field_test_operator').hide();
      }
    }).change();
  });
</script>