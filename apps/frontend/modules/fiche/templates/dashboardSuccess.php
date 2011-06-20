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
    // Batch actions
    if($('.contentbox tfoot select[name=batch_action] option').length <= 1) {
      $('.contentbox tfoot select[name=batch_action], .contentbox tfoot input:submit, .contentbox tfoot input:hidden').remove();
    }
  });
</script>
<?php use_helper('I18N', 'Date') ?>
<?php $sf_response->addMeta('title', 'Administration | '.__('Fiches', array(), 'messages')) ?>
<?php include_partial('fiche/assets') ?>
<?php include_partial('fiche/flashes') ?>
<div class="contentcontainer med left">
  <?php foreach($categories as $key => $category): ?>
    <?php echo $key ? "<br />" : null ?>
    <?php $helper->setCategoryId($category['id']) ?>
    <div class="headings">
      <h2><?php echo $category ?></h2>
    </div>
    <div class="contentbox">
      <?php if(!$category->getLimitedFiches($configuration->getPagerMaxPerPage()/2)->count()): ?>
        <p><?php echo __('No result', array(), 'sf_admin') ?></p>
        <?php include_partial('fiche/list_actions', array('helper' => $helper)) ?>
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
                  <?php echo format_number_choice('[0] no result|[1] 1 result|(1,+Inf] %1% results', array('%1%' => $category->getCountFiches()), $category->getCountFiches(), 'sf_admin') ?>
                  <?php if($category->getCountFiches() > $category->getLimitedFiches($configuration->getPagerMaxPerPage()/2)->count()): ?>
                    <?php echo link_to(sprintf('Afficher les %s autres...', $category->getCountFiches()-$category->getLimitedFiches($configuration->getPagerMaxPerPage()/2)->count()), '@fiche?page=2') ?>
                  <?php endif; ?>
                </span>
                <span>
                  <?php include_partial('fiche/list_actions', array('helper' => $helper)) ?>
                </span>
                <div style="clear: both;"></div>
              </td>
            </tr>
          </tfoot>
          <tbody>
            <?php foreach($category->getLimitedFiches($configuration->getPagerMaxPerPage()/2) as $i => $fiche): $odd = fmod(++$i, 2) ? 'odd' : 'even' ?>
              <tr class="sf_admin_row <?php echo $odd ?>">
                <?php include_partial('fiche/list_td_tabular', array('fiche' => $fiche)) ?>
              </tr>
              <tr class="sf_admin_row_object_actions">
                <?php include_partial('fiche/list_td_actions', array('fiche' => $fiche, 'helper' => $helper, 'i' => $i)) ?>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif ?>
    </div>
  <?php endforeach ?>
</div>
<div style="clear: both;"></div>