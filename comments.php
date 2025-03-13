<?php if ( 'comments.php' == basename($_SERVER['SCRIPT_FILENAME']) )
  die ( 'Please do not load this page directly. Thanks!' ); ?>

<div class="comments">

  <!-- TODO: -->
  <?php
    $req = get_option('require_name_email'); // Checks if fields are required. Thanks, Adam. ;-)
    if ( !empty($post->post_password) ) :
      if ( $_COOKIE['wp-postpass_' . COOKIEHASH] != $post->post_password ) : ?>
          <div class="protected"><?php _e('This post is protected. Enter the password to view any comments.', 'mukti') ?></div>
        </div><!-- div.comments -->
        <?php return;
      endif;
    endif;
  ?>

  <?php if ( $comments ) : ?>

    <!-- TODO: -->
    <?php global $barthelme_comment_alt // Gives .alt class for every-other comment/pingback ?>
    
    <!-- TODO: -->
    <?php
      $ping_count = 0;
      $comment_count = 0;
      foreach ( $comments as $comment )
        get_comment_type() == "comment" ? ++$comment_count : ++$ping_count;
    ?>

    <?php if ( $comment_count ) : ?>
      <!-- TODO: -->
      <?php $barthelme_comment_alt = 0 // Resets comment count for .alt classes ?>

      <h3 class="comment-title"><?php _e('Comments'); ?></h3>
      <span><?php echo '共' . $comment_count . '条'; ?></span>

      <ol id="comments" class="comment-list">
        <?php foreach ($comments as $comment) : ?>
          <?php if ( get_comment_type() == "comment" ) : ?>
            <!-- TODO: barthelme_comment_class -->
            <li id="comment-<?php comment_ID() ?>" class="<?php barthelme_comment_class() ?>">
              <div class="comment-wrap">
                <div class="comment-meta">
                  <span class="comment-author"><?php barthelme_commenter_link() ?></span>
                  <?php printf(__('<span class="comment-datetime" title="%1$s">%2$s %3$s</span>'),
                    get_the_time('Y-m-d\TH:i:sO'),
                    get_comment_date(),
                    get_comment_time() ); ?>
                  <!-- <span class="comment-permalink"><a href="#comment-<?php comment_ID() ?>" title="Permalink to this comment"><?php _e('Permalink') ?></a></span> -->
                  <?php edit_comment_link(__('Edit')); ?>
                </div>
                <!-- TODO: -->
                <?php if ($comment->comment_approved == '0') : ?><span class="unapproved"><?php _e('Your comment is awaiting moderation.', 'mukti') ?></span><?php endif; ?>
                <div class="comment-text"><?php comment_text() ?></div>
              </div>
            </li>
          <?php endif; ?>
        <?php endforeach; ?>
      </ol>
    <?php endif; ?>

    <!-- TODO: -->
    <!-- <?php if ( $ping_count ) : ?>
      <?php $barthelme_comment_alt = 0 // Resets comment count for .alt classes for pingbacks ?>

      <h3 class="comment-title"><?php _e('Trackbacks'); ?></h3>
      <span><?php echo '共' . $ping_count . '条'; ?></span>

      <ol id="pingbacks" class="commentlist">
        <?php foreach ( $comments as $comment ) : ?>
          <?php if ( get_comment_type() != "comment" ) : ?>
            <li id="comment-<?php comment_ID() ?>" class="<?php barthelme_comment_class() ?>">
            <div class="comment-meta">
            <span class="pingback-author vcard"><span class="fn n url org"><?php comment_author_link() ?></span></span>
            <span class="meta-sep">|</span>
            <span class="pingback-datetime"><?php printf(__('<span class="comment-published" title="%1$s">%2$s at %3$s</span></span>', 'mukti'),
            get_the_time('Y-m-d\TH:i:sO'),
            get_comment_date(),
            get_comment_time() ); ?>

            <span class="meta-sep">|</span>
            <span class="comment-permalink"><a href="#comment-<?php comment_ID() ?>" title="Permalink to this comment">Permalink</a></span>
            <?php edit_comment_link(__('Edit')); ?>
            </div>
            <?php if ($comment->comment_approved == '0') : ?><span class="unapproved"><?php _e('Your comment is awaiting moderation.') ?></span><?php endif; ?>
            <?php comment_text() ?>
            </li>
          <?php endif ?>
        <?php endforeach; ?>
      </ol>
    <?php endif ?> -->

  <?php endif ?>

  <!-- TODO: -->
  <?php if ( 'open' == $post->comment_status ) : ?>
    <h3 class="comment-title"><?php _e('发表评论') ?></h3>
    <?php if ( get_option('comment_registration') && !$user_ID ) : ?>
      <div id="mustlogin">
        <?php printf(__('<a href="%s" title="Log in">登录</a> 之后才可评论。', 'mukti'), get_option('siteurl') . '/wp-login.php?redirect_to=' . get_permalink() ); ?>
      </div>
    <?php else : ?>
      <div class="comment-form">  
        <form id="comment-form" action="<?php echo get_option('siteurl'); ?>/wp-comments-post.php" method="POST">
          <!-- <div class="form-label"><label for="comment"><?php _e('Comment') ?></label></div> -->
          <div class="form-textarea"><textarea id="comment" name="comment" placeholder="<?php _e('Comment') ?>（必填）"></textarea></div>
          <?php if ( isset($user_ID) ) : ?>
            <div id="loggedin"><?php printf(__('<a href="%1$s" title="View your profile" class="fn">%2$s</a>. <a href="%3$s" title="Log out of this account">注销</a>', 'mukti'),
            get_option('siteurl') . '/wp-admin/profile.php',
            _wp_specialchars($user_identity, true),
            get_option('siteurl') . '/wp-login.php?action=logout&amp;redirect_to=' . get_permalink() ) ?></div>
          <?php else : ?>
            <!-- TODO: -->
            <!-- <div id="comment-notes"><?php _e('Your email is <em>never</em> published nor shared.', 'mukti') ?> <?php if ($req) _e('Required fields are marked <span class="req-field">*</span>', 'mukti') ?></div> -->
            <!-- <div class="form-label"><label for="author"><?php _e('Name') ?><?php if ($req) _e(' <span class="req-field">*</span>') ?></label></div> -->
            <!-- <div class="form-label"><label for="email"><?php _e('Email') ?><?php if ($req) _e(' <span class="req-field">*</span>') ?></label></div> -->
            <!-- <div class="form-label"><label for="url"><?php _e('Website') ?></label></div> -->
            <div class="form-input">
              <input id="author" name="author" type="text" value="<?php echo $comment_author ?>" placeholder="<?php _e('Name') ?>（必填）" style="width:120px; margin-right: 8px;" />
              <input id="email" name="email" type="text" value="<?php echo $comment_author_email ?>" placeholder="<?php _e('Email') ?>（必填）" style="width:200px; margin-right: 8px;" />
              <input id="url" name="url" type="text" value="<?php echo $comment_author_url ?>" placeholder="<?php _e('Website') ?>（选填）" style="width:240px; margin-right: 8px;" />
            </div>
          <?php endif ?>
          <div class="form-submit">
            <input id="submit" name="submit" type="submit" value="<?php _e('Submit') ?>" />
            <input type="hidden" name="comment_post_ID" value="<?php echo $id; ?>" />
          </div>
          <!-- TODO: -->
          <?php do_action('comment_form', $post->ID); ?>
        </form>
      </div>
    <?php endif ?>
  <?php endif ?>
</div>
