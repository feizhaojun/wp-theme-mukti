<!-- Mukti -->
<!DOCTYPE html>
<html <?php language_attributes() ?>>
<head>
  <meta http-equiv="content-type" content="<?php bloginfo('html_type') ?>; charset=<?php bloginfo('charset') ?>" />
  <!-- <title><?php wp_title( '|', true, 'right' ); ?></title> -->
  <!-- <title><?php bloginfo( 'name' ); ?></title> -->
  <!-- TODO: _e -->
  <title>
    <?php
      // 标题设置条件
      // Conditional Tags 判断页面类型
      switch (true) {
        case is_home():
          bloginfo('name');
          echo ' - ';
          bloginfo('description');
          break;
        case is_front_page():
        case is_admin():
        case is_search():
          echo _wp_specialchars(stripslashes($_GET['s']), true) . ' - ';
          echo _e('Search') . ' - ';
          bloginfo('name');
          break;
        case is_404():
          _e('Page not found');
          echo ' - ';
          bloginfo('name');
          break;
        case is_single():
        case is_attachment():
        case is_page():
        case is_singular():
          the_title();
          echo ' - by ';
          bloginfo('name');
          break;
        case is_category():
          single_cat_title();
          echo ' - ';
          echo _e('Categories') . ' - ';
          bloginfo('name');
          break;
        case is_date():
        case is_year():
        case is_month():
        case is_day():
        case is_time():
        case is_new_day():
          echo _e('Blog Archives');
          echo ' - ';
          bloginfo('name');
          break;
        case is_tag():
          single_tag_title();
          echo ' - ';
          echo _e('Tags') . ' - ';
          echo ' - ';
          bloginfo('name');
          break;
        // case is_tax():
        //   break;
        case is_author():
          echo '来自 ' . get_the_author() . ' 的文章';
          echo ' - ';
          bloginfo('name');
          break;
        // case is_archive():
        //   break;
        // case is_privacy_policy():
        //   break;
        // case is_feed():
        //   break;
        // case is_trackback():
        //   break;
        // case is_preview():
        //   break;
        default:
          the_title();
          echo ' - ';
          bloginfo('name');
      }
    ?>
  </title>
  <!-- TODO: -->
  <meta name="keywords" content="<?php the_title() ?>" />
  <meta name="description" content="<?php the_title() ?>,<?php bloginfo('name') ?>,<?php bloginfo('description') ?>" />
  <!-- Pingback & RSS TODO: _e -->
  <link rel="pingback" href="<?php bloginfo('pingback_url') ?>" />
  <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('rss2_url') ?>" title="<?php bloginfo('name') ?> <?php _e('RSS feed' ) ?>" />
  <link rel="alternate" type="application/rss+xml" href="<?php bloginfo('comments_rss2_url') ?>" title="<?php bloginfo('name') ?> <?php _e( 'comments RSS feed' ) ?>" />
  <!-- CSS -->
  <link rel="stylesheet" type="text/css" media="screen,projection" href="<?php bloginfo('stylesheet_url'); ?>?20230406" title="Mukti" />
  <!-- <link rel="stylesheet" type="text/css" href="<?php echo esc_url( get_stylesheet_uri() ); ?>" /> -->
  <link rel="stylesheet" type="text/css" media="print" href="<?php bloginfo('template_directory'); ?>/print.css" />
  <!-- 落鹜文楷字体 -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/lxgw-wenkai-webfont@1.1.0/style.css" />
  <!-- JavaScript -->
  <script src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
  <?php wp_head() // Do not remove; helps plugins work ?>
  <!-- Google Adsense Start -->
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4343772002824313" crossorigin="anonymous"></script>
  <!-- Google Adsense End -->
</head>
<body class="<?php barthelme_body_class() ?>">
	<div id="header">
		<h1 id="blog-title"><a href="<?php echo get_option('home') ?>/" title="<?php bloginfo('name') ?>"><?php bloginfo('name') ?></a></h1>
	</div>
  <div id="container">
    <div id="content">

<!-- TODO: -->
	<!-- <div class="access">
		<span class="content-access">
			<a href="#content" title="<?php _e('Skip to content'); ?>">
				<?php _e('Skip to content'); ?>
			</a>
		</span>
	</div> -->

<?php
// TODO:
	// barthelme_globalnav(); // Adds list of pages just below header
?>

<?php
// TODO:
	// wp_nav_menu(
	// 	array(
	// 		'theme_location'=>'right-menu',
  //       	'menu_class'=>'right-menu',
  //       	'menu_id'=>'right-menu',
  //       	'container'=>'ul'
  //       )
	// );
?>