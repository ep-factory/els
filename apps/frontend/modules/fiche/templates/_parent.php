<?php if($fiche->hasParent()): ?>
  <div class="sf_admin_show_row sf_admin_text sf_admin_show_field_parent">
    <strong>Parent</strong>
    <span><?php echo link_to($fiche->getParent(), "fiche_show", $fiche->getParent(), array('target' => '_self')) ?></span>
  </div>
<?php endif ?>