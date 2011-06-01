<?php $widget = new sfWidgetFormInputSwitch(array('choices' => array('Désactivé', 'Activé'))) ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#enable_keyboard label').live('click', function(){
      var $label = $(this);
      $.ajax({
        url: "<?php echo url_for('@enable_keyboard') ?>",
        data: "enable=" + $label.attr('for').replace(/^keyboard_(\d)$/i, '$1'),
        success: function(){
          // Need to reload current page to force keyboard
          location.reload();
        }
      });
    });
  });
</script>
<div id="enable_keyboard">
  <span>Clavier</span>
  <?php echo $widget->render('keyboard', $sf_user->getAttribute('enable_keyboard', true)) ?>
</div>