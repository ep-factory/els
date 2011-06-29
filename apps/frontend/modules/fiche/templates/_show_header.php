<?php if(!$fiche->isNew()): ?>
  <div class="fiche_infos">
    <h3><?php echo $fiche->getCaseCode()." ".$fiche->getCategory()->getCode() ?></h3>
    <span><?php echo $fiche->getFicheDate() ? format_date(strtotime($fiche->getFicheDate()), "EEEE dd MMMM yyyy", "fr") : null ?></span>
  </div>
<?php endif ?>