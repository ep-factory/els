<?php if(!isset($fiche)) $fiche = $form->getObject() ?>
<?php if(!strtotime($fiche->getEndHour()) || !strtotime($fiche->getStartHour())) return ?>
<?php $diff = strtotime($fiche->getEndHour()) - strtotime($fiche->getStartHour()) ?>
<div class="sf_admin_show_row sf_admin_text sf_admin_show_field_time_spent">
  <label>Temps passé</label>
  <span><?php echo myUser::convert_seconds_to_time($diff ? $diff*1000 : 0) ?></span>
</div>