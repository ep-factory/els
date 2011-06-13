<?php use_helper('I18N', 'Date') ?>
<?php include_partial('fiche/assets') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Fiches', array(), 'messages') ?></h1>
  <?php include_partial('fiche/flashes') ?>
  <div id="sf_admin_content">
    <?php foreach($categories as $category): ?>
      <div class="sf_admin_list">
        <h2><?php echo $category ?></h2>
        <?php if(!$category->getLimitedFiches($configuration->getPagerMaxPerPage()/2)->count()): ?>
          <p><?php echo __('No result', array(), 'sf_admin') ?></p>
        <?php else: ?>
          <table cellspacing="0">
            <thead>
              <tr>
                <?php include_partial('fiche/list_th_tabular', array('sort' => array('fiche_date', 'desc'))) ?>
              </tr>
            </thead>
            <tfoot>
              <tr>
                <th colspan="5">
                  <?php if($category->getCountFiches() > $category->getLimitedFiches($configuration->getPagerMaxPerPage()/2)->count()): ?>
                    <span><?php echo link_to(sprintf('Afficher les %s autres...', $category->getCountFiches()-$category->getLimitedFiches($configuration->getPagerMaxPerPage()/2)->count()), '@fiche?page=2') ?></span>
                  <?php endif; ?>
                  <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $category->getCountFiches()), $category->getCountFiches(), 'sf_admin') ?>
                </th>
              </tr>
            </tfoot>
            <tbody>
              <?php foreach($category->getLimitedFiches($configuration->getPagerMaxPerPage()/2) as $i => $fiche): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
                <tr class="sf_admin_row <?php echo $odd ?>">
                  <?php include_partial('fiche/list_td_tabular', array('fiche' => $fiche)) ?>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        <?php endif; ?>
      </div>
    <?php endforeach ?>
  </div>
</div>
