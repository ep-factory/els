<td class="sf_admin_text sf_admin_list_td_number">
  <?php echo $fiche->getNumber() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_Category">
  <?php echo $fiche->getCategory() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_fiche_date">
  <?php echo false !== strtotime($fiche->getFicheDate()) ? format_date($fiche->getFicheDate(), "f") : '&nbsp;' ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_finished">
  <?php echo $sf_user->hasGroup('technicien') ? $fiche->getSfGuardUser() : ($sf_user->hasGroup('coordinateur') ? get_partial('fiche/list_field_boolean', array('value' => $fiche->getIsResolved())) : get_partial('fiche/list_field_boolean', array('value' => $fiche->getIsFinished()))) ?>
</td>
