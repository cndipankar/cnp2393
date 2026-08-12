<?php
/*
All the functions are in the PHP files in the `functions/` folder.
*/

if (! defined('ABSPATH')) {
    exit;
}

require get_template_directory() . '/functions/cleanup.php';
require get_template_directory() . '/functions/setup.php';
require get_template_directory() . '/functions/enqueues.php';
require get_template_directory() . '/functions/navbar.php';
require get_template_directory() . '/functions/widgets.php';
require get_template_directory() . '/functions/search-widget.php';
require get_template_directory() . '/functions/index-pagination.php';
require get_template_directory() . '/functions/single-split-pagination.php';
require get_template_directory() . '/functions/otm-theme.php';
require get_template_directory() . '/functions/schema-generator.php';
require get_template_directory() . '/functions/register-blocks.php';
require get_template_directory() . '/functions/register-block-categories.php';
require get_template_directory() . '/functions/user-registration.php';

// Enqueue Login Styles
function login_css() {
    wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/assets/css/login-style.css' );
}
add_action( 'login_enqueue_scripts', 'login_css' );

// Enqueue Admin Styles
function enqueue_admin_style() {
    add_theme_support('editor-styles');
    add_theme_support('align-wide');
    add_editor_style('assets/css/main.css');
}
add_action('after_setup_theme', 'enqueue_admin_style');

// Mega Menu Additional Font Weights
function megamenu_add_font_weights($weights) {
    $weights['100'] = "Thin (100)";
    $weights['200'] = "Extra Light (200)";
    $weights['500'] = "Medium (500)";
    $weights['600'] = "Semi Bold (600)";
    return $weights;
}
add_filter('megamenu_font_weights', 'megamenu_add_font_weights');

// Hide Admin Bar for Non-admins
function remove_admin_bar() {
    if (!current_user_can('administrator') && !is_admin()) {
        show_admin_bar(false);
    }
}
add_action('after_setup_theme', 'remove_admin_bar');