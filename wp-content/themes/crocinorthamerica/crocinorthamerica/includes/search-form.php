<div class="input-wrapper position-relative">
  <form class="form-inline search-form d-flex" role="search" method="get" action="<?php echo home_url('/') ?>" >
    <i class="fas fa-search position-absolute text-secondary form-icon"></i>
    <input class="form-control" type="text" value="<?php get_search_query() ?>" placeholder="<?php echo  esc_attr_x('Search', 'otm_theme'); ?>... " name="s" id="s" />
    <button type="submit" id="searchsubmit" value="<?php esc_attr_x('Search', 'otm_theme') ?>" class="search-btn position-absolute"><i class="fas fa-check"></i></button>
  </form>
</div>
