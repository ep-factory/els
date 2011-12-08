<td class="sf_admin_text sf_admin_list_td_number">
  <?php echo $fiche->getNumber() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_Category">
  <?php echo $fiche->getFirstTag() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_Category">
  <?php echo $fiche->getBatiment() ?>
</td>
<td class="sf_admin_date sf_admin_list_td_fiche_date">
  <?php echo false !== strtotime($fiche->getFicheDate()) ? format_date($fiche->getFicheDate(), "D") : '&nbsp;' ?>
</td>
<?php if($sf_user->hasGroup('technicien')): ?>
  <td class="sf_admin_text sf_admin_list_td_sfGuardUser">
    <?php echo $fiche->getSfGuardUser() ?>
  </td>
<?php elseif($sf_user->hasGroup('coordinateur')): ?>
  <td class="sf_admin_text sf_admin_list_td_sfGuardUser">
    <?php echo $fiche->getSfGuardUser() ?>
  </td>
  <td class="sf_admin_boolean sf_admin_list_td_is_resolved">
    <?php echo get_partial('fiche/list_field_boolean', array('value' => $fiche->getIsResolved())) ?>
  </td>
<?php else: ?>
  <td class="sf_admin_boolean sf_admin_list_td_is_finished">
    <?php echo get_partial('fiche/list_field_boolean', array('value' => $fiche->getIsFinished())) ?>
  </td>
<?php endif ?>

