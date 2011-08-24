<?php slot('menu', 'fiche') ?>
<?php use_helper('JavascriptBase') ?>
<?php use_javascript('/sfEPFactoryFormPlugin/js/tools.js') ?>
<style type="text/css">
  <?php if($sf_request->getParameter('action') == 'show' && ($fiche->getIsControlled() || (!$fiche->getUnsolvedName() && !$fiche->getUnsolvedDate()))): ?>
    .sf_admin_show_field_unsolved_name, .sf_admin_show_field_unsolved_date {
      display: none;
    }
  <?php endif ?>
  .sf_admin_show_field_Ppc strong,
  .sf_admin_show_field_ppi_number strong,
  .sf_admin_show_field_acr_number strong,
  .sf_admin_show_field_mo_number strong{
    width: 230px;
  }
  .sf_admin_show_field_Ppc span,
  .sf_admin_show_field_ppi_number span,
  .sf_admin_show_field_acr_number span,
  .sf_admin_show_field_mo_number span{
    width: 380px;
  }
  .sf_admin_form_field_unsolved_name, .sf_admin_form_field_unsolved_date,
  .sf_admin_form_field_test_mechanic, .sf_admin_form_field_test_operator
  {
    width: 49%;
    float: left;
    clear: none !important;
  }
  .sf_admin_form_field_unsolved_date, .sf_admin_form_field_test_operator {
    float: right;
  }
  .sf_admin_form_field_unsolved_date label {
    margin-bottom: 12px;
  }
</style>
<script type="text/javascript">
  $(document).ready(function(){
    // Temps passé
    $('#fiche_start_hour, #fiche_end_hour').live('change', function(){
      var $start = new Date($('#fiche_start_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})H(\d{2})/i, '$2 $1 $3 $4:$5:00'));
      var $end = new Date($('#fiche_end_hour').val().replace(/(\d{2})\/(\d{2})\/(\d{4}) (\d{2})H(\d{2})/i, '$2 $1 $3 $4:$5:00'));
      var $diff = $end.getTime()-$start.getTime() ? $end.getTime()-$start.getTime() : 0;
      $('.sf_admin_show_field_time_spent span').html(str_pad(Math.round($diff/(1000*60*60)), 2, 0, 'STR_PAD_LEFT') + 'h' + str_pad(Math.round(($diff%(1000*60*60))/(1000*60)), 2, 0, 'STR_PAD_LEFT'));
    }).change();
    // Testé
    $('.sf_admin_form_field_is_tested input:radio').live('change', function(){
      if($('.sf_admin_form_field_is_tested input:radio:checked').val() == 1) {
        $('.sf_admin_form_field_test_mechanic, .sf_admin_form_field_test_operator').show();
      }
      else {
        $('.sf_admin_form_field_test_mechanic, .sf_admin_form_field_test_operator').hide();
      }
    }).change();
    // Résolue
    $('.sf_admin_form_field_is_controlled input:radio').live('change', function(){
      if($('.sf_admin_form_field_is_controlled input:radio:checked').val() == 1) {
        $('.sf_admin_form_field_unsolved_name, .sf_admin_form_field_unsolved_date').hide();
      }
      else {
        $('.sf_admin_form_field_unsolved_name, .sf_admin_form_field_unsolved_date').show();
      }
    }).change();
    // Poste - Appareil
    $('.jqTransformSelectWrapper ul').each(function(){
      if($(this).find('li').length <= 5) {
        $(this).css('height', 'auto');
      }
    });
  });
</script>