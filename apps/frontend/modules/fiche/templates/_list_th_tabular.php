<?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_number">
  <?php if ('number' == $sort[0]): ?>
    <?php echo link_to(__('Numéro', array(), 'messages'), '@fiche', array('query_string' => 'sort=number&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'))) ?>
  <?php else: ?>
    <?php echo link_to(__('Numéro', array(), 'messages'), '@fiche', array('query_string' => 'sort=number&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_Category">
  <?php echo __('TAG', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_text sf_admin_list_th_Category">
  <?php echo __('Bâtiment', array(), 'messages') ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?><?php slot('sf_admin.current_header') ?>
<th class="sf_admin_date sf_admin_list_th_fiche_date">
  <?php if ('fiche_date' == $sort[0]): ?>
    <?php echo link_to(__('Date', array(), 'messages'), '@fiche', array('query_string' => 'sort=fiche_date&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
    <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'))) ?>
  <?php else: ?>
    <?php echo link_to(__('Date', array(), 'messages'), '@fiche', array('query_string' => 'sort=fiche_date&sort_type=asc')) ?>
  <?php endif; ?>
</th>
<?php end_slot(); ?>
<?php include_slot('sf_admin.current_header') ?>

<?php if($sf_user->hasGroup('technicien')): ?>
  <?php slot('sf_admin.current_header') ?>
  <th class="sf_admin_text sf_admin_list_th_sfGuardUser">
    <?php echo __('Intervenant', array(), 'messages') ?>
  </th>
  <?php end_slot(); ?>
  <?php include_slot('sf_admin.current_header') ?>
<?php elseif($sf_user->hasGroup('coordinateur')): ?>
  <?php slot('sf_admin.current_header') ?>
  <th class="sf_admin_boolean sf_admin_list_th_is_resolved">
    <?php echo __('Intervenant', array(), 'messages') ?>
  </th>
  <th class="sf_admin_boolean sf_admin_list_th_is_resolved">
    <?php echo __('Résolue', array(), 'messages') ?>
  </th>
  <?php end_slot(); ?>
  <?php include_slot('sf_admin.current_header') ?>
<?php else: ?>
  <?php slot('sf_admin.current_header') ?>
  <th class="sf_admin_boolean sf_admin_list_th_is_finished">
    <?php if ('is_finished' == $sort[0]): ?>
      <?php echo link_to(__('Cloturé', array(), 'messages'), '@fiche', array('query_string' => 'sort=is_finished&sort_type='.($sort[1] == 'asc' ? 'desc' : 'asc'))) ?>
      <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/'.$sort[1].'.png', array('alt' => __($sort[1], array(), 'sf_admin'), 'title' => __($sort[1], array(), 'sf_admin'))) ?>
    <?php else: ?>
      <?php echo link_to(__('Cloturé', array(), 'messages'), '@fiche', array('query_string' => 'sort=is_finished&sort_type=asc')) ?>
    <?php endif; ?>
  </th>
  <?php end_slot(); ?>
  <?php include_slot('sf_admin.current_header') ?>
<?php endif ?>
