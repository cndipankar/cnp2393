<?php
if ( ! defined( 'ABSPATH' ) ) exit;


/**
 * Main Class: Custom Product Attributes + Payment Disable Logic + Hide price
 */
class Custom_Product_Attributes {

    public function __construct() {
        // Admin meta boxes
        add_action( 'woocommerce_product_options_general_product_data', array( $this, 'add_custom_product_fields' ) );
        add_action( 'woocommerce_process_product_meta', array( $this, 'save_custom_product_fields' ) );

        // Frontend fields
        add_action( 'woocommerce_before_add_to_cart_button', array( $this, 'display_custom_fields_frontend' ) );

        // Cart / Checkout
        //add_filter( 'woocommerce_add_cart_item_data', array( $this, 'add_cart_item_data' ), 10, 3 );
        //add_filter( 'woocommerce_get_item_data', array( $this, 'display_cart_item_data' ), 10, 2 );
        //add_action( 'woocommerce_add_order_item_meta', array( $this, 'add_order_item_meta' ), 10, 3 );

        // Hide all prices globally
        add_filter( 'woocommerce_get_price_html', array( $this, 'hide_all_prices' ) );
        add_filter( 'woocommerce_cart_item_price', array( $this, 'hide_all_prices' ) );
        add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'hide_all_prices' ) );
        add_filter( 'woocommerce_order_item_subtotal', array( $this, 'hide_all_prices' ) );
        add_filter( 'woocommerce_order_formatted_line_subtotal', array( $this, 'hide_all_prices' ) );
        //add_filter( 'woocommerce_get_price', array( $this, 'hide_all_prices' ) );
        add_filter('woocommerce_is_purchasable', '__return_true', 999);


        // Remove price columns from cart and checkout  
        add_filter( 'woocommerce_cart_totals_order_total_html', array( $this, 'hide_all_prices' ) );
        add_filter( 'woocommerce_cart_totals_subtotal_html', array( $this, 'hide_all_prices' ) );
        add_filter( 'woocommerce_get_formatted_order_total', array( $this, 'hide_all_prices' ) );        

        // Add dummy prices internally (so add-to-cart still works)
        add_filter( 'woocommerce_product_get_price', array( $this, 'set_dummy_price' ), 10, 2 );
        add_filter( 'woocommerce_product_get_regular_price', array( $this, 'set_dummy_price' ), 10, 2 );
    
        // For Gutenberg Block Cart & Checkout (modern)        
        add_action('wp_enqueue_scripts', [$this, 'hide_totals_for_blocks'], 20);
        add_filter( 'woocommerce_store_api_cart_response', array( $this, 'remove_totals_from_store_api' ), 9999, 1 );  

        // Hide "no payment methods" message for Checkout Block
        add_action('wp_enqueue_scripts', array($this, 'hide_checkout_block_message'));

        // Allow checkout for zero total orders (no payment)
        add_action('template_redirect', array($this, 'allow_checkout_without_payment'));

        // Initialize Dummy Payment Gateways for checkout
        add_action( 'plugins_loaded', array( $this, 'register_dummy_payment_gateway' ) );
        add_action( 'woocommerce_blocks_loaded', array( $this, 'register_blocks_dummy_gateway' ) );
        
        // Block-based checkout (Store API)        
        add_action('woocommerce_store_api_checkout_order_processed', array($this, 'process_zero_payment_order'), 10, 1);
            
        // Safely ensure billing + payment info exists for Block Checkout without overwriting valid data.
        add_filter('rest_pre_dispatch', array($this, 'force_dummy_checkout_request'), 10, 3);

        // Hook into WooCommerce REST response for checkout
        add_filter('rest_request_after_callbacks', array($this, 'handle_no_payment_checkout'), 10, 3);

        // Inject dummy payment data for block checkout (Store API)        
        add_filter('woocommerce_store_api_checkout_request', array($this, 'patch_block_checkout_request'), 5);

        // Auto-complete zero total orders
        add_action('woocommerce_checkout_order_processed', array($this, 'auto_complete_zero_total_order'), 10, 3);

        // Change "Add to cart" button text globally        
        add_action( 'woocommerce_product_add_to_cart_text', array( $this, 'custom_add_to_cart_text' ) );
        add_action( 'woocommerce_product_single_add_to_cart_text', array( $this, 'custom_add_to_cart_text' ) );

        // Change button text + link
        add_filter( 'woocommerce_loop_add_to_cart_link', array( $this, 'custom_add_to_order_button' ), 10, 2 );

        //Hide price from email template on order placing
        add_filter( 'woocommerce_get_order_item_totals', array( $this, 'hide_price_from_order_email_template' ), 9999, 3 );



        /* //enable this code to empty the cart
        add_action('init', function() {
            if (isset($_GET['clear_cart'])) {
                WC()->cart->empty_cart();
                wc_add_notice('Cart cleared!', 'success');
            }
        });*/


        // ----------- Cart/Checkout flow enhancement code START here   -----------------------

        // Generate Edit form for cart item on cart page
        add_filter( 'woocommerce_after_cart_item_name', array( $this, 'cpa_cart_edit_form' ), 10,2 );

        // Update the cart item attributes values on cart page
        add_action( 'wp_ajax_cpa_update_cart_item', array( $this, 'cpa_update_cart_item' ) );
        add_action( 'wp_ajax_nopriv_cpa_update_cart_item', array( $this, 'cpa_update_cart_item' ) );
        
        // Display product attributes as preview on cart and checkout page
        add_filter('woocommerce_get_item_data', [$this,'cpa_fix_checkout_labels'], 20, 2);

        add_filter('woocommerce_cart_item_get_formatted_meta_data', '__return_empty_array', 10, 2);

        // Rectify the attribute label that WooCommerce displays, because WooCommerce normally converts attribute names into slug format
        //add_filter('woocommerce_attribute_label', [$this,'cpa_fix_attribute_labels'], 20, 2);

        // Display Product image and short description under Prodcut on checkout page
        add_filter('woocommerce_checkout_cart_item_quantity', [$this,'cpa_checkout_product_extra_info'], 10, 3);

        // Store custom product form fields into the WooCommerce cart item when the product is added to the cart
        //add_filter('woocommerce_add_cart_item_data', [$this,'cpa_store_cart_attributes'], 10, 2);

        // Restore custom cart item data (cpa_fields) when WooCommerce loads the cart from the session
        //add_filter('woocommerce_get_cart_item_from_session', [$this,'cpa_restore_cart_attributes'], 10, 2);

        // ----------- Cart/Checkout flow enhancement code END here   -----------------------
    }


    // ----------- Cart/Checkout flow enhancement code START here   -----------------------

    public function cpa_restore_cart_attributes($cart_item, $values){

        if(isset($values['cpa_fields'])){
            $cart_item['cpa_fields'] = $values['cpa_fields'];
        }

        return $cart_item;
    }

    public function cpa_store_cart_attributes($cart_item_data, $product_id){

        if(empty($_POST['cpa_fields'])){
            return $cart_item_data;
        }

        $fields = $_POST['cpa_fields'];
        $cart_item_data['cpa_fields'] = [];

        foreach($fields as $label => $value){

            $cart_item_data['cpa_fields'][] = [
                'label' => sanitize_text_field($label),
                'value' => sanitize_text_field($value)
            ];

        }

        return $cart_item_data;
    }
    
    public function cpa_checkout_product_extra_info($quantity_html, $cart_item, $cart_item_key){

        $product = $cart_item['data'];

        if(!$product){
            return $quantity_html;
        }

        $image = $product->get_image([60,60]); // product thumbnail
        $short_desc = $product->get_short_description();

        $extra = '<div class="cpa-checkout-extra" style="display:flex;gap:10px;margin-top:6px;">';

        $extra .= '<div class="cpa-thumb">'.$image.'</div>';

        if(!empty($short_desc)){
            $extra .= '<div class="cpa-desc" style="font-size:12px;color:#666;">'.$short_desc.'</div>';
        }

        $extra .= '</div>';

        return $quantity_html . $extra;
    }

    public function cpa_fix_attribute_labels($label, $name){

        global $product;

        if(!$product) return $label;

        $attributes = $product->get_meta('_cpa_custom_attributes');

        if(empty($attributes)) return $label;

        $slug = sanitize_title(str_replace('attribute_', '', $name));

        foreach($attributes as $attr){

            if(sanitize_title($attr['label']) === $slug){
                return $attr['label']; // return original label
            }

        }

        return $label;
    }


    public function cpa_fix_checkout_labels($item_data, $cart_item){ 
        //print_r($cart_item);
        
        // If attributes already exist, don't rebuild
        if(!empty($item_data)){
            return $item_data;
        }

        if(empty($cart_item['variation']) || empty($cart_item['data'])){
            return $item_data;
        }

        $item_data = [];      

        /* remove attributes already added by this plugin */
        foreach($item_data as $index => $data){

            if(isset($data['key']) && sanitize_title($data['key']) !== ''){
                unset($item_data[$index]);
            }

        }

        $product = $cart_item['data'];
        $attributes = $product->get_meta('_cpa_custom_attributes');
        //echo '<pre>'; print_r($attributes);
        foreach($cart_item['variation'] as $key => $value){

            if(strpos($key, 'attribute_') === false){
                continue;
            }

            $label_key = str_replace('attribute_', '', $key);

            $label = str_replace('-', ' ', $label_key);
            $label = ucwords($label);

            $display_value = $value;
            //echo $key;
            if(!empty($attributes)){ 
                foreach($attributes as $attr){ 

                    if(sanitize_title($attr['label']) === sanitize_title($label_key)){
                        $required = ($attr['required'] == 1)?'*': '';
                        $label = $label.$required;


                        if($attr['type'] === 'select' && !empty($attr['options'])){

                            foreach($attr['options'] as $option){                                
                                
                                if($option['value'] == $value){
                                    $display_value = $option['label'];
                                    
                                    break;
                                }

                            }

                        }

                    }

                }
            }
            
            if($value === '') continue;

            $item_data[] = [
                'key'   => $label,
                'value' => $display_value
            ];
        }

        /* ADD EDIT BUTTON ONLY ON CART PAGE */
        if(is_cart()){

            $item_data[] = [
                'key' => 'Action',
                'value' => '<button type="button" class="cpa-edit-btn" data-key="'.$cart_item['key'].'">Edit</button>'
            ];

        }    

        return $item_data;        
    }


    public function cpa_cart_edit_form($cart_item, $cart_item_key){ 

        $product = $cart_item['data'];
        $attributes = $product->get_meta('_cpa_custom_attributes');

        if(empty($attributes)) return;

        //$custom_attributes = get_post_meta( $cart_item['product_id'], '_cpa_custom_attributes', true );        
        $attribute_conditions = [];

        foreach ( $attributes as $attr ) {
            $slug = strtolower(trim(preg_replace('/\s+/', '_', $attr['label']))); 
            $attribute_conditions[ $slug ] = [
                'depends_on' => $attr['depends_on'] ?? '',
                'show_when'  => !empty($attr['show_when']) ? array_map('trim', explode(',', $attr['show_when'])) : [],
                'hide_when'  => !empty($attr['hide_when']) ? array_map('trim', explode(',', $attr['hide_when'])) : [],
                'formula'    => $attr['formula'] ?? '',
            ];
        }       

        echo '<div id="cpa-edit-'.esc_attr($cart_item_key).'" class="cpa-edit-form cpa-product-fields" style="display:none;" data-productid="'.$cart_item['product_id'].'" >';

        foreach ($attributes as $attr) { //print_r($attr);

            $label = $attr['label'];
            $slug  = sanitize_title($label);

            $value = '';

            foreach($cart_item['variation'] as $var_key => $var_value){

                if(strpos($var_key, 'attribute_') === false){
                    continue;
                }

                $label_key = str_replace('attribute_', '', $var_key);

                if(sanitize_title($label_key) === sanitize_title($label)){
                    $value = $var_value;
                    break;
                }

            }

            echo '<div class="cpa-field cpa-attribute" data-attribute="' . strtolower(trim(preg_replace('/\s+/', '_', $label))) . '" >';
            $required = ($attr['required'] == 1)?'*': '';
            echo '<label>'.esc_html($label).$required.'</label>';

            /* TEXT FIELD */
            if($attr['type'] === 'text'){                

                $required = !empty($attr['required']) ? 'data-required="1"' : '';

                echo '<input type="text"
                        name="'.esc_attr($label).'"
                        value="'.esc_attr($value).'"
                        '.$required.'>';

            }

            /* SELECT FIELD */
            if($attr['type'] === 'select'){

                // convert label back to value if needed
                foreach($attr['options'] as $option){
                    if($value == $option['label']){
                        $value = $option['value'];
                        break;
                    }
                }  

                $required = !empty($attr['required']) ? 'data-required="1"' : '';

                echo '<select name="'.esc_attr($label).'" '.$required.'>';

                foreach($attr['options'] as $option){

                    $selected = selected($value, $option['value'], false);

                    echo '<option value="'.esc_attr($option['value']).'" '.$selected.'>'.
                            esc_html($option['label']).
                         '</option>';
                }

                echo '</select>';
            }

            echo '</div>';
        }

        echo '<button type="button" class="cpa-save-btn" data-cart-key="'.esc_attr($cart_item_key).'">Update</button>';
          
        
        echo '<script class="cpa-attribute-conditions part_cpa_attribute_conditions_'.$cart_item['product_id'].'" type="application/json">';
        echo wp_json_encode( $attribute_conditions );
        echo '</script>';

        echo '</div>';
    }


    
    public function cpa_update_cart_item(){

        if(empty($_POST['cart_item_key'])){
            wp_die();
        }

        $cart_item_key = sanitize_text_field($_POST['cart_item_key']);
        $fields        = $_POST['fields'];

        $cart = WC()->cart->get_cart();

        if(!isset($cart[$cart_item_key])){
            wp_die();
        }

        
        //   STEP 1: Normalize variation keys 
        $normalized_variation = [];

        if(!empty($cart[$cart_item_key]['variation'])){

            foreach($cart[$cart_item_key]['variation'] as $var_key => $var_value){

                if(strpos($var_key, 'attribute_') === false){
                    continue;
                }

                $slug = sanitize_title(str_replace('attribute_', '', $var_key));
                $new_key = 'attribute_' . $slug;

                $normalized_variation[$new_key] = $var_value;
            }
        }

        $cart[$cart_item_key]['variation'] = $normalized_variation;


        
        // STEP 2: Update existing attributes
        foreach($fields as $label => $value){

            $value = sanitize_text_field($value);
            $slug  = sanitize_title($label);

            $variation_key = 'attribute_' . $slug;

            //if(isset($cart[$cart_item_key]['variation'][$variation_key])){
                $cart[$cart_item_key]['variation'][$variation_key] = $value;
            //}
        }

        
        // STEP 3: Update stored custom fields 
        $cart[$cart_item_key]['cpa_fields'] = [];

        foreach($fields as $label => $value){

            $cart[$cart_item_key]['cpa_fields'][] = [
                'label' => sanitize_text_field($label),
                'value' => sanitize_text_field($value)
            ];
        }        


        //  STEP 4: Save cart session
        WC()->cart->cart_contents = $cart;
        WC()->cart->set_session();
        WC()->cart->calculate_totals();

        wp_send_json_success();
    }



    //---------------------  Cart/Checkout flow enhancement code END here   -------------------------------------



    /**
     * Hide prices and totals from WooCommerce order emails (admin + customer)
     */
    public function hide_price_from_order_email_template($totals, $order, $tax_display){
        // Remove all total rows
        return [];
    }



    /**
     * Change "Add to cart" button text globally   
     */
    public function custom_add_to_cart_text( $text ) {
        return __( 'Add to Order', 'woocommerce' );
    }


    /**
    * Change button text + link on Product cards
    */
    public function custom_add_to_order_button( $button, $product ) {
        $product_id   = $product->get_id();
        $product_link = get_permalink( $product_id );
        $button_text  = __( 'View Details', 'woocommerce' );

        // Replace WooCommerce button with your custom one linking to single product
        return sprintf(
            '<a href="%s" class="button add_to_order_button" data-product_id="%s">%s</a>',
            esc_url( $product_link ),
            esc_attr( $product_id ),
            esc_html( $button_text )
        );
    }


    /**
     * Hide all prices site-wide
     */
    public function hide_all_prices( $price = '' ) {
        return '';
    }


    /**
     * Force a dummy price internally so WooCommerce allows add to cart
     */
    public function set_dummy_price( $price, $product ) {
        return $price ? $price : 1; // return 1 if no price is set
    }   


    /**
     * Allow checkout submission even with zero total (bypass payment requirement)
     */
    public function allow_checkout_without_payment() {
        if (is_checkout() && !is_wc_endpoint_url('order-received')) {
            add_filter('woocommerce_cart_needs_payment', '__return_false');
        }
    }
   

    /**
     * Hide "no payment methods" message on Gutenberg Checkout Block
     */
    public function hide_checkout_block_message() {
        if ( has_block('woocommerce/checkout') ) {
            wp_add_inline_style(
                'wc-blocks-style',
                '.wc-block-components-notice-banner.is-error { display: none !important; }'
            );
        }
    }


    // Remove all totals from Store API response
    public function remove_totals_from_store_api( $response ) {
        if ( isset( $response['totals'] ) ) {
            $response['totals'] = array(); // Empty totals
        }
        return $response;
    }


    // Hide totals visually for Cart and Checkout blocks
    public function hide_totals_for_blocks() {
        if ( has_block( 'woocommerce/checkout' ) || has_block( 'woocommerce/cart' ) ) {

            $css = '
            /* CART BLOCK: hide totals section completely */
            .wc-block-cart__totals,
            .wc-block-cart__footer {
                display: none !important;
            }

            /* CHECKOUT BLOCK: hide only totals values and keep structure */
            .wc-block-components-totals-item__value,
            .wc-block-components-totals-item__label,
            .wc-block-components-order-summary-item--subtotal .wc-block-components-order-summary-item__value,
            .wc-block-components-order-summary-item--subtotal .wc-block-components-order-summary-item__label,
            .wc-block-components-order-summary-item--tax .wc-block-components-order-summary-item__value,
            .wc-block-components-order-summary-item--tax .wc-block-components-order-summary-item__label,
            .wc-block-components-order-summary-item--total .wc-block-components-order-summary-item__value,
            .wc-block-components-order-summary-item--total .wc-block-components-order-summary-item__label {
                color: transparent !important;
            }

            /* Hide any currency symbols that might remain */
            .wc-block-components-formatted-money-amount,
            .wc-block-components-totals-item__value {
                visibility: hidden !important;
            }

            /* Keep structure intact */
            .wc-block-components-order-summary,
            .wc-block-components-order-summary__content,
            .wc-block-components-order-summary__items {
                display: block !important;
                visibility: visible !important;
                opacity: 1 !important;
            }

            /* Hide payment methods and error */
            /*.wc-block-components-checkout-payment-methods,
            .wc-block-components-notice-banner.is-error {
                display: none !important;
            }*/

            /* Optional: Replace total area with message */
            .wc-block-components-order-summary__content::after {
                content: "Prices will be confirmed after review." !important;
                display: block;
                text-align: center;
                margin-top: 10px;
                font-weight: 600;
                color: #333;
            }

            /* Hide coupon sections (form and applied coupons) */
                .wc-block-cart__coupon,
                .wc-block-components-totals-coupon,
                .wc-block-components-totals-discount,
                .wc-block-components-order-summary-item--discount,
                .wc-block-components-order-summary-discount {
                    display: none !important;
            }';

            wp_add_inline_style( 'wc-blocks-style', $css );
        }
    }
   

    /**
     * Register dummy payment gateway for classic checkout
     */
    public function register_dummy_payment_gateway() {
        add_filter( 'woocommerce_payment_gateways', function( $methods ) {
            $methods[] = 'WC_Gateway_Dummy';
            return $methods;
        });
    }


    /**
     * Register dummy payment gateway for Checkout Block (Store API)
     */
    public function register_blocks_dummy_gateway() {
        add_action( 'woocommerce_blocks_payment_method_type_registration', function( $payment_method_registry ) {
            if ( class_exists( 'WC_Block_Dummy_Gateway' ) ) {
                $payment_method_registry->register( new WC_Block_Dummy_Gateway() );
            }
        });
    }


    /**
     * Patch WooCommerce Blocks checkout request early to inject billing & payment fields
     */
    public function patch_block_checkout_request( $request_data ) {

        // Make sure billing address exists
        if ( empty( $request_data['billing_address'] ) || ! is_array( $request_data['billing_address'] ) ) {
            $request_data['billing_address'] = array(
                'first_name' => '',
                'last_name'  => '',
                'company'    => '',
                'address_1'  => '',
                'address_2'  => '',
                'city'       => '',
                'state'      => '',
                'postcode'   => '',
                'country'    => '',
                'email'      => '',
                'phone'      => '',
            );
        }

        // Copy billing → shipping if empty
        if ( empty( $request_data['shipping_address'] ) ) {
            $request_data['shipping_address'] = $request_data['billing_address'];
        }

        // Inject dummy payment gateway if missing
        if ( empty( $request_data['payment_method'] ) ) {
            $request_data['payment_method']       = 'no_payment';
            $request_data['payment_method_title'] = 'No Payment Required';
        }

        // Some WooCommerce blocks also expect "payment_data" key
        if ( empty( $request_data['payment_data'] ) ) {
            $request_data['payment_data'] = array();
        }

        return $request_data;
    }



    /**
     * Safely ensure billing + payment info exists for Block Checkout without overwriting valid data.
     */
    public function force_dummy_checkout_request( $result, $server, $request ) {
        $route  = $request->get_route();
        $method = strtoupper($request->get_method());

        // Only affect Store API checkout endpoint
        if ( $method !== 'POST' || strpos( $route, '/wc/store/v1/checkout' ) === false ) {
            return $result;
        }

        $params = $request->get_json_params();
        if ( ! is_array( $params ) ) {
            $params = [];
        }

        // Ensure billing_address is valid
        if ( empty( $params['billing_address'] ) || ! is_array( $params['billing_address'] ) ) {
            $params['billing_address'] = [];
        }

        // Fill missing fields with safe defaults
        $billing_defaults = [
            'first_name' => '',
            'last_name'  => '',
            'company'    => '',
            'address_1'  => '',
            'address_2'  => '',
            'city'       => '',
            'state'      => '',
            'postcode'   => '',
            'country'    => '', 
            'email'      => '',
            'phone'      => '',
        ];
        $params['billing_address'] = array_merge( $billing_defaults, $params['billing_address'] ); 

        // Ensure shipping_address exists and is valid
        if ( empty( $params['shipping_address'] ) || ! is_array( $params['shipping_address'] ) ) {
            $params['shipping_address'] = $params['billing_address'];
        }

        // Inject dummy payment method if none
        if ( empty( $params['payment_method'] ) ) {
            $params['payment_method']       = 'no_payment';
            $params['payment_method_title'] = 'No Payment Required';
        }

        // Reset request parameters safely
        foreach ( $params as $key => $value ) {
            $request->set_param( $key, $value );
        }

        return $result;
    }



    /**
     * Shared logic to finalize the order without payment
     */
    private function complete_order_without_payment($order) {
        if (!$order instanceof WC_Order) {
            $order = wc_get_order($order);
        }

        if (!$order) {
            return;
        }

        // Mark order as completed without payment
        $order->set_payment_method('none');
        $order->set_payment_method_title(__('No Payment', 'woocommerce'));
        $order->set_status('completed', __('No payment required (auto processed)', 'woocommerce'));
        $order->save();

        // Empty cart (if exists)
        if (WC()->cart) {
            WC()->cart->empty_cart();
        }

        // Redirect only for classic checkout (Block handles redirect in JS)
        if (!defined('REST_REQUEST') && !wp_doing_ajax()) {
            wp_safe_redirect($order->get_checkout_order_received_url());
            exit;
        }
    }
 

    /**
     * Auto-complete orders when no payment is required
     */
    public function auto_complete_zero_total_order($order_id, $posted_data, $order) {
        if ($order->get_total() == 0) {
            $order->set_payment_method('no_payment');
            $order->payment_complete();
            $order->update_status('completed', __('No payment required (auto processed).', 'woocommerce'));
        }
    }    


    /**
     * Automatically handle checkout without a payment method
     * for WooCommerce Block Checkout (Store API)
     */
    public function process_zero_payment_order( $order ) {
        if ( ! $order || ! is_a( $order, 'WC_Order' ) ) {
            return $order;
        }

        // Only run if no total or no payment method
        if ( $order->get_total() <= 0 || ! $order->get_payment_method() ) {
            $order->set_payment_method( 'no_payment_required' );
            $order->payment_complete();
            $order->update_status( 'completed', __( 'Auto completed: No payment required.', 'woocommerce' ) );
        }

        return $order;
    }



    /**
     * Handle missing payment method or billing data for Block Checkout
     */
    public function handle_no_payment_checkout( $response, $handler, $request ) {
        try {
            $route  = $request->get_route();
            $method = strtoupper( $request->get_method() );

            // Only intercept the Checkout endpoint
            if ( $method !== 'POST' || strpos( $route, '/wc/store/v1/checkout' ) === false ) {
                return $response;
            }

            $params = $request->get_json_params();

            // Inject dummy payment method if missing
            if ( empty( $params['payment_method'] ) ) {
                $params['payment_method']       = 'no_payment';
                $params['payment_method_title'] = 'No Payment Required';
            }

            // Ensure billing address structure exists
            if ( empty( $params['billing_address'] ) || !is_array( $params['billing_address'] ) ) {
                $params['billing_address'] = array(
                    'first_name' => '',
                    'last_name'  => '',
                    'company'    => '',
                    'address_1'  => '',
                    'address_2'  => '',
                    'city'       => '',
                    'state'      => '',
                    'postcode'   => '',
                    'country'    => '',
                    'email'      => '',
                    'phone'      => '',
                );
            }

            // Copy billing to shipping if missing
            if ( empty( $params['shipping_address'] ) ) {
                $params['shipping_address'] = $params['billing_address'];
            }

            // Safely override REST request parameters
            $reflection = new ReflectionClass( $request );
            if ( $reflection->hasProperty( 'params' ) ) {
                $property = $reflection->getProperty( 'params' );
                $property->setAccessible( true );
                $property->setValue( $request, $params );
            }
        } catch ( \Throwable $e ) {
            error_log( '[Custom B2B] Checkout bypass error: ' . $e->getMessage() );
        }

        return $response;
    }




    /**
     * Add product fields in admin
     */
    public function add_custom_product_fields() {
        global $post;

        echo '<div class="options_group cpa_attributes_section">';
        echo '<h3 style="margin-top:20px;">Custom Attributes</h3>';
        echo '<button id="cpa_toggle_all" class="button">Expand All</button>';

        // Get saved attributes
        $custom_attributes = get_post_meta( $post->ID, '_cpa_custom_attributes', true );
        if ( ! is_array( $custom_attributes ) ) {
            $custom_attributes = [];
        }
        //print_r($custom_attributes);
        ?>
        <div id="cpa_attributes_wrapper">
            <?php foreach ( $custom_attributes as $index => $attr ) :  ?>                

                <div class="cpa_attribute" style="margin-bottom:10px;border-bottom:1px solid #ddd;padding-bottom:10px;">

                    <div class="cpa_attribute_collapse">Collapse</div>
                    <span class="cpa-sort-handle dashicons dashicons-move"></span>
                    <div class="cpa_attribute_box_title">  <?php echo esc_attr( $attr['label'] ); ?> <div>[ slug: <span><?php echo strtolower(trim(preg_replace('/\s+/', '_', $attr['label']))); ?></span> ]</div></div>

                    <div class="cpa_attribute_content">
                        <p class="form-field cpa_label">
                            <label>Label: [ slug: <span><?php echo strtolower(trim(preg_replace('/\s+/', '_', $attr['label']))); ?></span> ]</label>
                            <input type="text" name="cpa_label[]" value="<?php echo esc_attr( $attr['label'] ); ?>" placeholder="e.g. Color or Custom Text" />
                        </p>
                        <p class="form-field">
                            <label>Required field</label>
                            <input type="checkbox" name="cpa_required[<?php echo $index; ?>]" value=1 <?php checked( ! empty( $attr['required'] ), 1 ); ?> />
                        </p>
                        <p class="form-field">
                            <label>Type:</label>
                            <select name="cpa_type[]">
                                <option value="text" <?php selected( $attr['type'], 'text' ); ?>>Text</option>
                                <option value="select" <?php selected( $attr['type'], 'select' ); ?>>Dropdown</option>
                            </select>
                        </p>
                        <div class="form-field">
                            <label>Dropdown Options:</label>                        
                            <div class="cpa-option-wrapper">
                                <?php 
                                if(!empty($attr['options']) && is_array($attr['options'])) {
                                    foreach($attr['options'] as $opt) { ?>
                                        <div class="cpa-option-item" style="margin-bottom:5px; display:flex; gap:10px;">
                                            <!-- Radio: default selected -->
                                            <input type="radio"
                                                name="cpa_default_option[<?php echo $index; ?>]"
                                                value="<?php echo esc_attr($opt['value']); ?>"
                                                <?php checked( $attr['default'], $opt['value'] ); ?>
                                                title="Set as Default" 
                                                
                                            >

                                            <input type="text" name="cpa_option_label[<?php echo $index; ?>][]" 
                                                value="<?php echo esc_attr($opt['label']); ?>" placeholder="Label (e.g. Red)" />

                                            <input type="text" name="cpa_option_value[<?php echo $index; ?>][]"
                                                value="<?php echo esc_attr($opt['value']); ?>" placeholder="Value (e.g. red)" />

                                            <button class="button cpa-remove-option">–</button>
                                        </div>
                                <?php } 
                                } ?>
                            </div>
                            <button class="button cpa-add-option" data-index="<?php echo $index; ?>">+ Add Option</button>
                        </div>
                        <p class="form-field">
                            <label>Description (optional):</label>
                            <textarea name="cpa_description[]" rows="2" placeholder="Enter message for description"><?php echo esc_textarea( $attr['description'] ?? '' ); ?></textarea>
                        </p>
                        <p class="form-field">
                            <label>Tooltip Message (optional):</label>
                            <textarea name="cpa_tooltip[]" rows="2" placeholder="Enter info message for tooltip"><?php echo esc_textarea( $attr['tooltip'] ?? '' ); ?></textarea>
                        </p>
                        <p class="form-field">
                            <label>Popup Label (mandatory if adding popup):</label>
                            <input type="text" name="cpa_popup_label[]" value="<?php echo esc_attr( $attr['popup_label'] ?? '' ); ?>" placeholder="e.g. View Size Guide" />
                        </p>
                        <div class="form-field">
                            <label>Popup Content (mandatory if adding popup):</label>
                            <?php
                            $content = $attr['popup_content'] ?? '';
                            $editor_id = 'cpa_popup_content_' . uniqid(); // unique editor ID

                            wp_editor(
                                $content,
                                $editor_id,
                                [
                                    'textarea_name' => 'cpa_popup_content[]', // ARRAY name
                                    'textarea_rows' => 10,
                                    'media_buttons' => true,
                                    'teeny'         => false,
                                    'quicktags'     => true,
                                    'editor_class'  => 'cpa_popup_content',  // ← your CLASS here
                                ]
                            );
                            ?>                        
                        </div> 
                        <!-- Attribute Conditions Section -->
                        <div class="attribute-conditions">
                            <h4>Conditional Logic</h4>
                            <p class="form-field">
                                <label>Dependent On (Attribute Slug):</label>
                                <input type="text" name="cpa_depends_on[]" value="<?php echo esc_attr($attr['depends_on'] ?? ''); ?>" placeholder="e.g. starters" />
                            </p>
                            <p class="form-field">
                                <label>Show When (Values, comma separated):</label>
                                <input type="text" name="cpa_show_when[]" value="<?php echo esc_attr($attr['show_when'] ?? ''); ?>" placeholder="e.g. Direct Mount, Large Starter" />
                            </p>
                            <p class="form-field">
                                <label>Hide When (Values, comma separated):</label>
                                <input type="text" name="cpa_hide_when[]" value="<?php echo esc_attr($attr['hide_when'] ?? ''); ?>" placeholder="e.g. Planview 1, Planview 2" />
                            </p>
                            <p class="form-field">
                                <label>Auto Fill Formula (optional):</label>
                                <input type="text" name="cpa_formula[]" value="<?php echo esc_attr($attr['formula'] ?? ''); ?>" placeholder="e.g. width*height*length" />
                                <span class="description">
                                    Use "*" for multiplication, "+" for addition, "-" for subtraction, and "/" for division.<br>
                                    Example: ((width * height) / 144 )*slat_type
                                </span>    
                            </p>
                        </div>
                        <p class="form-field">
                            <button type="button" class="button cpa-remove-attribute">Remove</button>
                        </p>
                    </div>
                </div>

            <?php endforeach; ?>
        </div>

        <button type="button" class="button" id="cpa_add_new_attribute">+ Add New Attribute</button>

        <style>
            .options_group.cpa_attributes_section {
              padding: 10px;
            }
            .cpa_attribute div.form-field {
              padding: 5px 20px 5px 162px !important;
            }
            #cpa_attributes_wrapper .cpa_attribute {
              position: relative;
            }
            #cpa_attributes_wrapper .cpa_attribute .cpa_attribute_collapse {
              position: absolute;
              right: 10px;
              top: 10px;
              cursor:pointer;
              color: #2271b1;
            }
            #cpa_attributes_wrapper .cpa_attribute .cpa-sort-handle.dashicons.dashicons-move {
              position: absolute;
              left: 5px;
              top: 10px;
              cursor:move;
            }
            #cpa_attributes_wrapper .cpa_attribute .cpa_attribute_box_title {
              padding: 10px 0 10px 30px;
              font-weight: 600;
              font-size: 15px;
              color: #000;
              background: #f2f2f2;
              display: flex;
              align-items: center;
            }
            #cpa_attributes_wrapper .cpa_attribute .cpa_attribute_box_title div {
              font-weight: 500;
              font-size: 13px;
              padding-left: 10px;
            }
            .options_group.cpa_attributes_section {
              position: relative;
            }
            .options_group.cpa_attributes_section #cpa_toggle_all {
              position: absolute;
              right: 10px;
              top: 26px;
            }
        </style>        

        <script>
            jQuery(function($){                

                // Make attributes sortable
                $("#cpa_attributes_wrapper").sortable({
                    handle: ".cpa-sort-handle",
                    placeholder: "cpa-sort-placeholder",
                    forcePlaceholderSize: true
                });

                // Expand All/Collapse All button event
                $(document).on("click", "#cpa_toggle_all", function(e){
                    e.preventDefault();

                    let state = $(this).data("state");

                    if (state === "collapsed") {
                        // Expand all
                        $(".cpa_attribute_content").slideDown();
                        $(".cpa_attribute_collapse").text("Collapse");
                        $(this).text("Collapse All");
                        $(this).data("state", "expanded");
                    } else {
                        // Collapse all
                        $(".cpa_attribute_content").slideUp();
                        $(".cpa_attribute_collapse").text("Expand");
                        $(this).text("Expand All");
                        $(this).data("state", "collapsed");
                    }
                });


                $(document).ready(function () {
                    $(".cpa_attribute_content").hide();
                    $(".cpa_attribute_collapse").text("Expand");

                    // Initial state
                    $("#cpa_toggle_all").data("state", "collapsed"); 
                });

                // toggle the section
                $(document).ready(function () {
                    $(".cpa_attribute_content").hide();
                    $(".cpa_attribute_collapse").text("Expand");

                    // Initial state
                    $("#cpa_toggle_all").data("state", "collapsed"); 
                });
                $(document).on("click", ".cpa_attribute_collapse", function (e) {
                    e.preventDefault();

                    let $btn = $(this);
                    let $content = $btn.closest(".cpa_attribute").find(".cpa_attribute_content");

                    $content.slideToggle();

                    // change Collapse/Expand text
                    if ($btn.text().trim() === "Collapse") {
                        $btn.text("Expand");
                    } else {
                        $btn.text("Collapse");
                    }
                });

                // Add option row
                $(document).on("click", ".cpa-add-option", function(e){
                    e.preventDefault();

                    let attribute = $(this).closest(".cpa_attribute");
                    let index = attribute.index();

                    $(this).prev(".cpa-option-wrapper").append(`
                        <div class="cpa-option-item" style="margin-bottom:5px; display:flex; gap:10px;">
                            <input type="radio" name="cpa_default_option[${index}]" value="" class="cpa-default-radio" title="Set as Default">
                            <input type="text" name="cpa_option_label[${index}][]" placeholder="Label" />
                            <input type="text" name="cpa_option_value[${index}][]" placeholder="Value" class="cpa-option-value-input" />
                            <button class="button cpa-remove-option">–</button>
                        </div>
                    `);
                });

                // Set radio value dynamically based on option value field
                $(document).on("input", ".cpa-option-value", function () {
                    let value = $(this).val();
                    $(this).closest(".cpa-option-item").find(".cpa-default-radio").val(value);
                });

                // Remove option row
                $(document).on("click", ".cpa-remove-option", function(e){
                    e.preventDefault();
                    $(this).parent().remove();
                });

                // Update radio value when option value text changes
                $(document).on("input", ".cpa-option-value-input", function() {
                    let val = $(this).val();
                    $(this).closest('.cpa-option-item').find('.cpa-default-radio').val(val);
                });

                // instant slug showing for new attribute
                document.addEventListener("input", function (e) {
                  if (e.target.matches('input[name="cpa_label[]"]')) {
                    const val = e.target.value;

                    // Convert to slug
                    const slug = val
                      .toLowerCase()
                      .trim()
                      .replace(/[^\w\s]/g, "")   // remove special chars
                      .replace(/\s+/g, "_");    // spaces → underscores

                    // Find the span inside the same label block
                    const wrapper = e.target.closest(".cpa_label");
                    const span = wrapper?.querySelector("label span");
                    if (span) {
                      span.textContent = slug;
                    }

                    const title_slug = e.target.closest(".cpa_attribute");
                    const title_slug_span = title_slug?.querySelector(".cpa_attribute_box_title span");                    
                    if (title_slug_span) {
                      title_slug_span.textContent = slug;
                    }

                  }
                });

                $('#cpa_add_new_attribute').on('click', function(e){
                    e.preventDefault();
                    let attributes_exists = $('.cpa_attribute').length;
                    let id = 'cpa_editor_' + Date.now();
                    $('#cpa_attributes_wrapper').append(`
                        <div class="cpa_attribute" style="margin-bottom:10px;border-bottom:1px solid #ddd;padding-bottom:10px;">
                            <p class="form-field cpa_label">
                                <label>Label: [ slug: <span></span>]</label>
                                <input type="text" name="cpa_label[]" value="" placeholder="e.g. Color or Custom Text" /></p>
                            <p class="form-field">
                                <label>Required field</label>
                                <input type="checkbox" name="cpa_required[${attributes_exists}]" value=1 />
                            </p>
                            <p class="form-field">
                                <label>Type:</label>
                                <select name="cpa_type[]">
                                    <option value="text">Text</option>
                                    <option value="select">Dropdown</option>
                                </select></p>
                            <div class="form-field">
                                <label>Dropdown Options:</label>                                
                                <div class="cpa-option-wrapper">                                    
                                </div>
                                <button class="button cpa-add-option" data-index="${attributes_exists}">+ Add Option</button>
                            </div>
                            <p class="form-field">
                                <label>Description (optional):</label>
                                <textarea name="cpa_description[]" rows="2" placeholder="Enter message for description"></textarea>
                            </p>
                            <p class="form-field">
                                <label>Tooltip Message (optional):</label>
                                <textarea name="cpa_tooltip[]" rows="2" placeholder="Enter info message for tooltip"></textarea></p>
                            <p class="form-field">
                                <label>Popup Label (mandatory if adding popup):</label>
                                <input type="text" name="cpa_popup_label[]" value="" placeholder="e.g. View Size Guide" />
                            </p>
                            <p class="form-field popup_texteditor_wrap_${id}">
                                <label>Popup Content (mandatory if adding popup):</label> 
                            </p>

                            <div class="attribute-conditions">
                                <h4>Conditional Logic</h4>
                                <p class="form-field">
                                    <label>Dependent On (Attribute Slug):</label>
                                    <input type="text" name="cpa_depends_on[]" value="" placeholder="e.g. starters" />
                                </p>
                                <p class="form-field">
                                    <label>Show When (Values, comma separated):</label>
                                    <input type="text" name="cpa_show_when[]" value="" placeholder="e.g. Direct Mount, Large Starter" />
                                </p>
                                <p class="form-field">
                                    <label>Hide When (Values, comma separated):</label>
                                    <input type="text" name="cpa_hide_when[]" value="" placeholder="e.g. Planview 1, Planview 2" />
                                </p>
                                <p class="form-field">
                                    <label>Auto Fill Formula (optional):</label>
                                    <input type="text" name="cpa_formula[]" value="" placeholder="e.g. width*height*length" />
                                </p>
                            </div>

                            <button type="button" class="button cpa-remove-attribute">Remove</button>
                        </div>
                    `);

                    // for editor
                    
                    $('#cpa_attributes_wrapper').find('.popup_texteditor_wrap_'+id).append(`<textarea id="${id}" name="cpa_popup_content[]"></textarea>`);

                     // Clone the default WordPress editor settings
                    let mceSettings = JSON.parse(JSON.stringify(tinyMCEPreInit.mceInit["content"]));
                    let qtSettings  = JSON.parse(JSON.stringify(tinyMCEPreInit.qtInit["content"]));

                    // Replace selector ID
                    mceSettings.selector = `#${id}`;
                    qtSettings.id        = id;

                    wp.editor.initialize(id, {
                        tinymce: mceSettings,
                        quicktags: qtSettings,
                        mediaButtons: true
                    }); 

                   
                });

                $(document).on('click', '.cpa-remove-attribute', function(){
                    $(this).closest('.cpa_attribute').remove();
                });
            });
        </script>
        <?php
        echo '</div>';
    }



    /**
     * Save product fields
     */
    public function save_custom_product_fields( $post_id ) {
        $labels  = isset( $_POST['cpa_label'] ) ? $_POST['cpa_label'] : [];          
        $required = isset( $_POST['cpa_required'] ) ? wp_unslash( $_POST['cpa_required'] ) : [];    
        $types   = isset( $_POST['cpa_type'] ) ? $_POST['cpa_type'] : [];
        //$options = isset( $_POST['cpa_options'] ) ? $_POST['cpa_options'] : []; 
        $description = isset( $_POST['cpa_description'] ) ? $_POST['cpa_description'] : [];
        $tooltips = isset( $_POST['cpa_tooltip'] ) ? $_POST['cpa_tooltip'] : [];
        $popup_labels   = isset( $_POST['cpa_popup_label'] ) ? $_POST['cpa_popup_label'] : [];
        $popup_contents = isset( $_POST['cpa_popup_content'] ) ? $_POST['cpa_popup_content'] : [];
        $depends_on = isset( $_POST['cpa_depends_on'] ) ? $_POST['cpa_depends_on'] : [];
        $show_when = isset( $_POST['cpa_show_when'] ) ? $_POST['cpa_show_when'] : [];
        $hide_when = isset( $_POST['cpa_hide_when'] ) ? $_POST['cpa_hide_when'] : [];
        $formula = isset( $_POST['cpa_formula'] ) ? $_POST['cpa_formula'] : [];
        $default_options = $_POST['cpa_default_option'] ?? [];
        

        $custom_attributes = [];

        foreach ( $labels as $index => $label ) {
            if ( ! empty( $label ) ) {
                $opt_labels = $_POST['cpa_option_label'][$index] ?? [];
                $opt_values = $_POST['cpa_option_value'][$index] ?? [];
                $option_list = [];
                foreach($opt_labels as $i => $lbl) {
                    if(!empty($lbl)) {
                        $option_list[] = [
                            "label" => sanitize_text_field($lbl),
                            "value" => sanitize_text_field($opt_values[$i] ?? $lbl)
                        ];
                    }
                }
                $default_value = isset($default_options[$index]) ? sanitize_text_field($default_options[$index]) : '';

                $custom_attributes[] = [
                    'label'             => sanitize_text_field( $label ),
                    'required'          => isset( $required[ $index ] ) ? 1 : 0,
                    'type'              => sanitize_text_field( $types[ $index ] ),
                    'options'           => $option_list, //sanitize_text_field( $options[ $index ] ),
                    'default'           => $default_value,
                    'description'       => sanitize_textarea_field( $description[ $index ] ?? '' ),
                    'tooltip'           => sanitize_textarea_field( $tooltips[ $index ] ?? '' ),
                    'popup_label'       => sanitize_text_field( $popup_labels[ $index ] ?? '' ),
                    'popup_content'     => wp_kses_post( $popup_contents[ $index ] ?? '' ), // allow HTML for image/text
                    'depends_on'        => sanitize_text_field( $depends_on[ $index ] ?? '' ),
                    'show_when'         => sanitize_text_field( $show_when[ $index ] ?? '' ),
                    'hide_when'         => sanitize_text_field( $hide_when[ $index ] ?? '' ),
                    'formula'           => sanitize_text_field( $formula[ $index ] ?? '' ),
                ];
            }
        }

        update_post_meta( $post_id, '_cpa_custom_attributes', $custom_attributes );
    }



    /**
     * Display fields on single product page
     */
    public function display_custom_fields_frontend() {
        global $product;

        $custom_attributes = get_post_meta( $product->get_id(), '_cpa_custom_attributes', true );

        if ( ! is_array( $custom_attributes ) || empty( $custom_attributes ) ) return;

        $attribute_conditions = [];

        foreach ( $custom_attributes as $attr ) {            
            $slug = strtolower(trim(preg_replace('/\s+/', '_', $attr['label']))); //sanitize_title( $attr['label'] );
            $attribute_conditions[ $slug ] = [
                'depends_on' => $attr['depends_on'] ?? '',
                'show_when'  => !empty($attr['show_when']) ? array_map('trim', explode(',', $attr['show_when'])) : [],
                'hide_when'  => !empty($attr['hide_when']) ? array_map('trim', explode(',', $attr['hide_when'])) : [],
                'formula'    => $attr['formula'] ?? '',
            ];
        }        

        echo '<div class="cpa-product-fields">';

        foreach ( $custom_attributes as $attr ) { //print_r($attr);
            $name = sanitize_title( $attr['label'] );

            echo '<div class="form-row form-row-wide cpa-attribute" data-attribute="' . esc_attr( sanitize_title( $attr['label'] ) ) . '">';

            echo '<label>' . esc_html( $attr['label'] );
            
            // Tooltip
            if ( ! empty( $attr['tooltip'] ) ) {
                echo ' <span class="cpa-info-icon" data-tooltip="' . esc_attr( $attr['tooltip'] ) . '">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#0073aa" viewBox="0 0 16 16">
                    <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
                    <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469l-.738 3.468c-.194.897.105 1.319.808 1.319.545 0 .979-.252 1.223-.598l.088-.416c-.287.07-.352-.105-.287-.381l.738-3.468c.194-.897-.105-1.319-.808-1.319zM8 4.5a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5z"/>
                  </svg></span>';
            }
            echo '</label>';

            // Input / Select
            if ( $attr['type'] === 'select' && ! empty( $attr['options'] ) ) {
                //$options = array_map( 'trim', explode( ',', $attr['options'] ) );
                echo '<select name="cpa_' . esc_attr( $name ) . '">';                

                foreach ($attr['options'] as $option) {
                    echo '<option value="' . esc_attr( $option['value'] ) . '" '.selected($attr['default'], $option['value']).' >' 
                           . esc_html( $option['label'] ) . 
                         '</option>';
                }

                echo '</select>';
            } else {
                echo '<input type="text" name="cpa_' . esc_attr( $name ) . '" />';
            }

            echo '<p class="attribute_description">' . esc_html( $attr['description'] ).'</p>';

            // Popup Label Button
            if ( ! empty( $attr['popup_label'] ) && ! empty( $attr['popup_content'] ) ) {
                echo '<button type="button" class="button cpa-popup-trigger" data-content="' . esc_attr( $attr['popup_content'] ) . '"><span class="dashicons dashicons-search"></span> ' . esc_html( $attr['popup_label'] ) . '</button>';
            }
            echo '</div>';
        }

        echo '<script class="cpa-attribute-conditions" type="application/json">';
        echo wp_json_encode( $attribute_conditions );
        echo '</script>';

        echo '<div class="cpa-popup-overlay">
                  <div class="cpa-popup-box">
                    <button class="cpa-popup-close" type="button">&times;</button>
                    <div class="cpa-popup-content"></div>
                  </div>
                </div>';

        echo '</div>';
    }



    /**
     * Add data to cart
     */
    public function add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
        $custom_attributes = get_post_meta( $product_id, '_cpa_custom_attributes', true );
        if ( ! is_array( $custom_attributes ) ) return $cart_item_data;

        foreach ( $custom_attributes as $attr ) {
            $key = 'cpa_' . sanitize_title( $attr['label'] );
            if ( isset( $_POST[ $key ] ) && $_POST[ $key ] !== '' ) {
                $cart_item_data['cpa_fields'][ $attr['label'] ] = sanitize_text_field( $_POST[ $key ] );
            }
        }

        return $cart_item_data;
    }



    /**
     * Display data on cart and checkout
     */
    public function display_cart_item_data( $item_data, $cart_item ) {
        if ( isset( $cart_item['cpa_fields'] ) ) {
            foreach ( $cart_item['cpa_fields'] as $label => $value ) {
                $item_data[] = [
                    'name'  => wp_kses_post( $label ),
                    'value' => wp_kses_post( stripslashes( $value ) ),
                    'display' => wp_kses_post( stripslashes( $value ) ),
                ];
            }
        }

        return $item_data;
    }



    /**
     * Save to order meta
     */
    public function add_order_item_meta( $item_id, $values, $cart_item_key ) {
        if ( isset( $values['cpa_fields'] ) ) {
            foreach ( $values['cpa_fields'] as $label => $value ) {
                wc_add_order_item_meta( $item_id, $label, stripslashes( $value ) );
            }
        }
    }


}







/**
 * Add a new payment method for placing order without payment
 * */
add_action( 'plugins_loaded', function() {
    if ( class_exists( 'WC_Payment_Gateway' ) ) {
        class WC_Gateway_No_Payment extends WC_Payment_Gateway {
            public function __construct() {
                $this->id                 = 'no_payment';
                $this->has_fields         = false;
                $this->method_title       = __( 'No Payment Required', 'woocommerce' );
                $this->method_description = __( 'Automatically completes orders without payment.', 'woocommerce' );
                $this->enabled            = 'yes';
            }

            public function process_payment( $order_id ) {
                $order = wc_get_order( $order_id );
                $order->payment_complete();
                $order->update_status( 'completed', __( 'No payment required.', 'woocommerce' ) );
                wc_empty_cart();

                return array(
                    'result'   => 'success',
                    'redirect' => $this->get_return_url( $order ),
                );
            }
        }

        add_filter( 'woocommerce_payment_gateways', function( $gateways ) {
            $gateways[] = 'WC_Gateway_No_Payment';
            return $gateways;
        } );
    }
});