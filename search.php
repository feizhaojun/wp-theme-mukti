<?php get_header() ?>

<?php if (have_posts()) : // 搜索有结果 ?>
  <!-- TODO: 标题 -->
  <h2 class="list-title">
    <?php printf(__('&#8220; %1$s &#8221;', 'mukti'), _wp_specialchars(stripslashes($_GET['s']), true) ); ?>
    <?php _e('Search Results', 'mukti') ?>
  </h2>
  <div class="list">
    <?php get_template_part('templates/list',''); ?>
  </div>
<?php else : // 搜索没有结果 ?>
  <div id="main" class="main">
    <div id="post-0" class="entry">
      <h2>
        <?php printf( __( 'Search Results for: %s', 'mukti' ), get_search_query() ); ?>
      </h2>
      <!-- TODO: -->
      <div class="entry-content" style="font-family: PingfangSC-light, sans-serif;font-size: 14px;">
        <p><?php _e('Sorry, but nothing matched your search criteria. Please try again with some different keywords.', 'mukti') ?></p>
        <p><?php _e('抱歉，没有您要找的内容。请试试用其他关键词搜索。', 'mukti') ?></p>
      </div>
      <?php get_template_part('templates/search',''); ?>
    </div>
  </div>
<?php endif; ?>

<?php get_footer() ?>