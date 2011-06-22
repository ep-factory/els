<div class="sf_admin_show_row sf_admin_text sf_admin_show_field_elements">
  <label>Elements</label>
  <?php if($fiche->getElements()->count()): ?>
    <div class="elements_list">
      <?php foreach($fiche->getElements() as $element): ?>
        <div class="element">
          <div class="changed">
            <strong>Element changé :</strong>
            <span><?php echo $element->getElementChanged() ? $element->getElementChanged() : "Pas d'élément changé" ?></span>
            <span class="serial"><?php echo $element->getElementChangedSerial() ? $element->getElementChangedSerial() : null ?></span>
          </div>
          <div class="installed">
            <strong>Element installé :</strong>
            <span><?php echo $element->getElementInstalled() ? $element->getElementInstalled() : "Pas d'élément installé" ?></span>
            <span class="serial"><?php echo $element->getElementInstalledSerial() ? $element->getElementInstalledSerial() : null ?></span>
          </div>
        </div>
      <?php endforeach ?>
    </div>
  <?php endif ?>
</div>