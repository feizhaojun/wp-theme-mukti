  </div><!-- div#content -->
</div><!-- div#container -->
<?php get_sidebar() ?>
<div id="footer">
  <div>
    <span id="copyright">
      &copy; 2006-<?php echo(date('Y')); ?>
      <?php barthelme_admin_hCard(); ?>
      <a href="https://feizhaojun.com">Mukti</a>
      版权所有
    </span>
    <span class="meta-sep">|</span>
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-left') ) : // Begin Widgets; displays widgets or default contents below ?>
      <!-- TODO: -->
      <!-- <span>您可以在“后台→外观→小工具”配置此处文本</span> -->
		<?php endif; // End Widgets ?>
  </div>
  <div>
    <span id="feed-links">
      <?php _e('RSS Feeds', 'mukti') ?>
      <a href="<?php bloginfo('rss2_url') ?>" title="<?php echo _wp_specialchars(get_bloginfo('name'), 1) ?> <?php _e('RSS 2.0 Feed', 'mukti') ?>" rel="alternate" type="application/rss+xml"><?php _e('Posts', 'mukti') ?></a>
      <span class="meta-sep">|</span>
      <a href="<?php bloginfo('comments_rss2_url') ?>" title="<?php echo _wp_specialchars(bloginfo('name'), 1) ?> Comments RSS 2.0 Feed" rel="alternate" type="application/rss+xml"><?php _e('Comments', 'mukti') ?></a>
      <!-- <span class="meta-sep">|</span>
      <a href="https://github.com/feizhaojun/wp-theme-mukti" target="_blank">获取本站主题</a> -->
    </span>
		<?php if (!function_exists('dynamic_sidebar') || !dynamic_sidebar('footer-right') ) : // Begin Widgets; displays widgets or default contents below ?>
      <!-- TODO: -->
      <!-- <span>您可以在“后台→外观→小工具”配置此处文本</span> -->
		<?php endif; // End Widgets ?>
    <!-- 设置的统计代码 -->
    <?php echo get_option('mukti_tj_code'); ?>
  </div>
</div>
<?php wp_footer() // Do not remove; helps plugins work ?>

<!-- 返回顶部 -->
<a href="javascript:;" class="back-top">
  ▲<br />
  TOP
</a>

<script type="text/javascript">
// 链接点击拦截
if (window.location.host.indexOf('zaodianying.com') >= 0) {
  jQuery('.entry-content a').on('click', function (e) {
    var target_title = jQuery(this).text()
    var target_url = jQuery(this).attr('href')
    LA.track('links_click', {target_title: target_title, target_url: target_url});
  })
}
// 回到顶部 - Start
jQuery('.back-top').on('click',function(){
  jQuery('html,body').animate({
    'scrollTop': 0
  }, 200)
})
function showBackTop(t){
  var t = jQuery('html,body').scrollTop()
  if(t > 100){
    jQuery('.back-top').css({'display':'block'})
  }else{
    jQuery('.back-top').css({'display':'none'})
  }
}
showBackTop()
jQuery(window).on('scroll', showBackTop)
// 回到顶部 - End

// // 右侧菜单在新窗口打开
// jQuery('#right-menu a').attr({
//   target: '_blank'
// })

// 在新窗口打开外链
jQuery('a').each(function (el, i) {
  if (jQuery(this).attr('href').indexOf('feizhaojun.com') < 0 && jQuery(this).attr('href').indexOf('zaodianying.com') < 0 && jQuery(this).attr('href').indexOf('javascript:;') < 0 && jQuery(this).attr('href').indexOf('#') !== 0) {
    jQuery(this).attr({target: '_blank'})
  }
})

// 折叠图片 TODO:
// jQuery('.fold-image-tag').parent().find('img').each(function (i, el) {
//   var id = 'image-' + jQuery(this).parents('.post').attr('id') + '-' + i
//   jQuery(this).attr({id: id}).after('<a href="javascript:;" class="show-image-tag" data-id="' + id + '" data-width="' + jQuery(this).width() + '">查看图片</a>').hide().css({width: 0})
// })
// jQuery('.show-image-tag').on('click', function () {
//   jQuery(this).hide()
//   jQuery('#' + jQuery(this).attr('data-id')).show().animate({width: jQuery(this).attr('data-width')}, 200)
// })
// jQuery('.fold-image-tag').parent().find('img').on('click', function () {
//   jQuery(this).animate({
//     width: 0
//   }, 200, function () {
//     jQuery(this).hide()
//   })
//   jQuery(this).next('.show-image-tag').show()
// })

// 锚点定位 TODO:
// focusAnchor()
// jQuery(window).on('hashchange', focusAnchor)
// function focusAnchor () {
//   if (!window.location.href.match(/.*#(.*)jQuery/)) {
//     return
//   }
//   var locatId = window.location.href.match(/.*#(.*)jQuery/)[1]
//   if (jQuery('#' + locatId).length) {
//     jQuery('html,body').animate({
//       'scrollTop': jQuery('#' + locatId).offset().top - 30
//     }, 200)
//   }
// }
</script>
</body>
</html>