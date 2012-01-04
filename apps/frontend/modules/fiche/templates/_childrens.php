<div class="sf_admin_show_row sf_admin_text sf_admin_show_field_Childrens">
  <strong>Filles</strong>
  <?php if($fiche->getChildrens()->count()): ?>
    <span>
      <ul>
        <?php foreach($fiche->getChildrens() as $children): ?>
          <li><?php echo link_to($children, "fiche_show", $children, array('target' => '_self')) ?></li>
        <?php endforeach ?>
      </ul>
    </span>
  <?php else: ?>
    <span>Aucune fille</span>
  <?php endif ?>
</div>