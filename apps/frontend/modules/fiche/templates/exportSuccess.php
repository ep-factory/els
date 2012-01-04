<?php use_helper('I18N', 'Date') ?>
<?php $sf_response->addMeta('title', 'Administration | '.__('Export', array(), 'messages')) ?>
<?php slot('menu', 'export') ?>
<?php include_partial('fiche/flashes') ?>
<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="contentcontainer med right">
  <div class="headings">
    <h2><?php echo __('Export', array(), 'messages') ?></h2>
  </div>
  
  <div class="contentbox">
    <form action="<?php echo url_for("@export") ?>" method="post">
      <?php echo $form->renderHiddenFields(false) ?>
      <?php if($form->hasGlobalErrors()): ?>
        <?php echo $form->renderGlobalErrors() ?>
      <?php endif ?>
      <?php foreach($form as $name => $field): ?>
        <?php if(!isset($form[$name]) || $form[$name]->isHidden()) continue ?>
        <div class="sf_admin_form_row sf_admin_<?php echo strtolower($configuration->getFieldConfiguration("list", $name)->getRawValue()->getConfig('type')) ?> sf_admin_form_field_<?php echo $name; $form[$name]->hasError() and print ' errors' ?>">
          <?php echo $form[$name]->renderLabel($configuration->getFieldConfiguration("list", $name)->getRawValue()->getConfig('label'), $form[$name]->hasError() ? array('class' => 'red') : array()) ?>
          <?php echo preg_replace('/\<br \/\>/i', '', $form[$name]->render($form[$name]->hasError() ? array('class' => 'errorbox') : array())) ?>
          <?php if($help = $form[$name]->renderHelp()): ?>
            <span class="smltxt"><?php echo $help ?></span>
          <?php endif ?>
          <?php if($form[$name]->hasError()): ?>
            <span class="smltxt red"><?php echo $form[$name]->renderError() ?></span>
          <?php endif ?>
        </div>
      <?php endforeach ?>
      <p><input type="submit" value="Exporter" class="btn noRemove" /></p>
    </form>
  </div>
</div>
<div style="clear: both;"></div>