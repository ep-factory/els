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
      $.ajax({
        url: "<?php echo url_for("@fiche_update_filters") ?>",
        type: "post",
        data: "category_id=" + $(this).attr('rel'),
        error: function(){
          alert("Une erreur est survenue.");
        },
        success: function(){
          $(location).attr('href', "<?php echo url_for("@search") ?>");
        }
      });
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
      <?php $fiches = $category->getLimitedFiches($sf_user->getRawValue(), $configuration->getPagerMaxPerPage()/2) ?>
      <?php $count = $category->getLimitedFiches($sf_user->getRawValue(), null, "count") ?>
      <?php if($sf_user->hasGroup('consultant')): ?>
        <p><?php echo __('No result', array(), 'sf_admin') ?> Utilisez la <?php echo link_to('recherche', '@search') ?> pour trouver des fiches.</p>
        <ul class="sf_admin_list_actions">
          <?php echo $helper->linkToNew(array('credential' => array('create'), 'category_id' => $category['id'], 'params' => array(), 'class_suffix' => 'new', 'label' => 'New')) ?>
        </ul>
        <div style="clear: both"></div>
      <?php elseif(!$fiches->count()): ?>
        <p><?php echo __('No result', array(), 'sf_admin') ?></p>
        <ul class="sf_admin_list_actions">
          <?php echo $helper->linkToNew(array('credential' => array('create'), 'category_id' => $category['id'], 'params' => array(), 'class_suffix' => 'new', 'label' => 'New')) ?>
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
                  <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $fiches->count()), $fiches->count(), 'sf_admin') ?>
                  <?php if($count > $fiches->count()): ?>
                    <?php $nb = $count-$fiches->count() ?>
                    <p><strong><a href="#" rel="<?php echo $category->getPrimaryKey() ?>" class="displayMore"><?php echo sprintf('Afficher les %s autres...', $nb) ?></a></strong></p>
                  <?php endif; ?>
                </span>
                <span>
                  <ul class="sf_admin_list_actions">
                    <?php echo $helper->linkToNew(array('credential' => array('create'), 'category_id' => $category['id'], 'params' => array(), 'class_suffix' => 'new', 'label' => 'New')) ?>
                  </ul>
                  <div style="clear: both"></div>
                </span>
              </td>
            </tr>
          </tfoot>
          <tbody>
            <?php foreach($fiches as $i => $fiche): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
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