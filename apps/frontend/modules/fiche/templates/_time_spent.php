<?php if(!isset($fiche)) $fiche = $form->getObject() ?>
<?php if(!$fiche->getTimeSpent()) return ?>
<div class="sf_admin_show_row sf_admin_text sf_admin_show_field_time_spent">
  <strong>Temps passé</strong>
  <span><?php echo $fiche->getTimeSpent() ?></span>
</div>