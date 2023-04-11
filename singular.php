<?php get_header(); ?>
<div id="main" class="main">
  <!-- TODO: barthelme_post_class -->
  <div id="post-<?php the_ID(); ?>" class="entry <?php barthelme_post_class(); ?>">
    <!-- TODO: the_post -->
    <?php echo the_post(); ?>
    
    <h2 class="entry-title"><?php the_title(); ?></h2>

    <div class="entry-meta" style="text-align:left;">
      <?php if ( is_single() && !is_attachment() ):
        printf(__('<div>分类: %1$s　　标签：%2$s</div>', 'mukti'),
          get_the_category_list(', '),
          get_the_tag_list('', ', ') );
      endif; ?>
      <?php printf(__('<div>作者: %1$s　　时间：%2$s %3$s</div>', 'mukti'),
        barthelme_author_link(),
        get_the_time('Y-m-d'),
        get_the_time('H:i:s (l)') ); ?>
    </div>
    <!-- TODO: -->
    <div class="entry-content">
      <?php the_content('<span class="more-link">'.__('Continue reading &rsaquo;', 'mukti').'</span>'); ?>
      <!-- TODO: WordPress模板标签wp_link_pages用于输出文章内容翻页，需要在文章内容中插入<!–nextpage–>标签。 -->
      <!-- <?php wp_link_pages(); ?> -->
      <?php wp_link_pages( array(
        'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:' ) . '</span>',
        'after'       => '</div>',
        'link_before' => '<span>',
        'link_after'  => '</span>',
        ) ); ?>
      <?php edit_post_link(__('Edit'),'<p>','</p>'); ?>
    </div>
    <!-- TODO: 改为可配置 -->
    <div class="entry-donate">
      <p>您的赞助将会支持作者创作及本站运维</p>
      <div class="donate-item">
        <p><img src="<?php echo get_theme_file_uri("/assets/images/wxpay-qrcode.png"); ?>" /></p>
      </div>
      <div class="donate-item">
        <p><img src="<?php echo get_theme_file_uri("/assets/images/alipay-qrcode.png"); ?>" /></p>
      </div>
    </div>
    <div class="navigation">
      <div class="nav-prev"><?php previous_post_link(__('&laquo; 上一篇：%link')) ?></div>
      <div class="nav-next"><?php next_post_link(__('下一篇：%link &raquo;')) ?></div>
    </div>
    <div class="entry-meta" style="padding: 7px 0 0;">
      <!-- TODO: -->
      <!-- <?php printf(__('<div>Follow any responses to this post with its <a href="%1$s" title="Comments RSS to %2$s" rel="alternate" type="application/rss+xml">comments RSS</a> feed.</div>', 'mukti'),
        esc_url( get_post_comments_feed_link() ),
        _wp_specialchars(get_the_title(), 'double') ); ?>

      <?php if (('open' == $post-> comment_status) && ('open' == $post->ping_status)) : ?>
        <span class="entry-interact"><?php printf(__('You can <a href="#respond" title="Post a comment">post a comment</a> or <a href="%s" rel="trackback" title="Trackback URL for your post">trackback</a> from your blog.', 'mukti'), get_trackback_url()) ?></span>
      <?php elseif (!('open' == $post-> comment_status) && ('open' == $post->ping_status)) : ?>
        <span class="entry-interact"><?php printf(__('Comments are closed, but you can <a href="%s" rel="trackback" title="Trackback URL for your post">trackback</a> from your blog.', 'mukti'), get_trackback_url()) ?></span>
      <?php elseif (('open' == $post-> comment_status) && !('open' == $post->ping_status)) : ?>
        <span class="entry-interact"><?php printf(__('You can <a href="#respond" title="Post a comment">post a comment</a>, but trackbacks are closed.', 'mukti')) ?></span>
      <?php elseif (!('open' == $post-> comment_status) && !('open' == $post->ping_status)) : ?>
        <span class="entry-interact"><?php _e('Both comments and trackbacks are currently closed.', 'mukti') ?></span>
      <?php endif; ?> -->
      <!-- 广告位 -->
      <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-4343772002824313" crossorigin="anonymous"></script>
      <!-- 列表和内容页横幅 -->
      <ins class="adsbygoogle"
          style="display:inline-block;width:720px;height:90px"
          data-ad-client="ca-pub-4343772002824313"
          data-ad-slot="4353532533"></ins>
      <script>
          (adsbygoogle = window.adsbygoogle || []).push({});
      </script>
    </div>
    <?php // If comments are open or we have at least one comment, load up the comment template.
      if ( comments_open() || get_comments_number() ) :
        comments_template();
      endif;
    ?>
  </div>
  <div class="index"></div>
</div>

<?php while ( have_posts() ) : the_post();
  /*
  TODO:
  * Include the post format-specific template for the content. If you want to
  * use this in a child theme, then include a file called called content-___.php
  * (where ___ is the post format) and that will be used instead.
  */
  get_template_part( 'content', get_post_format() );
endwhile; ?>
 
<?php get_footer(); ?>