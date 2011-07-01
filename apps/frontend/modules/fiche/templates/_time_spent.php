<?php if(!isset($fiche)) $fiche = $form->getObject() ?>
<?php $diff = strtotime($fiche->getEndHour()) - strtotime($fiche->getStartHour()) ?>
<div class="sf_admin_show_row sf_admin_text sf_admin_show_field_time_spent">
  <label>Temps passé</label>
  <span><?php echo gmdate('H\hi', $diff ? $diff : 0) ?></span>
</div>