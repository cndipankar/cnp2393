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


function croci_frontend_enqueue(){
    wp_enqueue_style( 'editor-style', get_template_directory_uri().'/assets/css/editor-style.css', array(), '1.0' );   
}
add_action( 'wp_enqueue_scripts', 'croci_frontend_enqueue' );

function croci_login_redirect($user_login, $user) {
    
    if( in_array('administrator', $user->roles) ) { 
        add_filter('login_redirect', function (){
            return esc_url(home_url('/wp-admin/'));
        });
    }else if( in_array('subscriber', $user->roles) ) { 
        add_filter('login_redirect', function (){
            return esc_url(home_url('/downloads/'));
        });
    }
}
add_action( 'wp_login', 'croci_login_redirect', 10, 2 );


function croci_shortcode_navigation_bar_callback( $atts ) {
    $attributes = shortcode_atts( array(
        'title' => '',        
    ), $atts );
    
    $html .='';

    if(is_user_logged_in()){
        $html .='<div class="tab_navigation_bar">
            <a href="'.esc_url(home_url('my-account/bulkorder')).'">Order Form</a>
            <a href="#brochures">Brochures</a>
            <a href="#forms">Forms</a>
            <a href="#technical_catalogs">Technical Catalogs</a>
            <a href="#warranty_certificate">Warranty Certificate</a>
            <a href="#fbc_approvals">FBC Approvals</a>
            <a href="#miami-dade_approvals">Miami-Dade Approvals</a>
            <a href="#tdi_approvals">TDI Approvals</a>
        </div>';
    }
    return $html; 
}
add_shortcode( 'navigation_bar', 'croci_shortcode_navigation_bar_callback' );


function croci_page_redirect( ) {
    global $wp;
    if($wp->request == 'products'){
        wp_redirect(home_url() );
        exit;
    }
} 
add_action( 'wp', 'croci_page_redirect' );


