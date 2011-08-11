<ul class="sf_admin_actions">
  <?php echo !$sf_request->isXmlHttpRequest() ? $helper->linkToList(array('params' => array(), 'class_suffix' => 'list', 'label' => 'Back to list')) : null ?>
  <?php echo $helper->linkToEdit($fiche, array('credential' => array('edit'), 'params' => array(), 'class_suffix' => 'edit', 'label' => 'Edit')) ?>
  <?php if($sf_user->hasCredential('resolve')): ?>
    <?php if(!$fiche->getIsResolved()): ?>
      <li class="sf_admin_action_resolve">
        <?php if (method_exists($helper, 'linkToResolve')): ?>
          <?php echo $helper->linkToResolve($fiche, array('label' => 'Fermer fiche', 'action' => 'resolve', 'credential' => array('resolve'), 'params' => array(), 'class_suffix' => 'resolve')) ?>
        <?php else: ?>
          <?php echo link_to(__('Fermer fiche', array(), 'messages'), 'fiche/resolve?id='.$fiche->getId(), array()) ?>
        <?php endif; ?>
      </li>
    <?php elseif($fiche->getSfGuardUserId() == $sf_user->getGuardUser()->getId()): ?>
      <li class="sf_admin_action_unresolve">
        <?php if (method_exists($helper, 'linkToUnresolve')): ?>
          <?php echo $helper->linkToUnresolve($fiche, array('label' => 'Rouvrir fiche', 'action' => 'unresolve', 'credential' => array('resolve'), 'params' => array(), 'class_suffix' => 'unresolve')) ?>
        <?php else: ?>
          <?php echo link_to(__('Rouvrir fiche', array(), 'messages'), 'fiche/unresolve?id='.$fiche->getId(), array()) ?>
        <?php endif; ?>
      </li>
    <?php endif ?>
  <?php endif ?>
  <?php if(!$fiche->getIsFinished() && $sf_user->hasCredential('close')): ?>
    <li class="sf_admin_action_close">
      <?php if (method_exists($helper, 'linkToClose')): ?>
        <?php echo $helper->linkToClose($fiche, array('label' => 'Clore', 'action' => 'close', 'credential' => array('close'), 'params' => array(), 'class_suffix' => 'close')) ?>
      <?php elseif($sf_user->hasGroup('coordinateur') && !$fiche->getAppareilId()): ?>
        <?php use_helper('JavascriptBase') ?>
        <?php echo link_to_function(__('Clore', array(), 'messages'), "alert('Vous devez associer un appareil à cette fiche.');") ?>
      <?php else: ?>
        <?php echo link_to(__('Clore', array(), 'messages'), 'fiche/close?id='.$fiche->getId(), array()) ?>
      <?php endif; ?>
    </li>
  <?php endif ?>
</ul>
<div class="clear"></div>