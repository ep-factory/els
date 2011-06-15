<?php $widget = new sfWidgetFormInputSwitch(array('choices' => array('Désactivé', 'Activé'))) ?>
<script type="text/javascript">
  $(document).ready(function(){
    $('#enable_keyboard label').live('click', function(){
      var $label = $(this);
      $.ajax({
        url: "<?php echo url_for('@enable_keyboard') ?>",
        data: "enable=" + $label.attr('for').replace(/^keyboard_(\d)$/i, '$1'),
        beforeSend: function(){
          $.fancybox.showActivity();
        },
        error: function(){
          $(this).removeClass('selected').siblings().addClass('selected');
        },
        success: function(){
          location.reload();
        }
      });
    });
  });
</script>
<div id="enable_keyboard">
  <p>Clavier</p>
  <?php echo $widget->render('keyboard', $sf_user->getAttribute('enable_keyboard', true)) ?>
</div>