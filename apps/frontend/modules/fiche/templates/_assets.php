<?php slot('menu', 'fiche') ?>
<?php use_javascript('/sfEPFactoryFormPlugin/js/tools.js') ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('.sf_admin_form_field_end_hour input, .sf_admin_form_field_start_hour input').live('change', function(){
      var $start = new Date($('#fiche_start_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$3'), $('#fiche_start_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$2'), $('#fiche_start_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$1'), $('#fiche_start_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$4'), $('#fiche_start_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$5'), 0);
      var $end = new Date($('#fiche_end_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$3'), $('#fiche_end_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$2'), $('#fiche_end_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$1'), $('#fiche_end_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$4'), $('#fiche_end_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})h(\d{2})/, '$5'), 0);
      var $diff = new Date($end-$start);
      $('.sf_admin_form_field_time_spent span').html(str_pad($diff.getDay(), 2, 0, 'STR_PAD_LEFT') + "/" + str_pad($diff.getMonth(), 2, 0, 'STR_PAD_LEFT') + "/" + str_pad($diff.getYear(), 2, 0, 'STR_PAD_LEFT') + " " + str_pad($diff.getHours()-1, 2, 0, 'STR_PAD_LEFT') + "h" + str_pad($diff.getMinutes(), 2, 0, 'STR_PAD_LEFT'));
      $('.sf_admin_form_field_time_spent input:hidden').val(str_pad($diff.getHours()-1, 2, 0, 'STR_PAD_LEFT') + ":" + str_pad($diff.getMinutes(), 2, 0, 'STR_PAD_LEFT') + ":00");
    });
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