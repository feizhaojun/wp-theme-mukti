<!-- <div class="list"> -->
  <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
      <div id="post-<?php the_ID() ?>" class="list-item <?php barthelme_post_class() ?>">
        <!-- TODO: -->
        <?php the_post_thumbnail(); ?>
        <h3><a href="<?php the_permalink() ?>" title="<?php echo _wp_specialchars(get_the_title()); ?>" rel="bookmark"><?php the_title() ?></a></h3>
        <!-- <div class="list-excerpt"><?php the_excerpt(); ?></div> -->
        <div class="list-excerpt"><?php the_content(); ?></div>
        <div class="list-item-meta">
          <?php
            the_category(', ');
            echo '　';
            the_tags('', ', ');
            ?>
        </div>
        <div class="list-item-meta">
          <?php
            the_time('Y-m-d');
            // TODO:
            // barthelme_author_link();
            echo '　';
            comments_popup_link(__('Comments (0)'), __('Comments (1)'), __('Comments (%)'));
            ?>
        </div>
        
        <a class="read-more" href="<?php the_permalink() ?>" target="_blank">阅读全文</a>

        <div><?php edit_post_link(__('Edit')); ?></div>
      </div>

      <?php
        /*
          the_title( '<h2>', '</h2>' );
          // the_author();
          // the_meta(); 弃用
          the_shortlink();
          // in_category( 3 )
          the_content('<span class="more-link">'.__('Continue reading &rsaquo;', 'mukti').'</span>');
        */
      ?>

    <?php endwhile; ?>

    <!-- TODO: Google AdSence -->
    <?php if ( is_home() || is_front_page() ): ?>
    <?php else: ?>
    <div id="post-0" class="list-item <?php barthelme_post_class() ?>" style="height: 90px; padding: 0;">
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
    <div class="navigation">
      <div class="nav-prev"><?php previous_posts_link(); ?></div>
      <div class="nav-next"><?php next_posts_link(); ?></div>
    </div>
    <?php endif; ?>
    <!-- TODO: -->
    <?php
      // the_post_navigation( array(
      // 'next_text' => '<span class="meta-nav">' . __( 'Next', 'twentyfifteen' ) . '</span> ' .
      // '<span class="screen-reader-text">' . __( 'Next post:', 'twentyfifteen' ) . '</span> ' .
      // '<span class="post-title">%title</span>',
      // 'prev_text' => '<span class="meta-nav">' . __( 'Previous', 'twentyfifteen' ) . '</span> ' .
      // '<span class="screen-reader-text">' . __( 'Previous post:', 'twentyfifteen' ) . '</span> ' .
      // '<span class="post-title">%title</span>',
      // ) );
    ?>

  <?php else : _e( 'Sorry, no posts matched your criteria.', 'mukti' );
    endif; ?>
<!-- </div> -->