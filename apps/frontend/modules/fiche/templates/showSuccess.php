<script type="text/javascript">
  $(document).ready(function(){
    $(".contentbox fieldset").each(function(){
      $('.jqTransformInputWrapper', $(this)).width('100%');
      if(!$(".sf_admin_show_row", $(this)).length) {
        $('a[href=#' + $(this).attr('id') + ']').parent().remove();
      }
    });
    $(".contentbox fieldset").each(function(){
      if(!$(".sf_admin_show_row", $(this)).length) {
        $(this).remove();
      }
    });
  });
</script>
<?php if(!$fiche->getIsTested()): ?>
  <style type="text/css">
    .sf_admin_show_field_test_mechanic, .sf_admin_show_field_test_operator {
      display: none;
    }
  </style>
<?php endif ?>
<?php use_helper('I18N', 'Date') ?>
<?php $sf_response->addMeta('title', 'Administration | '.__('Fiches', array(), 'messages').' | '.__('%%number%%', array('%%number%%' => $fiche->getNumber()), 'messages')) ?>
<?php slot('breadcrumb', array(array('url' => '@fiche', 'label' => __('Fiches', array(), 'messages')), array('url' => '@fiche_show?id='.$sf_request->getParameter('id'), 'label' => __('%%number%%', array('%%number%%' => $fiche->getNumber()), 'messages')))) ?>
<?php include_partial('fiche/assets', array('fiche' => $fiche)) ?>
<?php include_partial('fiche/flashes') ?>

<div class="contentcontainer">
  <div class="headings">
    <h2><?php echo __('%%number%%', array('%%number%%' => $fiche->getNumber()), 'messages') ?></h2>
  </div>

  <?php include_partial('fiche/show_header', array('fiche' => $fiche, 'configuration' => $configuration)) ?>

  <div class="contentbox">
    <?php include_partial('fiche/show', array('fiche' => $fiche, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </div>
  
  <?php include_partial('fiche/show_footer', array('fiche' => $fiche, 'configuration' => $configuration)) ?>
</div>
