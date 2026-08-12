<?php
/*
Plugin Name: Custom B2B Feature (B2BKing Extension)
Description: Overrides or extends the B2BKing bulk order shortcode output with custom functionality or styles.
Version: 1.0
Author: Barun Bhaumik
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Custom_B2B_Feature {

    public function __construct() {       

        // Hook to load frontend styles
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ] );

        // Include all class
        $this->load_dependencies();

        // Initialize attributes class
        new Custom_Product_Attributes();

        // Initialize overrite shortcode class
        new Override_Bulkorder_Shortcode();
    }

    public function load_dependencies() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-custom-product-attributes.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-override-b2bking-bulkorder-shortcode.php';
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-miscellaneous-modifications.php';
    }  

    public function enqueue_styles() {
        // Plugin CSS file path
        wp_enqueue_style(
            'my-plugin-style', 
            plugin_dir_url( __FILE__ ) . 'assets/css/custom-b2b-style.css', 
            array(), 
            '1.0.0', 
            'all' 
        );

        wp_enqueue_script( 'custom-b2b-jquery', plugin_dir_url(__FILE__) . 'assets/js/custom-b2b-jquery.js', array( 'jquery' ), $b2b_plugin_version, true );
    } 
    
    
}

new Custom_B2B_Feature();