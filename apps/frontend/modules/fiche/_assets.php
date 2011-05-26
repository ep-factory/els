<?php use_stylesheet('/sfDoctrinePlugin/css/global.css', 'first') ?>
<?php use_stylesheet('/sfDoctrinePlugin/css/default.css', 'first') ?>
<?php use_javascript('/sfEPFactoryFormPlugin/js/tools.js') ?>
<script type="text/javascript">
  $(document).ready(function(){
    /*$('.sf_admin_form_field_end_hour input, .sf_admin_form_field_start_hour input').live('change', function(){
      var $start = new Date(0, 0, 0, $('#fiche_start_hour').val().replace(/(\d{2})h(\d{2})/, '$1'), $('#fiche_start_hour').val().replace(/(\d{2})h(\d{2})/, '$2'), 0);
      var $end = new Date(0, 0, 0, $('#fiche_end_hour').val().replace(/(\d{2})h(\d{2})/, '$1'), $('#fiche_end_hour').val().replace(/(\d{2})h(\d{2})/, '$2'), 0);
      var $diff = new Date($end-$start);
      $('.sf_admin_form_field_time_spent span').html(str_pad($diff.getHours()-1, 2, 0, 'STR_PAD_LEFT') + "h" + str_pad($diff.getMinutes(), 2, 0, 'STR_PAD_LEFT'));
    });*/
  });
</script>