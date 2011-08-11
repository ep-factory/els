<script type="text/javascript">
  $(document).ready(function(){
    // Object actions
    $('.sf_admin_row_object_actions a.fancybox').fancybox({
      overlayColor: "#000",
      showCloseButton: false,
      padding: 0,
      margin: 0
    });
    $('.sf_admin_row').live('click', function(){
      $(this).next('.sf_admin_row_object_actions').find('a.fancybox').click();
    });
    $('.displayMore').live('click', function(event){
      event.preventDefault();
      $($(this).attr('href')).find('form').submit();
    });
  });
</script>
<?php use_helper('I18N', 'Date') ?>
<?php $sf_response->addMeta('title', 'Administration | '.__('Fiches', array(), 'messages')) ?>
<?php slot('breadcrumb', array(array('url' => '@fiche', 'label' => __('Fiches', array(), 'messages')))) ?>
<?php include_partial('fiche/assets') ?>
<?php include_partial('fiche/flashes') ?>
<div class="contentcontainer med left">
  <?php foreach($categories as $key => $category): ?>
    <?php echo $key ? "<br />" : null ?>
    <div class="headings">
      <h2><?php echo $category ?></h2>
    </div>
    <div class="contentbox">
      <?php $count = $category->getLimitedFiches($configuration->getPagerMaxPerPage()/2, $sf_user->hasGroup('technicien') ? false : null, $sf_user->hasGroup('coordinateur') ? false : null)->count() ?>
      <?php if($sf_user->hasGroup('consultant')): ?>
        <p><?php echo __('No result', array(), 'sf_admin') ?> Utilisez la <?php echo link_to('recherche', '@search') ?> pour trouver des fiches.</p>
        <ul class="sf_admin_list_actions">
          <?php if($sf_user->hasCredential('create')): ?>
            <?php echo $helper->linkToNew(array('credential' => array('create'), 'category_id' => $category['id'], 'params' => array(), 'class_suffix' => 'new', 'label' => 'New')) ?>
          <?php endif ?>
        </ul>
        <div style="clear: both"></div>
      <?php elseif(!$count): ?>
        <p><?php echo __('No result', array(), 'sf_admin') ?></p>
        <ul class="sf_admin_list_actions">
          <?php if($sf_user->hasCredential('create')): ?>
            <?php echo $helper->linkToNew(array('credential' => array('create'), 'category_id' => $category['id'], 'params' => array(), 'class_suffix' => 'new', 'label' => 'New')) ?>
          <?php endif ?>
        </ul>
        <div style="clear: both"></div>
      <?php else: ?>
        <table width="100%">
          <thead>
            <tr>
              <?php include_partial('fiche/list_th_tabular', array('sort' => array('fiche_date', 'desc'))) ?>
            </tr>
          </thead>
          <tfoot>
            <tr>
              <td colspan="5">
                <span>
                  <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $count), $count, 'sf_admin') ?>
                  <?php if($category->getCountFiches($sf_user->hasGroup('technicien') ? false : null, $sf_user->hasGroup('coordinateur') ? false : null) > $count): ?>
                    <?php $nb = $category->getCountFiches($sf_user->hasGroup('technicien') ? false : null, $sf_user->hasGroup('coordinateur') ? false : null)-$count ?>
                    <div style="display: none;" id="filter<?php echo $category->getPrimaryKey() ?>">
                      <?php $filters->bind($configuration->getCategoryFilters($filters, $category->getRawValue())->getRawValue()) ?>
                      <?php include_partial('fiche/filters', array('form' => $filters, 'configuration' => $configuration, $sf_user->hasGroup('technicien') ? false : null)) ?>
                    </div>
                    <p><strong><a href="#filter<?php echo $category->getPrimaryKey() ?>" class="displayMore"><?php echo sprintf('Afficher les %s autres...', $nb) ?></a></strong></p>
                  <?php endif; ?>
                </span>
                <span>
                  <ul class="sf_admin_list_actions">
                    <?php if($sf_user->hasCredential('create')): ?>
                      <?php echo $helper->linkToNew(array('credential' => array('create'), 'category_id' => $category['id'], 'params' => array(), 'class_suffix' => 'new', 'label' => 'New')) ?>
                    <?php endif ?>
                  </ul>
                  <div style="clear: both"></div>
                </span>
              </td>
            </tr>
          </tfoot>
          <tbody>
            <?php foreach($category->getLimitedFiches($configuration->getPagerMaxPerPage()/2, $sf_user->hasGroup('technicien') ? false : null, $sf_user->hasGroup('coordinateur') ? false : null) as $i => $fiche): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
              <tr class="sf_admin_row <?php echo $odd ?>">
                <?php include_partial('fiche/list_td_tabular', array('fiche' => $fiche)) ?>
              </tr>
              <tr class="sf_admin_row_object_actions">
                <?php include_partial('fiche/list_td_actions', array('fiche' => $fiche, 'helper' => $helper, 'i' => $key."_".$i)) ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif ?>
    </div>
  <?php endforeach ?>
</div>
<div style="clear: both;"></div>