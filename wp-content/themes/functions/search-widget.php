<?php
/*
 * Search form in widget
 */

if (! function_exists('otm_theme_search_form')) {
    function otm_theme_search_form($form)
    {
        $form = '<div class="input-wrapper relative">
              <form class="form-inline search-form d-flex position-relative" role="search" method="get" action="' . home_url('/') . '" >
                <i class="fas fa-search position-absolute text-secondary form-icon"></i>
                <input class="form-control" type="text" value="' . get_search_query() . '" placeholder="' . esc_attr_x('Search', 'otm_theme') . '..." name="s" id="s" />
                <button type="submit" id="searchsubmit" value="'. esc_attr_x('Search', 'otm_theme') .'" class="search-btn postion-absolute"><i class="fas fa-check"></i></button>
              </form>
            </div>';

        return $form;
    }
}
add_filter('get_search_form', 'otm_theme_search_form');
