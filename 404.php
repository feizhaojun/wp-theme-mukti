<!-- Mukti -->
<?php header("HTTP/1.1 404 Not Found"); ?>
<?php get_header() ?>

<div id="main" class="main">
  <div id="post-0" class="entry">
		<h2 class="entry-title"><?php _e('Not Found') ?></h2>
		<!-- TODO: -->
		<div class="entry-content" style="font-family: PingfangSC-light, sans-serif;font-size: 14px;">
			<p><?php _e('Apologies, but we were unable to find what you were looking for. Perhaps the search box will help.', 'mukti') ?></p>
			<p><?php _e('抱歉, 页面不存在。您可以尝试搜索一些内容。', 'mukti') ?></p>
		</div>
		<?php get_template_part('templates/search',''); ?>
	</div>
</div>

<?php get_footer() ?>