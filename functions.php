<?php
// TODO: Just a test.
include_once get_template_directory() . '/widgets/foo-widget.php'; //  最新评论

// Public: cURL
if (!function_exists('curl_post')) {
  function curl_post($post_url, $post_data) {
      $ch= curl_init();
      $header[] = "";
      curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
      curl_setopt($ch, CURLOPT_URL, $post_url);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
      curl_setopt($ch, CURLOPT_POST, 1);
      curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
      curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
      $output = curl_exec($ch);
      curl_close($ch);
      return $output;
  }
}

// 评论推送通知到企业微信机器人
// TODO: 做成可配置
function mukti_qy_notice($comment_id){
  $comment = get_comment($comment_id);
  $content = $comment->comment_content;
  $datetime  = $comment->comment_date;
  $author = $comment->comment_author;
  $title = get_the_title($comment->comment_post_ID);
  // $site_name = get_bloginfo('name');
  $url = get_bloginfo('url') . '/?=' . $comment->comment_post_ID;
  // 企业微信机器人 webhook url
  $webhook  = "https://qyapi.weixin.qq.com/cgi-bin/webhook/send?key=b930b446-184c-4742-ad37-af9fa20f1ff0";
  $data = '{"msgtype": "text","text": {"content": "' . $datetime .'\n' . $author . '评论《' . $title . '》：' . $content . '\n' . $url . '"}}';
  return $res = curl_post($webhook, $data);
}
// TODO: 19, 2 什么意思？
add_action('comment_post', 'mukti_qy_notice', 19, 2);

/**
 * 替换 gravatar 服务器
 */
function mukti_get_https_avatar($avatar) {
    $new_host = 'gravatar.loli.net/avatar';
    $avatar = str_replace(array(
        'secure.gravatar.com/avatar',
        "www.gravatar.com/avatar",
        "0.gravatar.com/avatar",
        "1.gravatar.com/avatar",
        "2.gravatar.com/avatar"
    ), $new_host, $avatar);
    $avatar = str_replace("http://", "https://", $avatar);
    return $avatar;
}
add_filter( 'get_avatar', 'mukti_get_https_avatar');
add_filter( 'um_user_avatar_url_filter', 'mukti_get_https_avatar', 1 );
add_filter( 'bp_gravatar_url', 'mukti_get_https_avatar', 1 );
add_filter( 'get_avatar_url', 'mukti_get_https_avatar', 1 );

/**
 * 注册菜单
 */
register_nav_menus(array(
  'top-menu' => __( 'Top Menu' ),
));
// TODO: 未使用
register_nav_menus(array(
  'right-menu' => __( 'Right Menu' ),
));

/**
 * Filter the "read more" excerpt string link to the post.
 *
 * @param string $more "Read more" excerpt string.
 * @return string (Maybe) modified "read more" excerpt string.
 */
function wpdocs_excerpt_more( $more ) {
	if ( !is_single() ) {
		$more = sprintf( '... <a class="more" href="%1$s">%2$s</a>',
			get_permalink( get_the_ID() ),
			__( 'Read More')
		);
	}
	return $more;
}
add_filter( 'excerpt_more', 'wpdocs_excerpt_more' );

// 加载语言包
function mukti_load_theme_textdomain() {
  // From /wp-content/languages/themes/{text-domain}-{locale}.mo 自动加载，该函数可省略
  // load_theme_textdomain( 'mukti', get_template_directory() . '/languages' );
  // From /wp-content/themes/mukti/{locale}.mo
  load_theme_textdomain('mukti');
}
add_action( 'after_setup_theme', 'mukti_load_theme_textdomain' );

/**
 * Sidebar
 */
function mukti_register_sidebar() {
  register_widget( 'WP_Widget_Pages' );
	register_widget( 'WP_Widget_Calendar' );
	register_widget( 'WP_Widget_Archives' );
	register_widget( 'Foo_Widget' );

  register_sidebar(
    array(
      'name'          => esc_html__( 'Sidebar' ),
      'id'            => 'sidebar',
      'description'   => esc_html__( 'Add widgets here to appear in your sidebar.', 'mukti' ),
      'before_widget' => '<div id="%1$s" class="widget %2$s">',
      'after_widget'  => '</div>',
      'before_title'  => '<h3 class="widget-title">',
      'after_title'   => '</h3>',
    )
  );
  register_sidebar(
    array(
      'name'          => esc_html__( 'Footer Left' ),
      'id'            => 'footer-left',
      'description'   => esc_html__( 'Add widgets here to appear in the left of your footer.', 'mukti' ),
      'before_widget' => '<span id="%1$s" class="widget %2$s">',
      'after_widget'  => '</span>',
      'before_title'  => '<span class="widget-title">',
      'after_title'   => '</span>',
    )
  );
  register_sidebar(
    array(
      'name'          => esc_html__( 'Footer Right' ),
      'id'            => 'footer-right',
      'description'   => esc_html__( 'Add widgets here to appear in the right of your footer.', 'mukti' ),
      'before_widget' => '<span id="%1$s" class="widget %2$s">',
      'after_widget'  => '</span>',
      'before_title'  => '<span class="widget-title">',
      'after_title'   => '</span>',
    )
  );
  // wp_widgets_init();
}
// add_action( 'init', 'mukti_register_sidebar' );
add_action( 'widgets_init', 'mukti_register_sidebar' );

// Mukti Theme Settings 主题设置后台菜单
// *_admin()
// *_admin_head()
// *_add_admin()
function mukti_settings_admin() {
  if ( isset($_REQUEST['saved']) && $_REQUEST['saved'] ) {
    ?>
      <div id="message1" class="updated fade">
        <p>
          <!-- <?php printf(__('Mukti theme settings saved. <a href="%s">View site.</a>', 'mukti'), bloginfo('url') . '/'); ?> -->
          Mukti theme settings saved. <a href="<?php bloginfo('url'); ?>">View site.</a>
        </p>
      </div>
    <?php
  }
  if ( isset($_REQUEST['reset']) && $_REQUEST['reset'] ) {
    ?>
      <div id="message2" class="updated fade">
        <p>
          <?php _e('Mukti theme settings reset.', 'mukti'); ?>
        </p>
      </div>
    <?php
  }
  ?>
    <div class="wrap" id="mukti-settings">
      <h2><?php _e('Mukti Theme Settings', 'mukti'); ?></h2>
      <?php
        // TODO:
        // printf( __('%1$s<p>Thanks for selecting the <a  title="Mukti theme for WordPress">Barthelme</a> theme by <span class="vcard"><a class="url fn n"  title="plaintxt.org" rel="me designer">PlainTXT.org</a></span>. Please read the included <a href="%2$s" title="Open the readme.html" rel="enclosure" id="readme">documentation</a> for more information about the Barthelme and its advanced features. <strong>If you find this theme useful, please consider <label for="paypal">donating</label>.</strong> You must click on <i><u>S</u>ave Options</i> to save any changes. You can also discard your changes and reload the default settings by clicking on <i><u>R</u>eset</i>.</p>', 'mukti'), barthelme_donate(), get_template_directory_uri() . '/readme.html' ); 
      ?>

      <form action="<?php echo _wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
        <?php wp_nonce_field('mukti_save_settings'); echo "\n"; ?>
        <!-- <h3><?php _e('Typography 印刷选项', 'mukti'); ?></h3> -->
        <table class="form-table" summary="Mukti typography settings">
          <tr valign="top">
            <th scope="row"><label for="main_avatar">网站主图路径</label></th> 
            <td>
              <input id="main_avatar" name="main_avatar" type="text" class="text" value="<?php if ( get_option('mukti_main_avatar') == "" ) { echo "/wp-content/themes/mukti/assets/images/avatar.png"; } else { echo esc_attr(get_option('mukti_main_avatar')); } ?>" tabindex="1" size="50" /><br />
              <p class="info">网站主图是一张 120 * 240 的图片，均分为上下两部分，鼠标悬停时滑动显示下半部分。</p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="tj_code">统计代码</label></th> 
            <td>
              <textarea id="tj_code" name="tj_code" style="min-width: 50%; min-height: 96px"><?php echo get_option('mukti_tj_code'); ?></textarea>
            </td>
          </tr>
        </table>

        <p class="submit">
          <input name="save" type="submit" value="<?php _e('Save Settingss', 'mukti'); ?>" tabindex="26" accesskey="S" class="button button-primary button-large" />  
          <input name="action" type="hidden" value="save" />
          <!-- TODO: -->
          <input name="page_options" type="hidden" value="bm_basefontsize,bm_basefontfamily,bm_headingfontfamily,bm_posttextalignment,bm_layoutwidth,bm_uppercolor,bm_lowercolor,bm_headerfontcolor,bm_authorlink,bm_avatarsize" />
        </p>
      </form>
      <!-- <h3 id="reset"><?php _e('Reset Settingss', 'mukti'); ?></h3>
      <p><?php _e('Resetting deletes all stored Barthelme options from your database. After resetting, default options are loaded but are not stored until you click <i>Save Settings</i>. A reset does not affect the actual theme files in any way. If you are uninstalling Barthelme, please reset before removing the theme files to clear your databse.', 'mukti'); ?></p>
      <form action="<?php echo _wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
        <?php wp_nonce_field('mukti_reset_settings'); echo "\n"; ?>
        <p class="submit">
          <input name="reset" type="submit" value="<?php _e('Reset Settings', 'mukti'); ?>" onclick="return confirm('<?php _e('Click OK to reset. Any changes to these theme options will be lost!', 'mukti'); ?>');" tabindex="27" accesskey="R" class="button button-primary button-large" />
          <input name="action" type="hidden" value="reset" />
          <input name="page_options" type="hidden" value="bm_basefontsize,bm_basefontfamily,bm_headingfontfamily,bm_posttextalignment,bm_layoutwidth,bm_uppercolor,bm_lowercolor,bm_headerfontcolor,bm_authorlink,bm_avatarsize" />
        </p>
      </form> -->
    </div>
  <?php
}

// Additional CSS styles for the Mukti Settings menu
function mukti_settings_admin_head() {
?>
  <style type="text/css" media="screen,projection">
  /*<![CDATA[*/
    p.info span{font-weight:bold;}
  /*]]>*/
  </style>
<?php
}
// Loads the admin menu; sets default settings for each
function mukti_settings_add_admin() {
  if ( isset($_GET['page']) && $_GET['page'] == 'mukti_settings' ) {
    if ( isset($_REQUEST['action']) && 'save' == $_REQUEST['action'] ) {
      check_admin_referer('mukti_save_settings');
      update_option( 'mukti_main_avatar', strip_tags( stripslashes( $_REQUEST['main_avatar'] ) ) );
      update_option( 'mukti_tj_code', stripslashes( $_REQUEST['tj_code'] ) );
      header("Location: themes.php?page=mukti_settings&saved=true");
      die;
    } elseif ( isset($_REQUEST['action']) && 'reset' == $_REQUEST['action'] ) {
      // check_admin_referer('barthelme_reset_options');
      // delete_option('barthelme_authorlink');
      // delete_option('barthelme_basefontfamily');
      // delete_option('barthelme_basefontsize');
      // delete_option('barthelme_headerfontcolor');
      // delete_option('barthelme_headingfontfamily');
      // delete_option('barthelme_layoutwidth');
      // delete_option('barthelme_lowercolor');
      // delete_option('barthelme_posttextalignment');
      // delete_option('barthelme_uppercolor');
      // delete_option('barthelme_avatarsize');
      // header("Location: themes.php?page=mukti_settings&reset=true");
      // die;
    }
    add_action('admin_head', 'mukti_settings_admin_head');
  }
  add_theme_page( __( 'Mukti Theme Settings', 'mukti' ), __( 'Theme Settings', 'mukti' ), 'edit_themes', 'mukti_settings', 'mukti_settings_admin' );
}
add_action('admin_menu', 'mukti_settings_add_admin');

// WP前台读取 Mukti Settings
function mukti_settings_wp_head() {
  // 取值
  $mukti_main_avatar = get_option('mukti_main_avatar') == "" ? '/wp-content/themes/mukti/assets/images/avatar.png' : esc_attr( stripslashes( get_option('mukti_main_avatar') ) );
  ?>
  <style type="text/css" media="screen,projection">
  /*<![CDATA[*/
  .sidebar .avatar a {
    background-image: url(<?php echo $mukti_main_avatar; ?>) !important;
  }
  /*]]>*/
  </style>
  <?php
}
add_action('wp_head', 'mukti_settings_wp_head');

// ---------- ↑ 已梳理

// TODO:
// Mukti Theme Options 主题设置后台菜单
// *_admin()
// *_admin_head()
// *_add_admin()
function barthelme_admin() {
  if ( isset($_REQUEST['saved']) && $_REQUEST['saved'] ) {
    ?>
      <div id="message1" class="updated fade">
        <p>
          <?php printf(__('Barthelme theme options saved. <a href="%s">View site.</a>', 'mukti'), bloginfo('url') . '/'); ?>
        </p>
    </div>
    <?php
  }
  if ( isset($_REQUEST['reset']) && $_REQUEST['reset'] ) {
    ?>
      <div id="message2" class="updated fade">
        <p>
          <?php _e('Barthelme theme options reset.', 'mukti'); ?>
        </p>
      </div>
    <?php
  }
  ?>
    <div class="wrap" id="barthelme-options">
      <h2><?php _e('Barthelme Theme Options', 'mukti'); ?></h2>
      <?php printf( __('%1$s<p>Thanks for selecting the <a  title="Barthelme theme for WordPress">Barthelme</a> theme by <span class="vcard"><a class="url fn n"  title="plaintxt.org" rel="me designer">PlainTXT.org</a></span>. Please read the included <a href="%2$s" title="Open the readme.html" rel="enclosure" id="readme">documentation</a> for more information about the Barthelme and its advanced features. <strong>If you find this theme useful, please consider <label for="paypal">donating</label>.</strong> You must click on <i><u>S</u>ave Options</i> to save any changes. You can also discard your changes and reload the default settings by clicking on <i><u>R</u>eset</i>.</p>', 'mukti'), barthelme_donate(), get_template_directory_uri() . '/readme.html' ); ?>

      <form action="<?php echo _wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
        <?php wp_nonce_field('barthelme_save_options'); echo "\n"; ?>
        <h3><?php _e('Typography 印刷选项', 'mukti'); ?></h3>
        <table class="form-table" summary="Barthelme typography options">
          <tr valign="top">
            <th scope="row"><label for="bm_basefontsize"><?php _e('Base font size', 'mukti'); ?></label></th> 
            <td>
              <input id="bm_basefontsize" name="bm_basefontsize" type="text" class="text" value="<?php if ( get_option('barthelme_basefontsize') == "" ) { echo "75%"; } else { echo esc_attr(get_option('barthelme_basefontsize')); } ?>" tabindex="1" size="10" /><br />
              <p class="info"><?php _e('The base font size globally affects the size of text throughout your blog. This can be in any unit (e.g., px, pt, em), but I suggest using a percentage (%). Default is <span>75%</span>.', 'mukti'); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Base font family', 'mukti'); ?></th> 
            <td>
              <input id="bm_basefontArial" name="bm_basefontfamily" type="radio" class="radio" value="PingfangSC-light,sans-serif" <?php if ( ( get_option('barthelme_basefontfamily') == "") || ( get_option('barthelme_basefontfamily') == "PingfangSC-light,sans-serif") ) { echo 'checked="checked"'; } ?> tabindex="2" /> <label for="bm_basefontArial" class="arial">苹方细体（仅MacOS）</label><br />
              <input id="bm_basefontArial" name="bm_basefontfamily" type="radio" class="radio" value="arial,helvetica,sans-serif" <?php if ( ( get_option('barthelme_basefontfamily') == "") || ( get_option('barthelme_basefontfamily') == "arial,helvetica,sans-serif") ) { echo 'checked="checked"'; } ?> tabindex="2" /> <label for="bm_basefontArial" class="arial">Arial 中文示例</label><br />
              <input id="bm_basefontCourier" name="bm_basefontfamily" type="radio" class="radio" value="'courier new',courier,monospace" <?php if ( get_option('barthelme_basefontfamily') == "'courier new',courier,monospace" ) { echo 'checked="checked"'; } ?> tabindex="3" /> <label for="bm_basefontCourier" class="courier">Courier</label><br />
              <input id="bm_basefontGeorgia" name="bm_basefontfamily" type="radio" class="radio" value="georgia,times,serif" <?php if ( get_option('barthelme_basefontfamily') == "georgia,times,serif" ) { echo 'checked="checked"'; } ?> tabindex="4" /> <label for="bm_basefontGeorgia" class="georgia">Georgia</label><br />
              <input id="bm_basefontLucidaConsole" name="bm_basefontfamily" type="radio" class="radio" value="'lucida console',monaco,monospace" <?php if ( get_option('barthelme_basefontfamily') == "'lucida console',monaco,monospace" ) { echo 'checked="checked"'; } ?> tabindex="5" /> <label for="bm_basefontLucidaConsole" class="lucida-console">Lucida Console</label><br />
              <input id="bm_basefontLucidaUnicode" name="bm_basefontfamily" type="radio" class="radio" value="'lucida sans unicode','lucida grande',sans-serif" <?php if ( get_option('barthelme_basefontfamily') == "'lucida sans unicode','lucida grande',sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="6" /> <label for="bm_basefontLucidaUnicode" class="lucida-unicode">Lucida Sans Unicode</label><br />
              <input id="bm_basefontTahoma" name="bm_basefontfamily" type="radio" class="radio" value="tahoma,geneva,sans-serif" <?php if ( get_option('barthelme_basefontfamily') == "tahoma,geneva,sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="7" /> <label for="bm_basefontTahoma" class="tahoma">Tahoma</label><br />
              <input id="bm_basefontTimes" name="bm_basefontfamily" type="radio" class="radio" value="'times new roman',times,serif" <?php if ( get_option('barthelme_basefontfamily') == "'times new roman',times,serif" ) { echo 'checked="checked"'; } ?> tabindex="8" /> <label for="bm_basefontTimes" class="times">Times</label><br />
              <input id="bm_basefontTrebuchetMS" name="bm_basefontfamily" type="radio" class="radio" value="'trebuchet ms',helvetica,sans-serif" <?php if ( get_option('barthelme_basefontfamily') == "'trebuchet ms',helvetica,sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="9" /> <label for="bm_basefontTrebuchetMS" class="trebuchet">Trebuchet MS</label><br />
              <input id="bm_basefontVerdana" name="bm_basefontfamily" type="radio" class="radio" value="verdana,geneva,sans-serif" <?php if ( get_option('barthelme_basefontfamily') == "verdana,geneva,sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="10" /> <label for="bm_basefontVerdana" class="verdana">Verdana</label>
              <p class="info"><?php printf(__('The base font family sets the font for everything except content headings. The selection is limited to %1$s fonts, as they will display correctly. Default is <span class="arial">Arial</span>.', 'mukti'), '<cite><a href="http://en.wikipedia.org/wiki/Web_safe_fonts" title="Web safe fonts - Wikipedia">web safe</a></cite>'); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><?php _e('Heading font family', 'mukti'); ?></th> 
            <td>
              <input id="bm_headingfontArial" name="bm_headingfontfamily" type="radio" class="radio" value="PingfangSC-light,sans-serif" <?php if ( get_option('barthelme_headingfontfamily') == "PingfangSC-light,sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="11" /> <label for="bm_headingfontArial" class="arial">苹方细体（仅MacOS）</label><br />
              <input id="bm_headingfontArial" name="bm_headingfontfamily" type="radio" class="radio" value="arial,helvetica,sans-serif" <?php if ( get_option('barthelme_headingfontfamily') == "arial,helvetica,sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="11" /> <label for="bm_headingfontArial" class="arial">Arial</label><br />
              <input id="bm_headingfontCourier" name="bm_headingfontfamily" type="radio" class="radio" value="'courier new',courier,monospace" <?php if ( get_option('barthelme_headingfontfamily') == "'courier new',courier,monospace" ) { echo 'checked="checked"'; } ?> tabindex="12" /> <label for="bm_headingfontCourier" class="courier">Courier</label><br />
              <input id="bm_headingfontGeorgia" name="bm_headingfontfamily" type="radio" class="radio" value="georgia,times,serif" <?php if ( ( get_option('barthelme_headingfontfamily') == "") || ( get_option('barthelme_headingfontfamily') == "georgia,times,serif") ) { echo 'checked="checked"'; } ?> tabindex="13" /> <label for="bm_headingfontGeorgia" class="georgia">Georgia</label><br />
              <input id="bm_headingfontLucidaConsole" name="bm_headingfontfamily" type="radio" class="radio" value="'lucida console',monaco,monospace" <?php if ( get_option('barthelme_headingfontfamily') == "'lucida console',monaco,monospace" ) { echo 'checked="checked"'; } ?> tabindex="14" /> <label for="bm_headingfontLucidaConsole" class="lucida-console">Lucida Console</label><br />
              <input id="bm_headingfontLucidaUnicode" name="bm_headingfontfamily" type="radio" class="radio" value="'lucida sans unicode','lucida grande',sans-serif" <?php if ( get_option('barthelme_headingfontfamily') == "'lucida sans unicode','lucida grande',sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="15" /> <label for="bm_headingfontLucidaUnicode" class="lucida-unicode">Lucida Sans Unicode</label><br />
              <input id="bm_headingfontTahoma" name="bm_headingfontfamily" type="radio" class="radio" value="tahoma,geneva,sans-serif" <?php if ( get_option('barthelme_headingfontfamily') == "tahoma,geneva,sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="16" /> <label for="bm_headingfontTahoma" class="tahoma">Tahoma</label><br />
              <input id="bm_headingfontTimes" name="bm_headingfontfamily" type="radio" class="radio" value="'times new roman',times,serif" <?php if ( get_option('barthelme_headingfontfamily') == "'times new roman',times,serif" ) { echo 'checked="checked"'; } ?> tabindex="17" /> <label for="bm_headingfontTimes" class="times">Times</label><br />
              <input id="bm_headingfontTrebuchetMS" name="bm_headingfontfamily" type="radio" class="radio" value="'trebuchet ms',helvetica,sans-serif" <?php if ( get_option('barthelme_headingfontfamily') == "'trebuchet ms',helvetica,sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="18" /> <label for="bm_headingfontTrebuchetMS" class="trebuchet">Trebuchet MS</label><br />
              <input id="bm_headingfontVerdana" name="bm_headingfontfamily" type="radio" class="radio" value="verdana,geneva,sans-serif" <?php if ( get_option('barthelme_headingfontfamily') == "verdana,geneva,sans-serif" ) { echo 'checked="checked"'; } ?> tabindex="19" /> <label for="bm_headingfontVerdana" class="verdana">Verdana</label>
              <p class="info"><?php printf(__('The heading font family sets the font for all content headings and miscellanea. The selection is limited to %1$s fonts, as they will display correctly. Default is <span class="georgia">Georgia</span>. ', 'mukti'), '<cite><a href="http://en.wikipedia.org/wiki/Web_safe_fonts" title="Web safe fonts - Wikipedia">web safe</a></cite>'); ?></p>
            </td>
          </tr>
        </table>

        <h3><?php _e('Layout 布局', 'mukti'); ?></h3>
        <table class="form-table" summary="Barthelme Layout options">
          <tr valign="top">
            <th scope="row"><label for="bm_layoutwidth"><?php _e('Layout width', 'mukti'); ?></label></th> 
            <td>
              <input id="bm_layoutwidth" name="bm_layoutwidth" type="text" class="text" value="<?php if ( get_option('barthelme_layoutwidth') == "" ) { echo "auto"; } else { echo esc_attr(get_option('barthelme_layoutwidth')); } ?>" tabindex="20" size="10" />
              <p class="info"><?php _e('The layout width determines the normal width of the entire layout. This can be in any unit (e.g., px, pt, em), but I suggest "auto". Default is <span>auto</span>.', 'mukti'); ?></p>
              <p class="info"><?php _e('<em>Note: If you use 100%, the width will be ever-so-larger than the browser window. If you want to play, I suggest playing with ems. But really, auto is the best option.</em>', 'mukti'); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="bm_posttextalignment"><?php _e('Post text alignment', 'mukti'); ?></label></th> 
            <td>
              <select id="bm_posttextalignment" name="bm_posttextalignment" tabindex="21" class="dropdown">
                <option value="center" <?php if ( get_option('barthelme_posttextalignment') == "center" ) { echo 'selected="selected"'; } ?>><?php _e('Centered', 'mukti'); ?> </option>
                <option value="justify" <?php if ( get_option('barthelme_posttextalignment') == "justify" ) { echo 'selected="selected"'; } ?>><?php _e('Justified', 'mukti'); ?> </option>
                <option value="left" <?php if ( ( get_option('barthelme_posttextalignment') == "") || ( get_option('barthelme_posttextalignment') == "left") ) { echo 'selected="selected"'; } ?>><?php _e('Left', 'mukti'); ?> </option>
                <option value="right" <?php if ( get_option('barthelme_posttextalignment') == "right" ) { echo 'selected="selected"'; } ?>><?php _e('Right', 'mukti'); ?> </option>
              </select>
              <p class="info"><?php _e('Choose one of the options for the alignment of the post entry text. Default is <span>left</span>.', 'mukti'); ?></p>
            </td>
          </tr>
        </table>

        <h3><?php _e('Content 内容', 'mukti'); ?></h3>
        <table class="form-table" summary="Barthelme Content options">
          <tr valign="top">
            <th scope="row"><label for="bm_authorlink"><?php _e('Author link', 'mukti'); ?></label></th> 
            <td>
              <select id="bm_authorlink" name="bm_authorlink" tabindex="21" class="dropdown">
                <option value="displayed" <?php if ( ( get_option('barthelme_authorlink') == "") || ( get_option('barthelme_authorlink') == "displayed") ) { echo 'selected="selected"'; } ?>><?php _e('Displayed', 'mukti'); ?> </option>
                <option value="hidden" <?php if ( get_option('barthelme_authorlink') == "hidden" ) { echo 'selected="selected"'; } ?>><?php _e('Hidden', 'mukti'); ?> </option>
              </select>
              <p class="info"><?php _e('The author\'s name and link to his/her corresponding archives page can be displayed or hidden. The "hidden" setting disables the link in an author\'s name in single post footers (and in pages &mdash; see the <a href="#readme">documentation</a> for info). Default is <span>displayed</span>.', 'mukti'); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="bm_avatarsize"><?php _e('Avatar size', 'mukti'); ?></label></th> 
            <td>
              <input id="bm_avatarsize" name="bm_avatarsize" type="text" class="text" value="<?php if ( get_option('barthelme_avatarsize') == null ) { echo "32"; } else { echo esc_attr(get_option('barthelme_avatarsize')); } ?>" size="6" />
              <p class="info"><?php _e('Sets the avatar size in pixels, if avatars are enabled. Default is <span>32</span>.', 'mukti'); ?></p>
            </td>
          </tr>
        </table>

        <h3><?php _e('Header colors 标题颜色', 'mukti'); ?></h3>
        <p><?php _e('You must enter the full, six-digit <a  title="HTML Colors at W3 Schools">hexidecimal color value</a> without the "#" symbol for each color value. Otherwise, things won\'t work.', 'mukti'); ?></p>
        <table class="form-table" summary="Barthelme Header colors options">
          <tr valign="top">
            <th scope="row"><label for="bm_uppercolor"><?php _e('Upper gradient color', 'mukti'); ?></label></th> 
            <td>
              # <input id="bm_uppercolor" name="bm_uppercolor" type="text" class="text" value="<?php if ( get_option('barthelme_uppercolor') == "" ) { echo "8999b0"; } else { echo esc_attr(get_option('barthelme_uppercolor')); } ?>" tabindex="23" size="10" />
              <p class="info"><?php _e('Sets the upper color for the banner gradient. Default is <span>8999B0</span>.', 'mukti'); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="bm_lowercolor"><?php _e('Lower gradient color', 'mukti'); ?></label></th> 
            <td>
              # <input id="bm_lowercolor" name="bm_lowercolor" type="text" class="text" value="<?php if ( get_option('barthelme_lowercolor') == "" ) { echo "bbc8d9"; } else { echo esc_attr(get_option('barthelme_lowercolor')); } ?>" tabindex="24" size="10" />
              <p class="info"><?php _e('Sets the lower color for the banner gradient. Default is <span>BBC8D9</span>.', 'mukti'); ?></p>
            </td>
          </tr>
          <tr valign="top">
            <th scope="row"><label for="bm_headerfontcolor"><?php _e('Header font color', 'mukti'); ?></label></th> 
            <td>
              # <input id="bm_headerfontcolor" name="bm_headerfontcolor" type="text" class="text" value="<?php if ( get_option('barthelme_headerfontcolor') == "" ) { echo "fefefe"; } else { echo esc_attr(get_option('barthelme_headerfontcolor')); } ?>" tabindex="25" size="10" />
              <p class="info"><?php _e('Sets the font color for the blog title and description. Default is <span>FEFEFE</span>.', 'mukti'); ?></p>
            </td>
          </tr>
        </table>
        <p class="submit">
          <input name="save" type="submit" value="<?php _e('Save Options', 'mukti'); ?>" tabindex="26" accesskey="S" class="button button-primary button-large" />  
          <input name="action" type="hidden" value="save" />
          <input name="page_options" type="hidden" value="bm_basefontsize,bm_basefontfamily,bm_headingfontfamily,bm_posttextalignment,bm_layoutwidth,bm_uppercolor,bm_lowercolor,bm_headerfontcolor,bm_authorlink,bm_avatarsize" />
        </p>
      </form>
      <h3 id="reset"><?php _e('Reset Options', 'mukti'); ?></h3>
      <p><?php _e('Resetting deletes all stored Barthelme options from your database. After resetting, default options are loaded but are not stored until you click <i>Save Options</i>. A reset does not affect the actual theme files in any way. If you are uninstalling Barthelme, please reset before removing the theme files to clear your databse.', 'mukti'); ?></p>
      <form action="<?php echo _wp_specialchars( $_SERVER['REQUEST_URI'] ) ?>" method="post">
        <?php wp_nonce_field('barthelme_reset_options'); echo "\n"; ?>
        <p class="submit">
          <input name="reset" type="submit" value="<?php _e('Reset Options', 'mukti'); ?>" onclick="return confirm('<?php _e('Click OK to reset. Any changes to these theme options will be lost!', 'mukti'); ?>');" tabindex="27" accesskey="R" class="button button-primary button-large" />
          <input name="action" type="hidden" value="reset" />
          <input name="page_options" type="hidden" value="bm_basefontsize,bm_basefontfamily,bm_headingfontfamily,bm_posttextalignment,bm_layoutwidth,bm_uppercolor,bm_lowercolor,bm_headerfontcolor,bm_authorlink,bm_avatarsize" />
        </p>
      </form>
    </div>
  <?php
}
// HTML 片段
function barthelme_donate() { 
  // $form = '<form id="paypal" action="https://www.paypal.com/cgi-bin/webscr" method="post">
  //   <div id="donate">
  //     <input type="hidden" name="cmd" value="_s-xclick" />
  //     <input type="image" name="submit" src="https://www.paypal.com/en_US/i/btn/x-click-butcc-donate.gif" alt="Donate with PayPal - it\'s fast, free and secure!" />
  //     <img src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" alt="Donate with PayPal" />
  //     <input type="hidden" name="encrypted" value="-----BEGIN PKCS7-----MIIHVwYJKoZIhvcNAQcEoIIHSDCCB0QCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYBSmjptafK34xlgxHDtNYGf/8wo8JSsn52q8uMv/t/dsauap8TwjdW6jy8JKPUGCPqFRzKv/BZXgb3j/OS3dS1lED3UtANPVKcj0EIuEL4i3NFZJ7QrlcMWnxQC1mb+4uxYH9ScbgXi27hUIjEV0PwbfU1UbKolOMmE2y8jBoprdzELMAkGBSsOAwIaBQAwgdQGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIbfhBJGGCf0qAgbChXN317gse0DncLOcY+8vIzz8iqR8uCVFzsUFl/FrEHMwKip/Ptg/xBU8/hHQ0lMi3tWzkvyvXevY2qRdTni/ZVWZFTAa3ECOYR6Ionlh5Xe9aran/r7O0o+dysMQ2yMFOA/USGki7caN+sG6LsRW4L/PHtytCQCmMbCqER/y5JAYtUe40wGzr9+OlZLRuDSOjluzhh68yDIRdBUVcRiZwxpj+F+Is8xnHziXBX4/zqaCCA4cwggODMIIC7KADAgECAgEAMA0GCSqGSIb3DQEBBQUAMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTAeFw0wNDAyMTMxMDEzMTVaFw0zNTAyMTMxMDEzMTVaMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbTCBnzANBgkqhkiG9w0BAQEFAAOBjQAwgYkCgYEAwUdO3fxEzEtcnI7ZKZL412XvZPugoni7i7D7prCe0AtaHTc97CYgm7NsAtJyxNLixmhLV8pyIEaiHXWAh8fPKW+R017+EmXrr9EaquPmsVvTywAAE1PMNOKqo2kl4Gxiz9zZqIajOm1fZGWcGS0f5JQ2kBqNbvbg2/Za+GJ/qwUCAwEAAaOB7jCB6zAdBgNVHQ4EFgQUlp98u8ZvF71ZP1LXChvsENZklGswgbsGA1UdIwSBszCBsIAUlp98u8ZvF71ZP1LXChvsENZklGuhgZSkgZEwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tggEAMAwGA1UdEwQFMAMBAf8wDQYJKoZIhvcNAQEFBQADgYEAgV86VpqAWuXvX6Oro4qJ1tYVIT5DgWpE692Ag422H7yRIr/9j/iKG4Thia/Oflx4TdL+IFJBAyPK9v6zZNZtBgPBynXb048hsP16l2vi0k5Q2JKiPDsEfBhGI+HnxLXEaUWAcVfCsQFvd2A1sxRr67ip5y2wwBelUecP3AjJ+YcxggGaMIIBlgIBATCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwCQYFKw4DAhoFAKBdMBgGCSqGSIb3DQEJAzELBgkqhkiG9w0BBwEwHAYJKoZIhvcNAQkFMQ8XDTA4MDMwMzA0MzUzN1owIwYJKoZIhvcNAQkEMRYEFF8E/Iv0zjQjorPPuacBKrh9IHU/MA0GCSqGSIb3DQEBAQUABIGAtjwgH9Ky2C6+BXClnHFGGql6DuD3VbQ6q3iZxXG/3DPS8tvZnzK3N1zMfS50JzFdJO3OBNGnNOflsC/AigCvtX4q2X3JhUXCUm2N2PhENU9ChWlMGAG2u8f8x/kP16fkq2h1GBhEyYrqxsdXgqoVQouZLDEWzJAL+V/LmenB5UY=-----END PKCS7-----" />
  //   </div>
  // </form>' . "\n\t";
  // echo $form;
  echo 'DONATE';
}
// Additional CSS styles for the theme options menu
function barthelme_admin_head() {
?>
  <style type="text/css" media="screen,projection">
  /*<![CDATA[*/
    p.info span{font-weight:bold;}
    label.arial,label.courier,label.georgia,label.lucida-console,label.lucida-unicode,label.tahoma,label.times,label.trebuchet,label.verdana{font-size:1.2em;line-height:175%;}
    .arial{font-family:arial,helvetica,sans-serif;}
    .courier{font-family:'courier new',courier,monospace;}
    .georgia{font-family:georgia,times,serif;}
    .lucida-console{font-family:'lucida console',monaco,monospace;}
    .lucida-unicode{font-family:'lucida sans unicode','lucida grande',sans-serif;}
    .tahoma{font-family:tahoma,geneva,sans-serif;}
    .times{font-family:'times new roman',times,serif;}
    .trebuchet{font-family:'trebuchet ms',helvetica,sans-serif;}
    .verdana{font-family:verdana,geneva,sans-serif;}
    form#paypal{float:right;margin:1em 0 0.5em 1em;}
  /*]]>*/
  </style>
<?php
}
// Loads the admin menu; sets default settings for each
function barthelme_add_admin() {
  if ( isset($_GET['page']) && $_GET['page'] == basename(__FILE__) ) {
    if ( isset($_REQUEST['action']) && 'save' == $_REQUEST['action'] ) {
      check_admin_referer('barthelme_save_options');
      update_option( 'barthelme_authorlink', strip_tags( stripslashes( $_REQUEST['bm_authorlink'] ) ) ); // Option for the author link
      update_option( 'barthelme_basefontfamily', strip_tags( stripslashes( $_REQUEST['bm_basefontfamily'] ) ) ); // Base font family
      update_option( 'barthelme_basefontsize', strip_tags( stripslashes( $_REQUEST['bm_basefontsize'] ) ) ); // Base font size
      update_option( 'barthelme_headerfontcolor', strip_tags( stripslashes( $_REQUEST['bm_headerfontcolor'] ) ) ); // Color for the header text
      update_option( 'barthelme_headingfontfamily', strip_tags( stripslashes( $_REQUEST['bm_headingfontfamily'] ) ) ); // Heading font family
      update_option( 'barthelme_layoutwidth', strip_tags( stripslashes( $_REQUEST['bm_layoutwidth'] ) ) ); // Layout width
      update_option( 'barthelme_lowercolor', strip_tags( stripslashes( $_REQUEST['bm_lowercolor'] ) ) ); // Lower color for the header image gradiant
      update_option( 'barthelme_posttextalignment', strip_tags( stripslashes( $_REQUEST['bm_posttextalignment'] ) ) ); // Post text alignment
      update_option( 'barthelme_uppercolor', strip_tags( stripslashes( $_REQUEST['bm_uppercolor'] ) ) ); // Upper color for the header image gradiant
      update_option( 'barthelme_avatarsize', strip_tags( stripslashes( $_REQUEST['bm_avatarsize'] ) ) ); // Avatar size
      header("Location: themes.php?page=functions.php&saved=true");
      die;
    } elseif ( isset($_REQUEST['action']) && 'reset' == $_REQUEST['action'] ) {
      check_admin_referer('barthelme_reset_options');
      delete_option('barthelme_authorlink');
      delete_option('barthelme_basefontfamily');
      delete_option('barthelme_basefontsize');
      delete_option('barthelme_headerfontcolor');
      delete_option('barthelme_headingfontfamily');
      delete_option('barthelme_layoutwidth');
      delete_option('barthelme_lowercolor');
      delete_option('barthelme_posttextalignment');
      delete_option('barthelme_uppercolor');
      delete_option('barthelme_avatarsize');
      header("Location: themes.php?page=functions.php&reset=true");
      die;
    }
    add_action('admin_head', 'barthelme_admin_head');
  }
  add_theme_page( __( 'Barthelme Theme Options', 'mukti' ), __( 'Theme Options', 'mukti' ), 'edit_themes', basename(__FILE__), 'barthelme_admin' );
}
add_action('admin_menu', 'barthelme_add_admin');
// ---------- ↑ 已捋摆

// Produces links for every page just below the header
function barthelme_globalnav() {
  echo "<div id=\"globalnav\"><ul id=\"menu\">";
  if ( !is_front_page() ) { ?><li class="page_item_home home-link"><a href="<?php bloginfo('url'); ?>/" title="<?php echo _wp_specialchars(bloginfo('name'), 1) ?>" rel="home"><?php _e('Home', 'mukti') ?></a></li><?php }
  $menu = wp_list_pages('title_li=&sort_column=menu_order&echo=0'); // Params for the page list in header.php
  echo str_replace(array("\r", "\n", "\t"), '', $menu);
  echo "</ul></div>\n";
}

// Produces an hCard for the "admin" user
function barthelme_admin_hCard() {
  global $wpdb, $user_info;
  $user_info = get_userdata(1); // TODO: get_userdata
  echo '<span class="vcard"><a class="url fn n" href="' . $user_info->user_url . '"><span class="given-name">' . $user_info->first_name . '</span> <span class="family-name">' . $user_info->last_name . '</span></a></span>';
}

// Produces an hCard for post authors
function barthelme_author_hCard() {
  global $wpdb, $authordata;
  echo '<span class="entry-author author vcard"><a class="url fn n" href="' . get_author_posts_url($authordata->ID, $authordata->user_nicename) . '" title="View all posts by ' . $authordata->display_name . '">' . get_the_author() . '</a></span>';
}

function barthelme_body_class( $print = true ) {
  global $wp_query, $current_user;

  $c = array('wordpress');

  barthelme_date_classes(time(), $c);

  is_home()       ? $c[] = 'home'       : null;
  is_archive()    ? $c[] = 'archive'    : null;
  is_date()       ? $c[] = 'date'       : null;
  is_search()     ? $c[] = 'search'     : null;
  is_paged()      ? $c[] = 'paged'      : null;
  is_attachment() ? $c[] = 'attachment' : null;
  is_404()        ? $c[] = 'four04'     : null;

  if ( is_single() ) {
    the_post();
    $c[] = 'single';
    if ( isset($wp_query->post->post_date) )
      barthelme_date_classes(mysql2date('U', $wp_query->post->post_date), $c, 's-');
    foreach ( (array) get_the_category() as $cat )
      $c[] = 's-category-' . $cat->category_nicename;
      $c[] = 's-author-' . get_the_author_meta('login');
    rewind_posts();
  }

  elseif ( is_author() ) {
    $author = $wp_query->get_queried_object();
    $c[] = 'author';
    $c[] = 'author-' . $author->user_nicename;
  }
  
  elseif ( is_category() ) {
    $cat = $wp_query->get_queried_object();
    $c[] = 'category';
    $c[] = 'category-' . $cat->category_nicename;
  }

  elseif ( is_page() ) {
    the_post();
    $c[] = 'page';
    $c[] = 'page-author-' . get_the_author_meta('login');
    rewind_posts();
  }

  if ( $current_user->ID )
    $c[] = 'loggedin';
    
  $c = join(' ', apply_filters('body_class',  $c));

  return $print ? print($c) : $c;
}

// Produces semantic classes for the each individual post div; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function barthelme_post_class( $print = true ) {
  global $post, $barthelme_post_alt;

  $c = array('hentry', "p$barthelme_post_alt", $post->post_type, $post->post_status);

  $c[] = 'author-' . get_the_author_meta('login');

  if ( is_attachment() )
    $c[] = 'attachment';

  foreach ( (array) get_the_category() as $cat )
    $c[] = 'category-' . $cat->category_nicename;

  barthelme_date_classes(mysql2date('U', $post->post_date), $c);

  if ( ++$barthelme_post_alt % 2 )
    $c[] = 'alt';
    
  $c = join(' ', apply_filters('post_class', $c));

  return $print ? print($c) : $c;
}
$barthelme_post_alt = 1;

// Produces semantic classes for the each individual comment li; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function barthelme_comment_class( $print = true ) {
  global $comment, $post, $barthelme_comment_alt;

  $c = array($comment->comment_type);

  if ( $comment->user_id > 0 ) {
    $user = get_userdata($comment->user_id);

    $c[] = "byuser commentauthor-$user->user_login";

    if ( $comment->user_id === $post->post_author )
      $c[] = 'bypostauthor';
  }

  barthelme_date_classes(mysql2date('U', $comment->comment_date), $c, 'c-');
  if ( ++$barthelme_comment_alt % 2 )
    $c[] = 'alt';

  $c[] = "c$barthelme_comment_alt";

  $c = join(' ', apply_filters('comment_class', $c));

  return $print ? print($c) : $c;
}

// Produces date-based classes for the three functions above; Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function barthelme_date_classes($t, &$c, $p = '') {
  $t = $t + (get_option('gmt_offset') * 3600);
  $c[] = $p . 'y' . gmdate('Y', $t);
  $c[] = $p . 'm' . gmdate('m', $t);
  $c[] = $p . 'd' . gmdate('d', $t);
  $c[] = $p . 'h' . gmdate('h', $t);
}

// Returns other categories except the current one (redundant); Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function barthelme_other_cats($glue) {
  $current_cat = single_cat_title('', false);
  $separator = "\n";
  $cats = explode($separator, get_the_category_list($separator));

  foreach ( $cats as $i => $str ) {
    if ( strstr($str, ">$current_cat<") ) {
      unset($cats[$i]);
      break;
    }
  }

  if ( empty($cats) )
    return false;

  return trim(join($glue, $cats));
}

// Returns other tags except the current one (redundant); Originally from the Sandbox, http://www.plaintxt.org/themes/sandbox/
function barthelme_other_tags($glue) {
  $current_tag = single_tag_title('', '',  false);
  $separator = "\n";
  $tags = explode($separator, get_the_tag_list("", "$separator", ""));

  foreach ( $tags as $i => $str ) {
    if ( strstr($str, ">$current_tag<") ) {
      unset($tags[$i]);
      break;
    }
  }

  if ( empty($tags) )
    return false;

  return trim(join($glue, $tags));
}

// Produces an avatar image with the hCard-compliant photo class
function barthelme_commenter_link() {
  $commenter = get_comment_author_link();
  if ( preg_match( '/<a[^>]* class=[^>]+>/', $commenter ) ) {
    $commenter = preg_replace( '/(<a[^>]* class=[\'"]?)/', '\\1url ' , $commenter );
  } else {
    $commenter = preg_replace( '/(<a )/', '\\1class="url "/' , $commenter );
  }
  $email = get_comment_author_email();
  $avatar_size = get_option('barthelme_avatarsize');
  if ( empty($avatar_size) ) $avatar_size = '32';
  $avatar = str_replace( "class='avatar", "class='photo avatar", get_avatar( "$email", "$avatar_size" ) );
  echo $avatar . ' <span class="fn n">' . $commenter . '</span>';
}

// Function to filter the default gallery shortcode
function barthelme_gallery($attr) {
  global $post;
  if ( isset($attr['orderby']) ) {
    $attr['orderby'] = sanitize_sql_orderby($attr['orderby']);
    if ( !$attr['orderby'] )
      unset($attr['orderby']);
  }

  extract(shortcode_atts( array(
    'orderby'    => 'menu_order ASC, ID ASC',
    'id'         => $post->ID,
    'itemtag'    => 'dl',
    'icontag'    => 'dt',
    'captiontag' => 'dd',
    'columns'    => 3,
    'size'       => 'thumbnail',
  ), $attr ));

  $id           =  intval($id);
  $orderby      =  addslashes($orderby);
  $attachments  =  get_children("post_parent=$id&post_type=attachment&post_mime_type=image&orderby={$orderby}");

  if ( empty($attachments) )
    return null;

  if ( is_feed() ) {
    $output = "\n";
    foreach ( $attachments as $id => $attachment )
      $output .= wp_get_attachment_link( $id, $size, true ) . "\n";
    return $output;
  }

  $listtag     =  tag_escape($listtag);
  $itemtag     =  tag_escape($itemtag);
  $captiontag  =  tag_escape($captiontag);
  $columns     =  intval($columns);
  $itemwidth   =  $columns > 0 ? floor(100/$columns) : 100;

  $output = apply_filters( 'gallery_style', "\n" . '<div class="gallery">', 9 ); // Available filter: gallery_style

  foreach ( $attachments as $id => $attachment ) {
    $img_lnk = get_attachment_link($id);
    $img_src = wp_get_attachment_image_src( $id, $size );
    $img_src = $img_src[0];
    $img_alt = $attachment->post_excerpt;
    if ( $img_alt == null )
      $img_alt = $attachment->post_title;
    $img_rel = apply_filters( 'gallery_img_rel', 'attachment' ); // Available filter: gallery_img_rel
    $img_class = apply_filters( 'gallery_img_class', 'gallery-image' ); // Available filter: gallery_img_class

    $output  .=  "\n\t" . '<' . $itemtag . ' class="gallery-item gallery-columns-' . $columns .'">';
    $output  .=  "\n\t\t" . '<' . $icontag . ' class="gallery-icon"><a href="' . $img_lnk . '" title="' . $img_alt . '" rel="' . $img_rel . '"><img src="' . $img_src . '" alt="' . $img_alt . '" class="' . $img_class . ' attachment-' . $size . '" /></a></' . $icontag . '>';

    if ( $captiontag && trim($attachment->post_excerpt) ) {
      $output .= "\n\t\t" . '<' . $captiontag . ' class="gallery-caption">' . $attachment->post_excerpt . '</' . $captiontag . '>';
    }

    $output .= "\n\t" . '</' . $itemtag . '>';
    if ( $columns > 0 && ++$i % $columns == 0 )
      $output .= "\n</div>\n" . '<div class="gallery">';
  }
  $output .= "\n</div>\n";

  return $output;
}

// Loads a Barthelme-style Search widget
function widget_barthelme_search($args) {
  extract($args);
  $options = get_option('widget_barthelme_search');
  $title = empty($options['title']) ? __( 'Search', 'mukti' ) : $options['title'];
  $button = empty($options['button']) ? __( 'Find', 'mukti' ) : $options['button'];
?>
      <?php echo $before_widget ?>
        <?php echo $before_title ?><label for="s"><?php echo $title ?></label><?php echo $after_title ?>
        <form id="searchform" method="get" action="<?php bloginfo('url') ?>">
          <div>
            <input id="s" name="s" class="text-input" type="text" value="<?php the_search_query() ?>" size="10" tabindex="1" accesskey="S" />
            <input id="searchsubmit" name="searchsubmit" class="submit-button" type="submit" value="<?php echo $button ?>" tabindex="2" />
          </div>
        </form>
      <?php echo $after_widget ?>
<?php
}

// Widget: Search; element controls for customizing text within Widget plugin
function widget_barthelme_search_control() {
  $options = $newoptions = get_option('widget_barthelme_search');
  if ( $_POST['search-submit'] ) {
    $newoptions['title'] = strip_tags( stripslashes( $_POST['search-title'] ) );
    $newoptions['button'] = strip_tags( stripslashes( $_POST['search-button'] ) );
  }
  if ( $options != $newoptions ) {
    $options = $newoptions;
    update_option( 'widget_barthelme_search', $options );
  }
  $title = esc_attr( $options['title'] );
  $button = esc_attr( $options['button'] );
?>
      <p><label for="search-title"><?php _e( 'Title:', 'mukti' ) ?> <input class="widefat" id="search-title" name="search-title" type="text" value="<?php echo $title; ?>" /></label></p>
      <p><label for="search-button"><?php _e( 'Button Text:', 'mukti' ) ?> <input class="widefat" id="search-button" name="search-button" type="text" value="<?php echo $button; ?>" /></label></p>
      <input type="hidden" id="search-submit" name="search-submit" value="1" />
<?php
}

// Loads a Barthelme-style Meta widget
function widget_barthelme_meta($args) {
  extract($args);
  $options = get_option('widget_meta');
  $title = empty($options['title']) ? __( 'Meta', 'mukti' ) : $options['title'];
?>
      <?php echo $before_widget; ?>
        <?php echo $before_title . $title . $after_title; ?>
        <ul>
          <?php wp_register() ?>

          <li><?php wp_loginout() ?></li>
          <?php wp_meta() ?>

        </ul>
      <?php echo $after_widget; ?>
<?php
}

function widget_barthelme_homelink($args) {
  extract($args);
  $options = get_option('widget_barthelme_homelink');
  $title = empty($options['title']) ? __( 'Home', 'mukti' ) : $options['title'];
  if ( !is_front_page() || is_paged() ) {
?>
      <?php echo $before_widget; ?>
        <?php echo $before_title; ?><a href="<?php bloginfo('url'); ?>/" title="<?php echo _wp_specialchars(bloginfo('name'), 1) ?>" rel="home"><?php echo $title; ?></a><?php echo $after_title; ?>
      <?php echo $after_widget; ?>
<?php }
}

// Loads the control functions for the Home Link, allowing control of its text
function widget_barthelme_homelink_control() {
  $options = $newoptions = get_option('widget_barthelme_homelink');
  if ( $_POST['homelink-submit'] ) {
    $newoptions['title'] = strip_tags( stripslashes( $_POST['homelink-title'] ) );
  }
  if ( $options != $newoptions ) {
    $options = $newoptions;
    update_option( 'widget_barthelme_homelink', $options );
  }
  $title = esc_attr( $options['title'] );
?>
      <p><?php _e('Adds a link to the home page on every page <em>except</em> the home.', 'mukti'); ?></p>
      <p><label for="homelink-title"><?php _e( 'Title:', 'mukti' ) ?> <input class="widefat" id="homelink-title" name="homelink-title" type="text" value="<?php echo $title; ?>" /></label></p>
      <input type="hidden" id="homelink-submit" name="homelink-submit" value="1" />
<?php
}

// Loads Barthelme-style RSS Links (separate from Meta) widget
function widget_barthelme_rsslinks($args) {
  extract($args);
  $options = get_option('widget_barthelme_rsslinks');
  $title = empty($options['title']) ? __( 'RSS Links', 'mukti' ) : $options['title'];
?>
    <?php echo $before_widget; ?>
      <?php echo $before_title . $title . $after_title; ?>
      <ul>
        <li><a href="<?php bloginfo('rss2_url') ?>" title="<?php echo _wp_specialchars( bloginfo('name'), 1 ) ?> <?php _e( 'Posts RSS feed', 'mukti' ); ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All posts', 'mukti' ) ?></a></li>
        <li><a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo _wp_specialchars(bloginfo('name'), 1) ?> <?php _e( 'Comments RSS feed', 'mukti' ); ?>" rel="alternate" type="application/rss+xml"><?php _e( 'All comments', 'mukti' ) ?></a></li>
      </ul>
    <?php echo $after_widget; ?>
<?php
}

// Loads the control functions for the RSS Links, allowing control of its text
function widget_barthelme_rsslinks_control() {
  $options = $newoptions = get_option('widget_barthelme_rsslinks');
  if ( $_POST['rsslinks-submit'] ) {
    $newoptions['title'] = strip_tags( stripslashes( $_POST['rsslinks-title'] ) );
  }
  if ( $options != $newoptions ) {
    $options = $newoptions;
    update_option( 'widget_barthelme_rsslinks', $options );
  }
  $title = esc_attr( $options['title'] );
?>
      <p><label for="rsslinks-title"><?php _e( 'Title:', 'mukti' ) ?> <input class="widefat" id="rsslinks-title" name="rsslinks-title" type="text" value="<?php echo $title; ?>" /></label></p>
      <input type="hidden" id="rsslinks-submit" name="rsslinks-submit" value="1" />
<?php
}

// Loads, checks that Widgets are loaded and working
function barthelme_widgets_init() {
  if ( !function_exists('register_sidebars') )
    return;
    // wp_widgets_init();
  // TODO:
  // $p = array(
  //   'before_title' => "<h3 class='widgettitle'>",
  //   'after_title' => "</h3>\n",
  // );

  // register_sidebars(1, $p);

  // Finished intializing Widgets plugin, now let's load the Barthelme default widgets; first, Barthelme search widget
  // $widget_ops = array(
  //   'classname'    =>  'widget_search',
  //   'description'  =>  __( "A search form for your blog (Barthelme)", "barthelme" )
  // );
  // wp_register_sidebar_widget( 'search', __( 'Search', 'mukti' ), 'widget_barthelme_search', $widget_ops );
  // wp_unregister_widget_control('search');
  // wp_register_widget_control( 'search', __( 'Search', 'mukti' ), 'widget_barthelme_search_control' );

  // // Barthelme Meta widget
  // $widget_ops = array(
  //   'classname'    =>  'widget_meta',
  //   'description'  =>  __( "Log in/out and administration links (Barthelme)", "barthelme" )
  // );
  // wp_register_sidebar_widget( 'meta', __( 'Meta', 'mukti' ), 'widget_barthelme_meta', $widget_ops );
  // wp_unregister_widget_control('meta');
  // wp_register_widget_control( 'meta', __('Meta'), 'wp_widget_meta_control' );

  // //Barthelme Home Link widget
  // $widget_ops = array(
  //   'classname'    =>  'widget_home_link',
  //   'description'  =>  __( "Link to the front page when elsewhere (Barthelme)", "barthelme" )
  // );
  // wp_register_sidebar_widget( 'home_link', __( 'Home Link', 'mukti' ), 'widget_barthelme_homelink', $widget_ops );
  // wp_register_widget_control( 'home_link', __( 'Home Link', 'mukti' ), 'widget_barthelme_homelink_control' );

  // //Barthelme RSS Links widget
  // $widget_ops = array(
  //   'classname'    =>  'widget_rss_links',
  //   'description'  =>  __( "RSS links for both posts and comments (Barthelme)", "barthelme" )
  // );
  // wp_register_sidebar_widget( 'rss_links', __( 'RSS Links', 'mukti' ), 'widget_barthelme_rsslinks', $widget_ops );
  // wp_register_widget_control( 'rss_links', __( 'RSS Links', 'mukti' ), 'widget_barthelme_rsslinks_control' );
}

// TODO:
// Loads settings for the theme options to use
function barthelme_wp_head() {
// TODO:
  function barthelme_author_link() { // Option to show the author link, or not
    global $wpdb, $authordata;
    if ( get_option('barthelme_authorlink') == "" ) {
      if ( is_single() || is_page() ) {
        return '<span class="entry-author author vcard"><a class="url fn" href="' . get_author_posts_url($authordata->ID, $authordata->user_nicename) . '" title="View all posts by ' . $authordata->display_name . '">' . get_the_author() . '</a></span>';
      } else {
        echo '<span class="entry-author author vcard"><a class="url fn" href="' . get_author_posts_url($authordata->ID, $authordata->user_nicename) . '" title="View all posts by ' . $authordata->display_name . '">' . get_the_author() . '</a></span>';
      }
    } elseif ( get_option('barthelme_authorlink') =="displayed" ) {
      if ( is_single() || is_page() ) {
        return '<span class="entry-author author vcard"><a class="url fn" href="' . get_author_posts_url($authordata->ID, $authordata->user_nicename) . '" title="View all posts by ' . $authordata->display_name . '">' . get_the_author() . '</a></span>';
      } else {
        echo '<span class="entry-author author vcard"><a class="url fn" href="' . get_author_posts_url($authordata->ID, $authordata->user_nicename) . '" title="View all posts by ' . $authordata->display_name . '">' . get_the_author() . '</a></span>';
      }
    } elseif ( get_option('barthelme_authorlink') =="hidden" ) {
      if ( is_single() || is_page() ) {
        return '<span class="entry-author author vcard"><span class="fn n">' . get_the_author() . '</span></span>';
      } else {
        echo '';
      }
    };
  }
  if ( get_option('barthelme_basefontsize') == "" ) {
    $basefontsize = '75%';
  } else {
    $basefontsize = esc_attr( stripslashes( get_option('barthelme_basefontsize') ) );
  };
  if ( get_option('barthelme_basefontfamily') == "" ) {
    $basefontfamily = 'arial,helvtica,sans-serif';
  } else {
    $basefontfamily = _wp_specialchars( stripslashes( get_option('barthelme_basefontfamily') ) );
  };
  if ( get_option('barthelme_headingfontfamily') == "" ) {
    $headingfontfamily = 'georgia,times,serif';
  } else {
    $headingfontfamily = _wp_specialchars( stripslashes( get_option('barthelme_headingfontfamily') ) );
  };
  if ( get_option('barthelme_layoutwidth') == "" ) {
    $layoutwidth = 'auto';
  } else {
    $layoutwidth = esc_attr( stripslashes( get_option('barthelme_layoutwidth') ) );
  };
  if ( get_option('barthelme_posttextalignment') == "" ) {
    $posttextalignment = 'left';
  } else {
    $posttextalignment = esc_attr( stripslashes( get_option('barthelme_posttextalignment') ) );
  };
  if ( get_option('barthelme_uppercolor') == "" ) {
    $uppercolor = '8a9aae';
  } else {
    $uppercolor = esc_attr( stripslashes( get_option('barthelme_uppercolor') ) );
  };
  if ( get_option('barthelme_lowercolor') == "" ) {
    $lowercolor = 'bac8da';
  } else {
    $lowercolor = esc_attr( stripslashes( get_option('barthelme_lowercolor') ) );
  };
  if ( get_option('barthelme_headerfontcolor') == "" ) {
    $headerfontcolor = 'fefefe';
  } else {
    $headerfontcolor = esc_attr(stripslashes(get_option('barthelme_headerfontcolor')));
  };

  ?>
  <style type="text/css" media="screen,projection">
  /*<![CDATA[*/
  /* CSS inserted by Barthelme theme options */
    body{font-family:<?php echo $basefontfamily; ?>;font-size:<?php echo $basefontsize; ?>;}
    div#content h2,div#content h3,div#content h4,div#content h5,div#content h6,body.archive div.archive-meta,body.attachment div.entry-content div.attachment-content p.attachment-name,body.home div#content div.entry-meta span,body.archive div#content div.entry-meta span,body.search div#content div.entry-meta span,body.single div.entry-date,body.single div.entry-meta,div#content blockquote,div.comments ol.commentlist li div.comment-meta,div.entry-content div.page-link,div.entry-content span.tag-links,body.page div.archive-meta,div.formcontainer form#commentform div.form-input input,div.formcontainer form#commentform div.form-textarea textarea#comment,input#s,div.entry-content div.entry-caption{font-family:<?php echo $headingfontfamily; ?>;}
    body div#content div.hentry{text-align:<?php echo $posttextalignment; ?>;}
    body div#wrapper{width:<?php echo $layoutwidth; ?>;}
    div#header,div#header h1#blog-title a,div#header h1#blog-title a:link,div#header h1#blog-title a:visited{color:#<?php echo $headerfontcolor; ?>;outline:none;text-decoration:none;}
    /* body div#header{background:#<?php echo $lowercolor; ?> url("<?php echo get_template_directory_uri(); ?>/images/header-img.php?upper=<?php echo $uppercolor; ?>&lower=<?php echo $lowercolor; ?>") repeat-x left top;} */
  /*]]>*/
  </style>
  <?php // Checks that everything has loaded properly
}
add_action('wp_head', 'barthelme_wp_head');

add_action('init', 'barthelme_widgets_init');

add_filter('archive_meta', 'wptexturize');
add_filter('archive_meta', 'convert_smilies');
add_filter('archive_meta', 'convert_chars');
add_filter('archive_meta', 'wpautop');

// add_filter('post_gallery', 'barthelme_gallery', $attr);

// Readies for translation.
// load_theme_textdomain( 'mukti', get_template_directory() . '/languages' );
?>