<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * For miscellaneous modifications work
 */
class Miscellaneous_Modifications {

    public function __construct() {        
        //Hide menus from WooCommerce My Account Dashboard
        add_filter( 'woocommerce_account_menu_items', array( $this, 'hide_woocommerce_user_dashboard_menus' ) );    
        
        // Remove price columns and totals from WooCommerce admin order details page
        add_action( 'admin_head', array( $this, 'remove_price_text_from_admin_order_page' ) );      

        // Add Admin-only WooCommerce Dashboard setting
        add_action( 'admin_init', array( $this, 'add_admin_setting_for_user_restriction' ) ); 

        // Restrict WooCommerce My Account pages to Admin only
        add_action( 'template_redirect', array( $this, 'restrict_woocommerce_account_pages_admin_only' ) ); 

        // Restrict Order section for dashborad pages to Admin only
        add_filter('body_class', array( $this, 'add_admin_body_class' ) ); 
        add_action('wp_head', array( $this, 'admin_only_quote_section_css' ) ); 

        // Add link for Order Form page
        add_action('woocommerce_order_details_after_customer_details', array( $this, 'add_link_for_order_form' ) ); 
        add_action('woocommerce_after_cart_totals', array( $this, 'add_link_for_order_form' ) ); 

        // Redirect WooCommerce shop page and product pages to bulk orddr page
        add_action( 'template_redirect', array( $this, 'redirect_shop_and_product_to_bulk' ) ); 

        add_filter( 'woocommerce_order_button_text', array( $this, 'custom_quote_button_text' ) );   

        add_filter( 'init', array( $this, 'remove_checkout_order_review' ) );   
    }

    public function remove_checkout_order_review() {
        remove_action(
            'woocommerce_checkout_order_review',
            'woocommerce_checkout_payment',
            20
        );
    }

    public function custom_quote_button_text() {
        return 'Submit Quote Request';
    }


    public function redirect_shop_and_product_to_bulk() {
        if (is_shop() || is_product()) {
            wp_redirect(home_url('/my-account/bulkorder/')); 
            exit;
        }
    }


    /**
     * Add link for Order Form page
     */    
    public function add_link_for_order_form() {
         echo '<div class="back_to_order_form_link" style="margin:20px;">
            <a href="'.esc_url(home_url('/my-account/bulkorder/')).'" class="button">← Back to Order Form</a>
          </div>';
    }


    /**
     * Hide menus from WooCommerce My Account Dashboard
     */    
    public function hide_woocommerce_user_dashboard_menus($items) {
        unset($items['downloads']); // remove the Downloads tab
        return $items;
    }


    /**
     * Remove price columns and totals from WooCommerce admin order details page
     */    
    public function remove_price_text_from_admin_order_page() {
        $screen = get_current_screen();

        // Only run on WooCommerce order edit page
        if (isset($screen->id) && $screen->id === 'woocommerce_page_wc-orders') {
            echo '<style>
                /* Hide Price column */
                #woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items tbody#order_line_items tr td.item_cost {
                  display: none;
                }
                #woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items tbody#order_line_items tr td.line_cost {
                  display: none;
                }

                /* Hide table column names */
                #woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items thead th.item_cost {
                  display: none;
                }
                #woocommerce-order-items .woocommerce_order_items_wrapper table.woocommerce_order_items thead th.line_cost {
                  display: none;
                }

                /* Hide line item prices */
                .wc-order-item-total,
                .wc-order-edit-line-item .wc-order-item-meta,
                .wc-order-totals-items td,
                .wc-order-totals-items th,
                .wc-order-totals-items .wc-order-totals,
                .wc-order-totals-items .display_meta,
                .wc-order-totals-items .wc-order-item-total {
                    display: none !important;
                }

                /* Hide order total box */
                #order_data .order_data_column ._order_total_field,
                #order_data .order_data_column ._order_subtotal_field,
                #order_data .order_data_column ._order_shipping_field,
                #order_data .order_data_column ._order_tax_field {
                    display: none !important;
                }

                /* Hide total summary box */
                .wc-order-totals-items {
                    display: none !important;
                }
            </style>';
        }
    }


    /**
     * Add Admin-only WooCommerce Dashboard setting
     */
    public function add_admin_setting_for_user_restriction(){

        register_setting(
            'general',
            'wc_admin_only_dashboard',
            [
                'type'              => 'boolean',
                'sanitize_callback' => 'rest_sanitize_boolean',
                'default'           => false,
            ]
        );

        add_settings_field(
            'wc_admin_only_dashboard',
            'WooCommerce Dashboard (Admin Only)',
            function () {
                $value = get_option('wc_admin_only_dashboard', false);
                ?>
                <label>
                    <input type="checkbox" name="wc_admin_only_dashboard" value="1" <?php checked(1, $value); ?>>
                    Enable admin-only access for WooCommerce My Account dashboard
                </label>
                <p class="description">
                    When enabled, only Administrators can access WooCommerce account/dashboard pages.
                </p>
                <?php
            },
            'general'
        );
    }


    /**
     * Restrict WooCommerce My Account pages to Admin only
     */
    public function restrict_woocommerce_account_pages_admin_only() {

        // Check if toggle is ON
        if (!get_option('wc_admin_only_dashboard')) {
            return;
        }

        // Must be WooCommerce My Account page
        if (!function_exists('is_account_page') || !is_account_page()) {
            return;
        }

        // Allow admin
        if (current_user_can('administrator')) {
            return;
        }

        // Not logged in → redirect to login
        if (!is_user_logged_in()) {
            wp_redirect(wp_login_url(get_permalink()));
            exit;
        }

        // Logged in but not admin → block
        wp_redirect(home_url('/'));
        exit;
    }


    /**
     * Restrict Order section for dashborad pages to Admin only
     */
    public function add_admin_body_class($classes) {
        if (is_user_logged_in() && current_user_can('administrator')) {
            $classes[] = 'admin-logged-in';
        }
        return $classes;
    }
    
    public function admin_only_quote_section_css() {    
        echo '<style>
        
        /* hide by default for everyone */
        .quote_request_section{
            display:none !important;
        }

        /* show only if admin logged in */
        body.admin-logged-in .quote_request_section{
            display:block !important;
        }

        /* Hide first Order menu on dashboard page */
        .page-template-catelog .tab_navigation_bar a:first-child {
          display: none;
        }
        
        </style>';
    }


}

// Initialize attributes class
new Miscellaneous_Modifications();