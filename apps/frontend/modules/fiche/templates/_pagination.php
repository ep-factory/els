<ul class="pagination">
  <li>
    <a href="<?php echo url_for('@search') ?>?page=1">
      <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/first.png', array('alt' => __('First page', array(), 'sf_admin'), 'title' => __('First page', array(), 'sf_admin'))) ?>
    </a>
  </li>
  
  <li>
    <a href="<?php echo url_for('@search') ?>?page=<?php echo $pager->getPreviousPage() ?>">
      <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/previous.png', array('alt' => __('Previous page', array(), 'sf_admin'), 'title' => __('Previous page', array(), 'sf_admin'))) ?>
    </a>
  </li>

  <?php foreach ($pager->getLinks() as $page): ?>
    <?php if ($page == $pager->getPage()): ?>
    <li class="page">
      <a href="<?php echo url_for('@search') ?>?page=<?php echo $page ?>">
        <?php echo $page ?>
      </a>
    </li>
    <?php else: ?>
      <li>
        <a href="<?php echo url_for('@search') ?>?page=<?php echo $page ?>">
          <?php echo $page ?>
        </a>
      </li>
    <?php endif; ?>
  <?php endforeach; ?>

  <li>
    <a href="<?php echo url_for('@search') ?>?page=<?php echo $pager->getNextPage() ?>">
      <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/next.png', array('alt' => __('Next page', array(), 'sf_admin'), 'title' => __('Next page', array(), 'sf_admin'))) ?>
    </a>
  </li>

  <li>
    <a href="<?php echo url_for('@search') ?>?page=<?php echo $pager->getLastPage() ?>">
      <?php echo image_tag(sfConfig::get('sf_admin_module_web_dir').'/images/last.png', array('alt' => __('Last page', array(), 'sf_admin'), 'title' => __('Last page', array(), 'sf_admin'))) ?>
    </a>
  </li>
</ul>