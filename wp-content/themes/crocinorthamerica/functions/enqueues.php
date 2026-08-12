<?php
/*
 * Enqueues
 */

if (! function_exists('otm_theme_enqueues')) {
    function otm_theme_enqueues()
    {

        // Styles

        wp_register_style('swiper', get_template_directory_uri() . '/assets/css/swiper.min.css', false, '4.5.0');
        wp_enqueue_style('swiper');

        wp_register_style('main', get_template_directory_uri() . '/assets/css/main.min.css', false, null);
        wp_enqueue_style('main');

        wp_register_style('lightbox', get_template_directory_uri() . '/assets/css/lightbox.min.css', false, null);
        wp_enqueue_style('lightbox');
        
        wp_register_style('fontawesome_style', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css', false, null);
        wp_enqueue_style('fontawesome_style');
        // Scripts

        //wp_enqueue_script('jquery');

        wp_register_script('jquery-js', get_template_directory_uri() . '/assets/js/jquery.min.js', false, '1.12.4', true);
        wp_enqueue_script('jquery-js');

        wp_register_script('bootstrap-bundle', get_template_directory_uri() . '/assets/js/bootstrap.bundle.min.js', false, '4.1.3', true);
        wp_enqueue_script('bootstrap-bundle');

        wp_register_script('modernizr', get_template_directory_uri() . '/assets/js/modernizr.js', false, null, true);
        wp_enqueue_script('modernizr');

        wp_register_script('swiper', get_template_directory_uri() . '/assets/js/swiper.min.js', false, '4.5.0', true);
        wp_enqueue_script('swiper');

        wp_register_script('OTMForms', 'https://d3h66sfd9htnrp.cloudfront.net/otm-forms.min.js', false, null, true);
        wp_enqueue_script('OTMForms');

        wp_register_script('scripts', get_template_directory_uri() . '/assets/js/scripts.js', false, null, true);
        wp_enqueue_script('scripts');

        wp_register_script('lightbox', get_template_directory_uri() . '/assets/js/lightbox.min.js', false, null, true);
        wp_enqueue_script('lightbox');

        wp_register_script('pagination', get_template_directory_uri() . '/assets/js/pagination.min.js', false, '2.1.5', true);
        wp_enqueue_script('pagination');

        wp_register_script( 'fontawesome', 'https://use.fontawesome.com/releases/v6.4.0/js/all.js', false, null, true );
        wp_enqueue_script('fontawesome');

        if (is_singular() && comments_open() && get_option('thread_comments')) {
            wp_enqueue_script('comment-reply');
        }
    }
}
add_action('wp_enqueue_scripts', 'otm_theme_enqueues', 100);
