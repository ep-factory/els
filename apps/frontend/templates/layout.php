<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
  <head>
    <?php include_http_metas() ?>
    <?php include_metas() ?>
    <?php include_title() ?>
    <link rel="shortcut icon" href="/favicon.ico" />
    <?php include_stylesheets() ?>
    <?php include_javascripts() ?>
  </head>
  <body>
    <?php include_partial("global/keyboard") ?>
    <ul>
      <li><?php echo link_to("Fiches", "@fiche") ?></li>
      <li><?php echo link_to("Appareils", "@appareil") ?></li>
      <li><?php echo link_to("Ateliers", "@atelier") ?></li>
      <li><?php echo link_to("Bâtiments", "@batiment") ?></li>
      <li><?php echo link_to("Codes affaire", "@case_code") ?></li>
      <li><?php echo link_to("Catégories", "@category") ?></li>
      <li><?php echo link_to("Demandeurs", "@demandeur") ?></li>
      <li><?php echo link_to("Eléments", "@element") ?></li>
      <li><?php echo link_to("Postes", "@poste") ?></li>
    </ul>
    <?php echo $sf_content ?>
  </body>
</html>