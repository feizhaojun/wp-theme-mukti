<?php get_header(); ?>

<div class="list">
  <h2>
    <?php
      // 标题设置条件
      // Conditional Tags 判断页面类型
      switch (true) {
        case is_home():
        case is_front_page():
        case is_admin():
        case is_search():
        case is_404():
          break;
        case is_single():
        case is_attachment():
        case is_page():
        case is_singular():
          the_title();
          break;
        case is_category():
          echo _e('Categories') . ': ';
          single_cat_title();
          break;
        case is_date():
        case is_year():
        case is_month():
        case is_day():
        case is_time():
        case is_new_day():
          echo _e('Blog Archives');
          break;
        case is_tag():
          echo _e('Tags') . ': ';
          single_tag_title();
          break;
          break;
        case is_tax():
          break;
        case is_author():
          echo '来自 ' . get_the_author() . ' 的文章';
          break;
        case is_archive():
          break;
        case is_privacy_policy():
          break;
        case is_feed():
          break;
        case is_trackback():
          break;
        case is_preview():
          break;
        default:
          // 
      }
    ?>
  </h2>
  <?php get_template_part('templates/list',''); ?>
</div>

<?php get_footer(); ?>

<?php
/*
  // wp_reset_postdata()
  // wp_reset_query()
  // rewind_posts()

  // rewind_posts();
  // while ( have_posts() ) : the_post();
  //   the_title();
  // endwhile;

  // wp_reset_postdata();
  // $secondary_query = new WP_Query( 'category_name=example-category' );
  // if ( $secondary_query->have_posts() )
  //   while ( $secondary_query->have_posts() ) : $secondary_query->the_post();
  //     the_title();
  //   endwhile;
  // endif;
  // wp_reset_postdata();

  // $the_query = new WP_Query( array( 'posts_per_page' => 3 ) );

  is_home() – Returns true if the current page is the homepage
  is_admin() – Returns true if inside Administration Screen, false otherwise
  is_single() – Returns true if the page is currently displaying a single post
  is_page() – Returns true if the page is currently displaying a single page
  is_page_template() – Can be used to determine if a page is using a specific template, for example: is_page_template('about-page.php')
  is_category() – Returns true if page or post has the specified category, for example: is_category('news')
  is_tag() – Returns true if a page or post has the specified tag
  is_author() – Returns true if inside author’s archive page
  is_search() – Returns true if the current page is a search results page
  is_404() – Returns true if the current page does not exist
  has_excerpt() – Returns true if the post or page has an excerpt

  is_front_page()
  is_paged()

  _wp_specialchars

  get_template_part('slug','template');
  bloginfo('stylesheet_url');
  esc_url( get_stylesheet_uri() );
  bloginfo('template_directory');
  echo get_theme_file_uri("images/demo.svg");
  echo get_theme_file_path("images/demo.svg");
  echo get_permalink();
  get_parent_theme_file_uri();
  get_parent_theme_file_path();
*/ ?>

<!-- TODO: 循环中只有第一个显示 -->
<!-- <div>时间：<?php the_date('Y-m-d'); ?></div> -->