<!-- Mukti -->
<form id="main-search-form" method="get" action="<?php bloginfo('url') ?>">
  <div>
    <input id="main-search-input" name="s" type="text" value="<?php the_search_query() ?>" />
    <input id="main-search-submit" name="searchsubmit" type="submit" value="<?php _e('Search', 'mukti') ?>" />
  </div>
</form>