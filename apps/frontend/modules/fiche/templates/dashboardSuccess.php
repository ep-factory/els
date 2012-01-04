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
<?php $sf_response->addMeta('title', 'Administration | '.__('Fiches', array(), 'messages')) ?>
<?php include_partial('fiche/assets') ?>
<?php slot('menu', 'search') ?>
<?php include_partial('fiche/flashes') ?>

<div class="contentcontainer sml right">
  <?php include_partial('fiche/filters', array('form' => $filters, 'configuration' => $configuration)) ?>
</div>
<div class="contentcontainer med left">
  <div class="headings">
    <h2><?php echo __('Fiches', array(), 'messages') ?></h2>
  </div>

  <?php include_partial('fiche/list_header', array('pager' => $pager)) ?>
  
  <div class="contentbox">
    <?php include_partial('fiche/list', array('pager' => $pager, 'sort' => $sort, 'helper' => $helper)) ?>
  </div>

  <?php include_partial('fiche/list_footer', array('pager' => $pager)) ?>
</div>
<div style="clear: both;"></div>
