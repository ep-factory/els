<ul class="sf_admin_actions">
  <?php echo !$sf_request->isXmlHttpRequest() ? $helper->linkToList(array('params' => array(), 'class_suffix' => 'list', 'label' => 'Back to list')) : null ?>
  <?php echo $helper->linkToEdit($fiche, array('credential' => array('edit'), 'params' => array(), 'class_suffix' => 'edit', 'label' => 'Edit')) ?>
  <?php if(!$fiche->getIsResolved() && $sf_user->hasCredential('resolve')): ?>
    <li class="sf_admin_action_resolve">
      <?php if (method_exists($helper, 'linkToResolve')): ?>
        <?php echo $helper->linkToResolve($fiche, array('label' => 'Résoudre', 'action' => 'resolve', 'credential' => array('resolve'), 'params' => array(), 'class_suffix' => 'resolve')) ?>
      <?php else: ?>
        <?php echo link_to(__('Résoudre', array(), 'messages'), 'fiche/resolve?id='.$fiche->getId(), array()) ?>
      <?php endif; ?>
    </li>
  <?php endif ?>
  <?php if(!$fiche->getIsFinished() && $sf_user->hasCredential('close')): ?>
    <li class="sf_admin_action_close">
      <?php if (method_exists($helper, 'linkToClose')): ?>
        <?php echo $helper->linkToClose($fiche, array('label' => 'Clore', 'action' => 'close', 'credential' => array('close'), 'params' => array(), 'class_suffix' => 'close')) ?>
      <?php else: ?>
        <?php echo link_to(__('Clore', array(), 'messages'), 'fiche/close?id='.$fiche->getId(), array()) ?>
      <?php endif; ?>
    </li>
  <?php endif ?>
</ul>
<div class="clear"></div>