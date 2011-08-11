<?php use_stylesheets_for_form($form) ?>
<?php use_javascripts_for_form($form) ?>

<?php if($form->getObject()->hasParent()): ?>
<p>Fiche étendue de <?php echo $form->getObject()->getParent() ?></p><br />
<?php endif ?>

<div class="sf_admin_form">
  <?php echo form_tag_for($form, '@fiche') ?>
    <?php if($sf_request->hasParameter('category_id')): ?>
      <input type="hidden" name="category_id" value="<?php echo $sf_request->getParameter("category_id") ?>" />
    <?php elseif($form->getObject()->hasParent()): ?>
      <input type="hidden" name="parent_id" value="<?php echo $form->getObject()->getParentId() ?>" />
      <input type="hidden" name="category_id" value="<?php echo $form->getObject()->getParent()->getCategoryId() ?>" />
    <?php endif ?>
    <?php echo $form->renderHiddenFields(false) ?>

    <?php if($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <?php foreach ($configuration->getFormFields($form, $form->isNew() ? 'new' : 'edit') as $fieldset => $fields): ?>
      <?php include_partial('fiche/form_fieldset', array('fiche' => $fiche, 'form' => $form, 'fields' => $fields, 'fieldset' => $fieldset)) ?>
    <?php endforeach; ?>

    <?php include_partial('fiche/form_actions', array('fiche' => $fiche, 'form' => $form, 'configuration' => $configuration, 'helper' => $helper)) ?>
  </form>
</div>
