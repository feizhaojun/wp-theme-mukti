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
  </div>
</div>
<?php wp_footer() // Do not remove; helps plugins work ?>

<!-- 返回顶部 -->
<a href="javascript:;" class="back-top">
  ▲<br />
  TOP
</a>

<script type="text/javascript">
// 回到顶部 - Start
$('.back-top').on('click',function(){
  $('html,body').animate({
    'scrollTop': 0
  }, 200)
})
function showBackTop(t){
  var t = $('html,body').scrollTop()
  if(t > 100){
    $('.back-top').css({'display':'block'})
  }else{
    $('.back-top').css({'display':'none'})
  }
}
showBackTop()
$(window).on('scroll', showBackTop)
// 回到顶部 - End

// 右侧菜单在新窗口打开
$('#right-menu a').attr({
  target: '_blank'
})

// 在新窗口打开外链
$('a').each(function (el, i) {
  if ($(this).attr('href').indexOf('feizhaojun.com') < 0 && $(this).attr('href').indexOf('javascript:;') < 0 && $(this).attr('href').indexOf('#') !== 0) {
    $(this).attr({target: '_blank'})
  }
})

// 折叠图片 TODO:
// $('.fold-image-tag').parent().find('img').each(function (i, el) {
//   var id = 'image-' + $(this).parents('.post').attr('id') + '-' + i
//   $(this).attr({id: id}).after('<a href="javascript:;" class="show-image-tag" data-id="' + id + '" data-width="' + $(this).width() + '">查看图片</a>').hide().css({width: 0})
// })
// $('.show-image-tag').on('click', function () {
//   $(this).hide()
//   $('#' + $(this).attr('data-id')).show().animate({width: $(this).attr('data-width')}, 200)
// })
// $('.fold-image-tag').parent().find('img').on('click', function () {
//   $(this).animate({
//     width: 0
//   }, 200, function () {
//     $(this).hide()
//   })
//   $(this).next('.show-image-tag').show()
// })

// 锚点定位 TODO:
// focusAnchor()
// $(window).on('hashchange', focusAnchor)
// function focusAnchor () {
//   if (!window.location.href.match(/.*#(.*)$/)) {
//     return
//   }
//   var locatId = window.location.href.match(/.*#(.*)$/)[1]
//   if ($('#' + locatId).length) {
//     $('html,body').animate({
//       'scrollTop': $('#' + locatId).offset().top - 30
//     }, 200)
//   }
// }

// 百度统计 TODO:
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?91c7bd060d2c86b8d6d5994edb756abe";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
</body>
</html>