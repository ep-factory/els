<form action="<?php echo url_for('@sf_guard_signin') ?>" method="post">
  <?php echo $form->renderHiddenFields() ?>
  <p><?php echo $form['username']->renderLabel() ?></p>
  <?php echo $form['username']->render() ?>
  <p><?php echo $form['password']->renderLabel() ?></p>
  <?php echo $form['password']->render() ?>
  <input type="submit" class="loginbtn noTransform" value="Se connecter" />
  <?php $routes = $sf_context->getRouting()->getRoutes() ?>
  <?php if (isset($routes['sf_guard_forgot_password'])): ?>
    <br /><p><a href="<?php echo url_for('@sf_guard_forgot_password') ?>" title="Mot de passe oublié ?">Mot de passe oublié ?</a></p>
  <?php endif; ?>
</form>