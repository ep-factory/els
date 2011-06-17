<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
    <!--[if IE 6]>
      <script type='text/javascript' src='/sfAdminTemplatePlugin/js/png_fix.js'></script>
      <script type='text/javascript'>
        DD_belatedPNG.fix('img, .notifycount, .selected');
      </script>
    <![endif]-->
  </head>
  <body>
    <div id="logincontainer">
      <div id="loginbox">
        <div id="loginheader">
          <?php echo get_slot('title', image_tag(public_path('/sfAdminTemplatePlugin/images/cp_logo.png'), array('title' => 'Control Panel', 'title' => 'Control Panel'))) ?>
        </div>
        <?php include_partial('global/header') ?>
        <div id="innerlogin">
          <!-- Start notice -->
          <?php if($sf_user->hasFlash('success')): ?>
            <div class="notify success"><span>Succès !</span><?php echo __($sf_user->getFlash('success'), null, 'sf_admin') ?></div>
          <?php endif ?>
          <?php if($sf_user->hasFlash('notice')): ?>
            <div class="notify success"><span>Succès !</span><?php echo __($sf_user->getFlash('notice'), null, 'sf_admin') ?></div>
          <?php endif ?>
          <?php if($sf_user->hasFlash('error')): ?>
            <div class="notify error"><span>Erreur !</span><?php echo __($sf_user->getFlash('error'), null, 'sf_admin') ?></div>
          <?php endif ?>
          <?php if($sf_user->hasFlash('info')): ?>
            <div class="notify info"><span>Information !</span><?php echo __($sf_user->getFlash('error'), null, 'sf_admin') ?></div>
          <?php endif ?>
          <!-- End notice -->
          <?php echo $sf_content ?>
        </div>
      </div>
      <?php echo image_tag(public_path('/sfAdminTemplatePlugin/images/login_fade.png'), array('title' => 'Fade', 'title' => 'Fade')) ?>
    </div>
  </body>
</html>