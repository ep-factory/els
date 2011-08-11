<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<div class="headings">
  <h2>Filtres</h2>
</div>
<?php if ($form->hasGlobalErrors()): ?>
  <?php echo $form->renderGlobalErrors() ?>
<?php endif; ?>
<div class="contentbox">
  <form action="<?php echo url_for('fiche_collection', array('action' => 'filter')) ?>" method="post">
    <?php echo $form->renderHiddenFields() ?>
    <?php foreach ($configuration->getFormFilterFields($form) as $name => $field): ?>
    <?php if ((isset($form[$name]) && $form[$name]->isHidden()) || (!isset($form[$name]) && $field->isReal())) continue ?>
      <?php include_partial('fiche/filters_field', array(
        'name'       => $name,
        'attributes' => $field->getConfig('attributes', array()),
        'label'      => $field->getConfig('label'),
        'help'       => $field->getConfig('help'),
        'form'       => $form,
        'field'      => $field,
        'class'      => 'sf_admin_form_row sf_admin_'.strtolower($field->getType()).' sf_admin_filter_field_'.$name,
      )) ?>
    <?php endforeach; ?>
    <?php echo link_to(__('Reset', array(), 'sf_admin'), 'fiche_collection', array('action' => 'filter'), array('query_string' => '_reset', 'method' => 'post')) ?>
    <input type="submit" value="<?php echo __('Filter', array(), 'sf_admin') ?>" class="btn" />
    <img src="/sfAdminTemplatePlugin/images/ajax-loader.gif" alt="Chargement..." class="loading" style="display: none;" />
  </form>
</div>
