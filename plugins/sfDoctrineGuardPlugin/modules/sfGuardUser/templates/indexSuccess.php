<script type="text/javascript">
  $(document).ready(function(){
    // Object actions
    $('.sf_admin_row_object_actions a.fancybox').fancybox({
      overlayColor: "#000",
      showCloseButton: false,
      padding: 0,
      margin: 0
    });
    $('.sf_admin_row').live('click', function(){
      $(this).next('.sf_admin_row_object_actions').find('a.fancybox').click();
    });
    // Batch actions
    if($('.contentbox tfoot select[name=batch_action] option').length <= 1) {
      $('.contentbox tfoot select[name=batch_action], .contentbox tfoot input:submit, .contentbox tfoot input:hidden').remove();
    }
  });
</script>
<?php use_helper('I18N', 'Date') ?>
<?php $sf_response->addMeta('title', 'Administration | '.__('Utilisateurs', array(), 'messages')) ?>
<?php slot('breadcrumb', array(array('url' => '@sf_guard_user', 'label' => __('Utilisateurs', array(), 'messages')))) ?>
<?php include_partial('sfGuardUser/assets') ?>
<?php include_partial('sfGuardUser/flashes') ?>

<?php if($sf_user->isSuperAdmin()): ?>
<div class="contentcontainer sml right">
  <?php include_partial('sfGuardUser/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
</div>
<?php endif ?>
<div class="contentcontainer med left">
  <div class="headings">
    <h2><?php echo __('Utilisateurs', array(), 'messages') ?></h2>
  </div>

  <?php include_partial('sfGuardUser/list_header', array('pager' => $pager)) ?>
  
  <div class="contentbox">
    <form action="<?php echo url_for('sf_guard_user_collection', array('action' => 'batch')) ?>" method="post">
    <?php include_partial('sfGuardUser/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
    </form>
  </div>

  <?php include_partial('sfGuardUser/list_footer', array('pager' => $pager)) ?>
</div>
<div style="clear: both;"></div>
