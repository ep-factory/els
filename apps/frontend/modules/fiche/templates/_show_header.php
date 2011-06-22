<?php if(!$fiche->isNew()): ?>
  <div class="fiche_infos">
    <h3><?php echo $fiche->getCaseCode()." ".$fiche->getCategory()->getCode() ?></h3>
    <span><?php echo format_date(strtotime($fiche->getFicheDate()), "EEEE dd MMMM yyyy", "fr") ?></span>
  </div>
<?php endif ?>