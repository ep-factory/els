<div class="fiche_infos">
  <h3><?php echo $fiche->getCaseCode()." ".$fiche->getCategory()->getCode() ?></h3>
  <span><?php echo $fiche->getFicheDate() ? format_date(strtotime($fiche->getFicheDate()), "EEEE dd MMMM yyyy", "fr") : null ?></span>
  <?php if($fiche->getIsResolved()): ?>
    <p><?php echo $fiche->getResolvedAuthor() ?> a fermé(e) cette fiche le <?php echo format_date(strtotime($fiche->getResolvedDate()), "EEEE dd MMMM yyyy", "fr") ?> à <?php echo date('H\hi', strtotime($fiche->getResolvedDate())) ?></p>
  <?php endif ?>
  <?php if($fiche->getIsFinished()): ?>
    <p><?php echo $fiche->getFinishedAuthor() ?> a clos cette fiche le <?php echo format_date(strtotime($fiche->getFinishedDate()), "EEEE dd MMMM yyyy", "fr") ?> à <?php echo date('H\hi', strtotime($fiche->getFinishedDate())) ?></p>
  <?php endif ?>
</div>