<ul class="sf_admin_list_actions">
  <?php if($sf_user->hasCredential('create')): ?>
    <?php foreach(CategoryTable::getInstance()->findAll() as $category): ?>
      <?php echo $helper->linkToNew(array('credential' => array('create'), 'category_id' => $category['id'], 'params' => array(), 'class_suffix' => 'new', 'label' => 'Créer une fiche '.$category['code'])) ?>
    <?php endforeach ?>
  <?php endif ?>
</ul>
<div style="clear: both"></div>
