<div id="sidebar" class="sidebar">
  <!-- TODO: 放在 Widgets 连同 CSS -->
  <div class="avatar">
    <a href="//feizhaojun.com"></a>
  </div>
  <!-- TODO: 小工具 -->
  <div class="side-box">
    <?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar') ) : // Begin Widgets; displays widgets or default contents below ?>
    <?php endif; // End Widgets ?>
  </div>
  <!-- TODO: -->
  <!-- <?php get_search_form(); ?> -->
  <!-- Search -->
  <!-- <div id="search">
    <h3> -->
      <!-- <label for="s"><?php _e('Blog Search', 'mukti') ?></label> -->
    <!-- </h3>
    <form id="search-form" method="get" action="<?php get_option('home') ?>">
      <div>
        <input id="search-input" name="s" type="text" value="<?php the_search_query() ?>" placeholder="<?php _e('搜索文章') ?>" />
        <input id="search-submit" name="searchsubmit" type="submit" value="<?php _e('搜') ?>" />
      </div>
    </form>
  </div> -->
  <!-- <div id="" class="side-box">
    <ul>
      <?php wp_list_pages('title_li=<h3>'.__('Pages').'</h3>&sort_column=menu_order' ) ?>
    </ul>
  </div> -->
  <!-- <div id="category" class="side-box">
    <h3><?php _e('Categories'); ?></h3>
    <ul>
      <?php wp_list_categories('orderby=name&hierarchical=1&title_li=') ?>
    </ul>
  </div> -->
  <!-- <div id="tag-cloud" class="side-box">
    <h3><?php _e('Tags') ?></h3>
    <p><?php wp_tag_cloud() ?></p>
  </div> -->
  <!-- TODO: -->
  <!-- <div id="meta" class="side-box">
    <h3><?php _e('Meta') ?></h3>
    <ul>
      <?php wp_register() ?>
      <li><?php wp_loginout() ?></li>
    </ul>
    <?php wp_meta() // Do not remove; helps plugins work ?>
  </div> -->
  <!-- TODO: -->
  <!-- <div id="" class="side-box">
    <?php wp_list_bookmarks('title_before=<h3>&title_after=</h3>') ?>
  </div> -->
</div>
