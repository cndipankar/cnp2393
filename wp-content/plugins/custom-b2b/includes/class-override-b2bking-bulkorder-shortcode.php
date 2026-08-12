<?php
if ( ! defined( 'ABSPATH' ) ) exit;

class Override_Bulkorder_Shortcode extends B2bking_Public {

    public function __construct() {
        // Enqueue CSS (original + custom)
        add_action('wp_enqueue_scripts', [$this, 'enqueue_bulkorder_scripts']);

        add_action('init', array($this, 'override_b2bking_bulkorder_shortcode'), 20);   

        add_action('gettext', array($this, 'override_b2bking_bulkorder_cart_button_text'), 10, 3);  


        //Fetching products on first load
        add_action('wp_ajax_b2bking_ajax_fetch_parts_product', [$this, 'load_products_by_category']);
        add_action('wp_ajax_nopriv_b2bking_ajax_fetch_parts_product', [$this, 'load_products_by_category']);

        // Fetch parts products on changing category dropdown
        add_action('wp_ajax_load_parts_products_by_category', [$this, 'load_parts_products_by_category']);
        add_action('wp_ajax_nopriv_load_parts_products_by_category', [$this, 'load_parts_products_by_category']);
        
        add_action('wp_ajax_b2bking_bulkorder_add_multiple', [$this, 'handle_b2bking_bulkorder_add_multiple']);
        add_action('wp_ajax_nopriv_b2bking_bulkorder_add_multiple', [$this, 'handle_b2bking_bulkorder_add_multiple']);

        // Loads only single Finished product on right side
        add_filter('b2bking_order_form_ids_before_display', [$this,'pass_single_product_id_to_display_only_that_product'], 5, 1);
            
        // Auto-fill regular price with 1 when adding a new product in admin
        add_action('admin_footer-post-new.php', [$this, 'auto_fill_regular_price_for_new_product']);
        add_action('admin_footer-post.php', [$this, 'auto_fill_regular_price_for_new_product']);   

        // Override PDF invoice button text
        add_filter( 'wpo_wcpdf_document_title', [$this,'override_pdf_invoice_button_text'], 10, 2);         

        // Override PDF invoice output file name
        add_filter( 'wpo_wcpdf_filename', [$this, 'change_invoice_pdf_output_file_name'], 10, 5 );


        add_filter( 'woocommerce_account_menu_items', [$this, 'modify_my_account_links'], 105 );
        add_action( 'template_redirect', [$this, 'cnp_default_my_account_tab'] );
    }   


    public function cnp_default_my_account_tab() {
        if ( is_account_page() && !is_wc_endpoint_url() ) {
            wp_redirect( wc_get_account_endpoint_url( 'bulkorder' ) );
            exit;
        }
    }


    public function modify_my_account_links( $items ) {
         // 1. REMOVE unwanted menu items
        unset( $items['dashboard'] );       // Hide Offers
        unset( $items['offers'] );       // Hide Offers
        unset( $items['customer-logout'] ); // Hide Logout if needed

        // 2. RENAME Bulk Order → Order Form
        if ( isset( $items['bulkorder'] ) ) {
            $items['bulkorder'] = __( 'Order Form', 'woocommerce' );
        }

        // 3. SORT menu in your custom order
        $sorted_order = array(
            'dashboard',
            'bulkorder',        // Order Form (renamed above)            
            'orders',
            'conversations',
            'purchase-lists',
            'subaccounts',
            'edit-address',
            'edit-account',
        );

        $final = array();

        foreach ( $sorted_order as $key ) {
            if ( isset( $items[$key] ) ) {
                $final[$key] = $items[$key];
            }
        }
        
        return $final;
    } 






    public function change_invoice_pdf_output_file_name( $filename, $pdf_type, $order_ids, $context, $args ) { 
        $order_count = isset($args['order_ids']) ? count($args['order_ids']) : 1;

        //$name = _n( 'Quote Request', 'Quote Requests', $order_count, 'woocommerce-pdf-invoices-packing-slips' );

        if ( $order_count == 1 ) {            
            $suffix = $order_ids[0];

            // ensure unique filename in case suffix was empty
            if ( empty( $suffix ) ) {                
                $suffix = uniqid();                
            }
        } else {
            $suffix = date_i18n( 'Y-m-d' ); // 2024-12-31
        }

        // get filename
        $output_format = ! empty( $args['output'] ) ? esc_attr( $args['output'] ) : 'pdf';
        $filename      = /*$name  .*/ $suffix . wcpdf_get_document_output_format_extension( $output_format );
               
        return $filename;
    }

    public function override_pdf_invoice_button_text( $title, $document ) {        
        if ( $document->get_type() === 'invoice' ) {
            $title = 'Quote Request'; 
        } 
        return $title;
    }


    public function auto_fill_regular_price_for_new_product() {
        global $post, $pagenow;

        // Run only on product edit/add screen
        if ( $pagenow === 'post-new.php' || $pagenow === 'post.php' ) {
            if ( get_post_type($post) === 'product' ) : ?>
                <script type="text/javascript">
                jQuery(document).ready(function($) {
                    // Target regular price field
                    var priceField = $('#_regular_price');

                    // Only if empty, set default value to 1
                    if (priceField.length && priceField.val() === '') {
                        priceField.val('1');
                    }
                });
                </script>
            <?php
            endif;
        }
    }


    public function override_b2bking_bulkorder_shortcode() {
        // Remove original B2BKing shortcode (only if it exists)
        if (shortcode_exists('b2bking_bulkorder')) {
            global $shortcode_tags;
            $this->original_shortcode = $shortcode_tags['b2bking_bulkorder'];
            remove_shortcode('b2bking_bulkorder');
        }

        add_shortcode('b2bking_bulkorder', array($this, 'custom_bulkorder_shortcode_content'));
        
        add_action('b2bking_bulkorder_cream_custom_column', array($this, 'my_custom_bulkorder_header_section'), 1,1);        
    }



    /**
     * Re-enqueue original B2BKing CSS + add custom CSS + JS
     */
    public function enqueue_bulkorder_scripts() {   

        $b2b_plugin_file = defined('B2BKING_PLUGIN_FILE')? B2BKING_PLUGIN_FILE: WP_PLUGIN_DIR . '/b2bking/b2bking.php';
        $b2b_plugin_version = defined('B2BKING_VERSION')? B2BKING_VERSION: time();

        // Enqueue B2BKing fonts
        wp_enqueue_style(
            'b2bking_fonts_dmsans',
            plugins_url('includes/assets/css/fonts-dmsans.css', $b2b_plugin_file),
            [],
            $b2b_plugin_version
        );

        // Enqueue B2BKing Bulk Order CSS (minified or dev)
        if (defined('B2BKING_FILE_RELEASE') && B2BKING_FILE_RELEASE === 'DEV') {
            wp_enqueue_style(
                'b2bking_bulkorder',
                plugins_url('includes/assets/css/style-bulkorder.css', $b2b_plugin_file),
                ['b2bking_fonts_dmsans'],
                $b2b_plugin_version
            );
        } else {
            wp_enqueue_style(
                'b2bking_bulkorder',
                plugins_url('includes/assets/css/style-bulkorder.min.css', $b2b_plugin_file),
                ['b2bking_fonts_dmsans'],
                $b2b_plugin_version
            );
        }

        // Enqueue of custom CSS for additional styling
        wp_enqueue_style(
            'custom_bulkorder_override',
            plugin_dir_url( dirname( __FILE__ ) ) . 'assets/css/override-bulkorder.css',
            ['b2bking_bulkorder'],
            time() // change to version number for production
        );

    }


    /*
    * Override cart button text
    */
    public function override_b2bking_bulkorder_cart_button_text($translated_text, $text, $domain) {
        // Match the B2BKing text domain
        if ($domain === 'b2bking') {

            if ($text === 'Add to cart') {
                return 'Add to order'; 
            }

            if ($text === 'Add selected items to cart') {
                return 'Add all selected items to Order'; 
            }
        }
        return $translated_text;
    }


    
    /*
    * Add attributes to the line items on bulk order page
    */
    public function my_custom_bulkorder_header_section($product) {          
        //print_r($_POST);
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

        echo '<div class="cpa-product-fields" data-productid="'.$product->get_id().'">';

        foreach ( $custom_attributes as $attr ) { 
            $name = sanitize_title( $attr['label'] );
            $required = $attr['required'];

            echo '<div class="form-row form-row-wide cpa-attribute" data-attribute="' . strtolower(trim(preg_replace('/\s+/', '_', $attr['label']))) . '">';

            echo '<label>' . esc_html( $attr['label'] );

            if($required==1){
                echo '<span class="required">*</span>';
            }
            
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
            $is_required = ($required==1)? 'required' : '';
            if ( $attr['type'] === 'select' && ! empty( $attr['options'] ) ) {
                //$options = array_map( 'trim', explode( ',', $attr['options'] ) );
                echo '<select name="cpa_' . esc_attr( $name ) . '" '.$is_required.'>';
                foreach ($attr['options'] as $option) {
                    echo '<option value="' . esc_attr( $option['value'] ) . '" '.selected($attr['default'], $option['value'], false).' >' 
                           . esc_html( $option['label'] ) . 
                         '</option>';
                }
                echo '</select>';
            } else {
                echo '<input type="text" name="cpa_' . esc_attr( $name ) . '" '.$is_required.' />';
            }

            echo '<p class="attribute_description">' . esc_html( $attr['description'] ).'</p>';

            // Popup Label Button
            if ( ! empty( $attr['popup_label'] ) && ! empty( $attr['popup_content'] ) ) {
                
                // Convert PDF links to embedded viewer
                $popup_content = preg_replace(
                    '/<a[^>]+href="([^"]+\.pdf)"[^>]*>.*?<\/a>/i',
                    '<iframe src="$1" width="100%" height="600px" style="border:1px solid #ccc;"></iframe>',
                    $attr['popup_content']
                );
                echo '<button type="button" class="button cpa-popup-trigger" data-content="' . esc_attr( $popup_content ) . '"><span class="dashicons dashicons-search"></span> ' . esc_html( $attr['popup_label'] ) . '</button>';
            }
            echo '</div>';
        }

        //echo '<script class="cpa-attribute-conditions" type="application/json">';
        echo '<script class="cpa-attribute-conditions part_cpa_attribute_conditions_'.$product->get_id().'" type="application/json">';
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



    // Custom bulk order shortcode (based on original B2BKing code)
    public function custom_bulkorder_shortcode_content($atts) {          

        $b2b_plugin_file = defined('B2BKING_PLUGIN_FILE')? B2BKING_PLUGIN_FILE: WP_PLUGIN_DIR . '/b2bking/b2bking.php';        

        $atts = shortcode_atts(
            array(
                'theme' => 'cream', //get_option( 'b2bking_order_form_theme_setting', 'classic' ),
                'category' => 'all',
                'tag' => 'all',
                'sku' => 'no',
                'multiselect' => get_option( 'b2bking_order_form_cream_multiselect_setting', 0 ),
                'stock' => 'no',
                'sortby' => get_option( 'b2bking_order_form_sortby_setting', 'atoz' ),
                'exclude' => '',
                'product_list' => '',
                'attributes' => 'no',
                'instock' => 'instock',
                'width' => 'adaptive',
                'hidecolumns' => 'subtotal',
                'style' => '',
                'attributestext' => apply_filters('b2bking_orderform_attributes_text', esc_html__('Attributes','b2bking')),
            ), 
        $atts);

        $theme = $atts['theme'];
        $multiselect = $atts['multiselect'];
        if ($multiselect !== 'yes' && $multiselect !== 'no'){
            if (intval($multiselect) === 0){
                $multiselect = 'no';
            } else if (intval($multiselect) === 1){
                $multiselect = 'yes';
            }
        }

        $attributes = $atts['attributes'];
        $width = $atts['width'];
        $hidecolumns = $atts['hidecolumns'];
        $style = $atts['style'];
        if (empty($hidecolumns)){
            $hidecolumns = array();
        } else {
            $hidecolumns = explode(',', $hidecolumns);
            $hidecolumns = array_map('trim', $hidecolumns);
        }

        $attributestext = $atts['attributestext'];
        $showsku = $atts['sku'];
        $showstock = $atts['stock'];
        $category = $atts['category'];
        if ($category === 'all'){
            $category = 0;
        }
        $tag = $atts['tag'];
        if ($tag === 'all'){
            $tag = 0;
        }
        $exclude = $atts['exclude'];
        $product_list = $atts['product_list'];
        $sortby = $atts['sortby'];
        $instock = $atts['instock'];

        if (apply_filters('b2bking_bulkorder_instock_filter', false)){
            if (apply_filters('b2bking_bulkorder_instock_filter_default_all', true)){
                $instock = 'all';
            }
        }

        ob_start();

        if ($this->user_has_offer_in_cart() === 'yes'){
            if ($this->dynamic_replace_prices_with_quotes() === 'yes' || (get_option('b2bking_guest_access_restriction_setting', 'hide_prices') === 'replace_prices_quote') && (!is_user_logged_in() || (intval(get_option( 'b2bking_multisite_separate_b2bb2c_setting', 0 )) === 1 && get_user_meta($user_data_current_user_id, 'b2bking_b2buser', true) !== 'yes'))){
                wc_print_notice( esc_html__('While you have an offer / pack in cart, you cannot add products to quote', 'b2bking'), 'error' );
            }
        }

        ?>
        <div class="b2bking_bulkorder_container_final">
            <input type="hidden" class="b2bking_bulkorder_exclude" value="<?php echo esc_attr($exclude);?>">
            <input type="hidden" class="b2bking_bulkorder_product_list" value="<?php echo esc_attr($product_list);?>">
            <input type="hidden" class="b2bking_bulkorder_category" value="<?php echo esc_attr($category);?>">
            <input type="hidden" class="b2bking_bulkorder_tag" value="<?php echo esc_attr($tag);?>">
            <input type="hidden" class="b2bking_bulkorder_attributes" value="<?php echo esc_attr($attributes);?>">
            <input type="hidden" class="b2bking_bulkorder_sortby" value="<?php echo esc_attr($sortby);?>">
            <input type="hidden" class="b2bking_bulkorder_instock" value="<?php echo esc_attr($instock);?>">
            <?php

            /*
            if ($theme === 'classic'){     // Edited
                ?>
                <div class="b2bking_bulkorder_form_container">
                    <div class="b2bking_bulkorder_form_container_top">
                        <?php esc_html_e('Bulk Order Form', 'b2bking'); ?>
                    </div>
                    <div class="b2bking_bulkorder_form_container_content">
                        <div class="b2bking_bulkorder_form_container_content_header">
                            <?php do_action('b2bking_bulkorder_column_header_start'); ?>

                            <div class="b2bking_bulkorder_form_container_content_header_product">
                                <?php
                                if (intval(get_option( 'b2bking_search_by_sku_setting', 1 )) === 1){
                                    esc_html_e('Search by', 'b2bking');
                                    ob_start();
                                ?>
                                    <select id="b2bking_bulkorder_searchby_select">
                                        <option value="productname"><?php esc_html_e('Product Name', 'b2bking'); ?></option>
                                        <option value="sku"><?php 

                                        echo apply_filters('b2bking_sku_search_display', esc_html__('SKU', 'b2bking')); 

                                        ?></option>
                                    </select>
                                <?php 
                                $content = ob_get_clean();
                                echo apply_filters('b2bking_classic_form_searchby_display', $content);
                                } else {
                                    esc_html_e('Product name', 'b2bking');
                                }
                                ?>
                            </div>
                            <div class="b2bking_bulkorder_form_container_content_header_qty">
                                <?php esc_html_e('Qty', 'b2bking'); ?>
                            </div>
                            <?php do_action('b2bking_bulkorder_column_header_mid'); ?>
                            <div class="b2bking_bulkorder_form_container_content_header_subtotal">
                                <?php esc_html_e('Subtotal', 'b2bking'); ?>
                            </div>

                            <?php do_action('b2bking_bulkorder_column_header_end'); ?>

                        </div>

                        <?php
                        // show 5 lines of bulk order form
                        $lines = apply_filters('b2bking_bulkorder_lines_default', 5);
                        for ($i = 1; $i <= $lines; $i++){
                            ?>
                            <div class="b2bking_bulkorder_form_container_content_line"><input type="text" class="b2bking_bulkorder_form_container_content_line_product" <?php 

                            if ($i === 1){
                                echo 'placeholder="'.esc_attr__('Search for a product...','b2bking').'"';
                            }

                            ?>><input type="number" min="0" class="b2bking_bulkorder_form_container_content_line_qty b2bking_bulkorder_form_container_content_line_qty_classic" step="1"><?php do_action('b2bking_bulkorder_column_header_mid_content'); ?><div class="b2bking_bulkorder_form_container_content_line_subtotal"><?php 

                            if ($this->dynamic_replace_prices_with_quotes() === 'yes' || (get_option('b2bking_guest_access_restriction_setting', 'hide_prices') === 'replace_prices_quote') && (!is_user_logged_in() || (intval(get_option( 'b2bking_multisite_separate_b2bb2c_setting', 0 )) === 1 && get_user_meta($user_data_current_user_id, 'b2bking_b2buser', true) !== 'yes'))){
                                esc_html_e('Quote','b2bking');
                            } else {
                                if (intval(get_option( 'b2bking_show_accounting_subtotals_setting', 0 )) === 1){
                                    echo wc_price(0);
                                } else {
                                    echo get_woocommerce_currency_symbol().'0'; 
                                }
                            }

                            ?></div><?php do_action('b2bking_bulkorder_column_header_end_content'); ?><div class="b2bking_bulkorder_form_container_content_line_livesearch"></div></div>
                            <?php
                        }
                        ?>

                        <!-- new line button -->
                        <div class="b2bking_bulkorder_form_container_newline_container">
                            <button class="b2bking_bulkorder_form_container_newline_button">
                                <svg class="b2bking_bulkorder_form_container_newline_button_icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 22 22">
                                  <path fill="#fff" d="M11 1.375c-5.315 0-9.625 4.31-9.625 9.625s4.31 9.625 9.625 9.625 9.625-4.31 9.625-9.625S16.315 1.375 11 1.375zm4.125 10.14a.172.172 0 01-.172.172h-3.265v3.266a.172.172 0 01-.172.172h-1.032a.172.172 0 01-.171-.172v-3.265H7.046a.172.172 0 01-.172-.172v-1.032c0-.094.077-.171.172-.171h3.266V7.046c0-.095.077-.172.171-.172h1.032c.094 0 .171.077.171.172v3.266h3.266c.095 0 .172.077.172.171v1.032z"/>
                                </svg>
                                <?php esc_html_e('new line','b2bking'); ?>
                            </button>
                        </div>

                        <div class="b2bking_bulkorder_form_newline_template" style="display:none"><div class="b2bking_bulkorder_form_container_content_line"><input type="text" class="b2bking_bulkorder_form_container_content_line_product"><input type="number" min="0" step="1" class="b2bking_bulkorder_form_container_content_line_qty b2bking_bulkorder_form_container_content_line_qty_classic"><?php do_action('b2bking_bulkorder_column_header_mid_newline_content'); ?><div class="b2bking_bulkorder_form_container_content_line_subtotal">pricetext</div><div class="b2bking_bulkorder_form_container_content_line_livesearch"></div></div></div>

                        <!-- add to cart button -->
                        <div class="b2bking_bulkorder_form_container_bottom">
                            <!-- initialize hidden loader to get it to load instantly -->
                            <img class="b2bking_loader_hidden" src="<?php echo plugins_url('../includes/assets/images/loader.svg', __FILE__); ?>">
                            <img class="b2bking_loader_hidden" src="<?php echo plugins_url('../includes/assets/images/no_products.svg', __FILE__); ?>">
                            <img class="b2bking_loader_hidden" src="<?php echo plugins_url('../includes/assets/images/close.svg', __FILE__); ?>">
                            <img class="b2bking_loader_hidden" src="<?php echo plugins_url('../includes/assets/images/filter.svg', __FILE__); ?>">

                            <div class="b2bking_bulkorder_form_container_bottom_add">
                                <button class="b2bking_bulkorder_form_container_bottom_add_button" type="button">
                                    <svg class="b2bking_bulkorder_form_container_bottom_add_button_icon" xmlns="http://www.w3.org/2000/svg" width="21" height="19" fill="none" viewBox="0 0 21 19">
                                      <path fill="#fff" d="M18.401 11.875H7.714l.238 1.188h9.786c.562 0 .978.53.854 1.087l-.202.901a2.082 2.082 0 011.152 1.87c0 1.159-.93 2.096-2.072 2.079-1.087-.016-1.981-.914-2.01-2.02a2.091 2.091 0 01.612-1.543H8.428c.379.378.614.903.614 1.485 0 1.18-.967 2.131-2.14 2.076-1.04-.05-1.886-.905-1.94-1.964a2.085 2.085 0 011.022-1.914L3.423 2.375H.875A.883.883 0 010 1.485V.89C0 .399.392 0 .875 0h3.738c.416 0 .774.298.857.712l.334 1.663h14.32c.562 0 .978.53.854 1.088l-1.724 7.719a.878.878 0 01-.853.693zm-3.526-5.64h-1.75V4.75a.589.589 0 00-.583-.594h-.584a.589.589 0 00-.583.594v1.484h-1.75a.589.589 0 00-.583.594v.594c0 .328.26.594.583.594h1.75V9.5c0 .328.261.594.583.594h.584a.589.589 0 00.583-.594V8.016h1.75a.589.589 0 00.583-.594v-.594a.589.589 0 00-.583-.594z"/>
                                    </svg>
                                <?php 

                                if ($this->dynamic_replace_prices_with_quotes() === 'yes' || (get_option('b2bking_guest_access_restriction_setting', 'hide_prices') === 'replace_prices_quote') && (!is_user_logged_in() || (intval(get_option( 'b2bking_multisite_separate_b2bb2c_setting', 0 )) === 1 && get_user_meta($user_data_current_user_id, 'b2bking_b2buser', true) !== 'yes'))){
                                    esc_html_e('Add to Quote','b2bking'); 
                                } else {
                                    esc_html_e('Add to Cart','b2bking');    
                                }
                                

                                ?>
                                </button>
                                <?php if (intval(get_option('b2bking_enable_purchase_lists_setting', 1)) === 1){ ?>
                                    <button class="b2bking_bulkorder_form_container_bottom_save_button" type="button">
                                        <svg class="b2bking_bulkorder_form_container_bottom_save_button_icon" xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 22 22">
                                          <path fill="#fff" d="M9.778 4.889h7.333v2.444H9.778V4.89zm0 4.889h7.333v2.444H9.778V9.778zm0 4.889h7.333v2.444H9.778v-2.444zm-4.89-9.778h2.445v2.444H4.89V4.89zm0 4.889h2.445v2.444H4.89V9.778zm0 4.889h2.445v2.444H4.89v-2.444zM20.9 0H1.1C.489 0 0 .489 0 1.1v19.8c0 .489.489 1.1 1.1 1.1h19.8c.489 0 1.1-.611 1.1-1.1V1.1c0-.611-.611-1.1-1.1-1.1zm-1.344 19.556H2.444V2.444h17.112v17.112z"/>
                                        </svg>
                                    <?php esc_html_e('Save list','b2bking'); ?>
                                    </button>
                                <?php } ?>

                            </div>
                            <div class="b2bking_bulkorder_form_container_bottom_total">
                                <?php
                                if ($this->dynamic_replace_prices_with_quotes() === 'yes' || (get_option('b2bking_guest_access_restriction_setting', 'hide_prices') === 'replace_prices_quote') && (!is_user_logged_in() || (intval(get_option( 'b2bking_multisite_separate_b2bb2c_setting', 0 )) === 1 && get_user_meta($user_data_current_user_id, 'b2bking_b2buser', true) !== 'yes'))){

                                } else {
                                    ?>
                                        <?php esc_html_e('Total: ','b2bking'); ?><strong><?php echo wc_price(0);?></strong>
                                    <?php   
                                }?>
                                
                            </div>
                        </div>


                    </div>
                </div>
                <?php
            }

            if ($theme === 'indigo'){


                ?>
                <div class="b2bking_bulkorder_form_container b2bking_bulkorder_form_container_indigo">

                    <div class="b2bking_bulkorder_form_container_content_header_top">
                        <input type="text" id="b2bking_bulkorder_search_text_indigoid" class="b2bking_bulkorder_search_text_indigo" placeholder="<?php esc_html_e('Search products...','b2bking');?>">
                    </div>


                    <div class="b2bking_bulkorder_form_container_top b2bking_bulkorder_form_container_top_indigo">
                        <?php do_action('b2bking_bulkorder_column_header_start'); ?>

                        <div class="b2bking_bulkorder_form_container_content_header_product b2bking_bulkorder_form_container_content_header_product_indigo">
                            <?php esc_html_e('Product', 'b2bking'); ?>
                        </div>
                        <div class="b2bking_bulkorder_form_container_content_header_qty b2bking_bulkorder_form_container_content_header_qty_indigo">
                            <?php esc_html_e('Qty', 'b2bking'); ?>
                        </div>
                        <?php do_action('b2bking_bulkorder_column_header_mid'); ?>
                        <div class="b2bking_bulkorder_form_container_content_header_subtotal b2bking_bulkorder_form_container_content_header_subtotal_indigo">
                            <?php esc_html_e('Subtotal', 'b2bking'); ?>
                        </div>
                        <div class="b2bking_bulkorder_form_container_content_header_subtotal b2bking_bulkorder_form_container_content_header_cart_indigo">
                            <?php esc_html_e('Cart', 'b2bking'); ?>
                        </div>
                        <?php do_action('b2bking_bulkorder_column_header_end'); ?>
                    </div>


                    <div class="b2bking_bulkorder_form_container_content b2bking_bulkorder_form_container_content_indigo">

                        <input type="hidden" id="b2bking_indigo_order_form" value="1">
                        <!-- initialize hidden loader to get it to load instantly -->
                        <img class="b2bking_loader_hidden" src="<?php echo plugins_url('../includes/assets/images/loader.svg', __FILE__); ?>">
                        <img class="b2bking_loader_hidden" src="<?php echo plugins_url('../includes/assets/images/no_products.svg', __FILE__); ?>">
                        <img class="b2bking_loader_hidden" src="<?php echo plugins_url('../includes/assets/images/close.svg', __FILE__); ?>">
                        <img class="b2bking_loader_hidden" src="<?php echo plugins_url('../includes/assets/images/filter.svg', __FILE__); ?>">


                    </div>
                </div>
                <?php
            }*/

            if ($theme === 'cream'){

                $hidecolumns = apply_filters('b2bking_cream_hidecolumns_cream', $hidecolumns);

                /*if ($style === 'nameqty'){ // 2 columns, name, qty      // Edited
                    $hidecolumns[] = 'subtotal';
                    $hidecolumns[] = 'cart';
                    // $hidecolumns[] = 'multiselect';
                    $hidecolumns = apply_filters('b2bking_cream_hidecolumns_nameqty', $hidecolumns);
                    ?>
                    <style>
                        .b2bking_bulkorder_indigo_product_container, .b2bking_cream_input_group, .b2bking_bulkorder_form_container_content_header_product.b2bking_bulkorder_form_container_content_header_product_indigo.b2bking_bulkorder_form_container_content_header_product_cream, .b2bking_bulkorder_form_container_content_header_qty.b2bking_bulkorder_form_container_content_header_qty_indigo.b2bking_bulkorder_form_container_content_header_qty_cream, .b2bking_bulkorder_indigo_product_container.b2bking_bulkorder_cream_product_container{
                            width:50% !important;
                        }
                        .b2bking_bulkorder_form_container_top.b2bking_bulkorder_form_container_top_indigo.b2bking_bulkorder_form_container_top_cream {
                            padding-right: 0px;
                        }
                        .b2bking_bulkorder_form_container_content_header_qty.b2bking_bulkorder_form_container_content_header_qty_indigo.b2bking_bulkorder_form_container_content_header_qty_cream{
                            margin-right: 5% !important;
                        }
                        #b2bking_cream_add_selected.active {
                            background: #000;
                        }
                        #b2bking_cream_add_selected.active:hover {
                            background: #3d3d3d !important;
                        }
                        .b2bking_bulkorder_form_container {
                            min-width: 300px;
                            max-width: 550px;
                        }
                        .b2bking_bulkorder_form_container_content_line_indigo.b2bking_bulkorder_form_container_content_line_cream {
                            min-height: 66px !important;
                        }
                        
                    </style>
                    <?php
                }*/

                if (in_array('image', $hidecolumns)){
                    ?>
                    <style>
                        img.b2bking_bulkorder_indigo_image.b2bking_bulkorder_cream_image {
                            display: none !important;
                        }
                    </style>
                    <?php
                }

                if (in_array('subtotal', $hidecolumns)){
                    ?>
                    <style>
                        .b2bking_bulkorder_form_container_content_header_subtotal_cream, .b2bking_bulkorder_form_container_content_line_subtotal{
                            display: none !important;
                        }
                    </style>
                    <?php
                }
                if (in_array('cart', $hidecolumns)){
                    ?>
                    <style>
                        .b2bking_bulkorder_form_container_content_header_cart_cream, .b2bking_bulkorder_cream_add, .b2bking_bulkorder_form_container_content_line_cart_cream, .b2bking_bulkorder_form_container_content_header_subtotal.b2bking_bulkorder_form_container_content_header_cart_indigo.b2bking_bulkorder_form_container_content_header_cart_cream{
                            display: none !important;
                        }
                    </style>
                    <?php
                }
                if (in_array('multiselect', $hidecolumns)){
                    ?>
                    <style>
                        .b2bking_bulkorder_form_container_content_multiselect_cream{
                            display: none !important;
                        }
                    </style>
                    <?php
                }


                ?>
                <div class="b2bking_bulkorder_form_container <?php echo 'multiselect_'.esc_attr($multiselect).' '; ?><?php echo esc_attr($width); ?> b2bking_bulkorder_form_container_indigo b2bking_bulkorder_form_container_cream b2bking_orderform_<?php echo get_option( 'b2bking_order_form_creme_cart_button_setting', 'cart' );?>_container <?php 
                    if ($showsku === 'yes' || $showstock === 'yes'){
                        echo 'b2bking_has_extra_column';
                    }

                    ?> nonadaptive">

                    <div class="b2bking_bulkorder_cream_header_container">
                        <div class="b2bking_bulkorder_form_container_content_header_top b2bking_bulkorder_form_container_content_header_top_cream b2bking_orderform_filters">
                            <span class="b2bking_bulkorder_search_text_cream"><?php esc_html_e('Filters','b2bking');?></span>
                            <div id="b2bking_bulkorder_cream_filter_icon">
                                <img src="<?php echo plugins_url('includes/assets/images/filter.svg', $b2b_plugin_file); ?>">                                
                            </div>
                        </div>
                        <?php
                        if ($attributes !== 'no'){
                            ?>
                            <div class="b2bking_bulkorder_form_container_content_header_top b2bking_bulkorder_form_container_content_header_top_cream b2bking_orderform_attributes">
                                <span class="b2bking_bulkorder_search_text_cream"><?php echo esc_html($attributestext); ?></span>
                                <div id="b2bking_bulkorder_cream_filter_icon_attributes">
                                    <img src="<?php echo plugins_url('includes/assets/images/attributes.svg', $b2b_plugin_file); ?>">
                                </div>
                            </div>
                            <?php
                        }

                        ?>

                        <div class="b2bking_bulkorder_form_container_content_header_top b2bking_bulkorder_form_container_content_header_top_cream">
                            <input type="text" id="b2bking_bulkorder_search_text_indigoid" class="b2bking_bulkorder_search_text_indigo b2bking_bulkorder_search_text_cream" placeholder="<?php esc_html_e('Search products...','b2bking');?>">
                            <div class="b2bking_bulkorder_cream_search_icon b2bking_bulkorder_cream_search_icon_show b2bking_bulkorder_cream_search_icon_search">
                                <img src="<?php echo plugins_url('includes/assets/images/search.svg', $b2b_plugin_file); ?>">
                            </div>
                            <div class="b2bking_bulkorder_cream_search_icon b2bking_bulkorder_cream_search_icon_hide b2bking_bulkorder_cream_search_icon_clear">
                                <img src="<?php echo plugins_url('includes/assets/images/clear.svg', $b2b_plugin_file); ?>">
                            </div>
                        </div>

                        <?php

                        if (is_object( WC()->cart )){
                            $cartcount = WC()->cart->get_cart_contents_count();
                            $cartsubtotal = WC()->cart->get_cart_subtotal();
                        } else {
                            $cartcount = 0;
                            $cartsubtotal = 0;
                        }

                        ?>

                        <div class="b2bking_bulkorder_form_container_content_header_top b2bking_bulkorder_form_container_content_header_top_cream b2bking_orderform_<?php echo get_option( 'b2bking_order_form_creme_cart_button_setting', 'cart' );?> b2bking_orderform_<?php if ($cartcount == 0) {echo get_option( 'b2bking_order_form_creme_cart_button_setting', 'cart' ).'_inactive'; } ?>">
                            <div id="b2bking_bulkorder_cream_cart_icon">
                                <img src="<?php echo plugins_url('includes/assets/images/cart.svg', $b2b_plugin_file); ?>">
                            </div>
                            <div id="b2bking_bulkorder_cream_filter_cart_text">
                                <?php 


                                    if (get_option( 'b2bking_order_form_creme_cart_button_setting', 'cart' ) === 'cart'){
                                        echo '<span class="b2bking_cream_cart_button_price">'.$cartsubtotal.' </span><span class="b2bking_cream_cart_button_items"><span class="b2bking_cream_cart_button_items_qty">'.$cartcount.'</span>'.' '.esc_html__('items','b2bking').'</span>';
                                    }

                                    if (get_option( 'b2bking_order_form_creme_cart_button_setting', 'cart' ) === 'carticon'){
                                        echo $cartcount; 
                                    }

                                    if (get_option( 'b2bking_order_form_creme_cart_button_setting', 'cart' ) === 'checkout'){
                                        esc_html_e('Checkout','b2bking');
                                    }

                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="b2bking_bulkorder_form_cream_main_container b2bking_filters_open">
                        <div class="b2bking_bulkorder_form_container_cream_filters b2bking_filters_open">
                            <div class="b2bking_bulkorder_form_container_cream_filters_content_first">
                                <div class="b2bking_bulkorder_filter_header b2bking_bulkorder_filter_header_sortby <?php 
                                $skipsort = apply_filters('b2bking_bulkorder_skip_sort', false);
                                if ($skipsort){
                                    echo 'b2bking_bulkorder_filters_list_sortby_hidden';
                                }
                                ?>"><?php esc_html_e('Sort By','b2bking');?></div>
                                <ul class="b2bking_bulkorder_filters_list_sortby <?php 
                                if ($skipsort){
                                    echo 'b2bking_bulkorder_filters_list_sortby_hidden';
                                }
                                ?>">
                                    <?php
                                    $available_sort_options = apply_filters('b2bking_bulkorder_sorting_options', array('automatic','bestselling', 'latest', 'atoz','ztoa'));

                                    if (in_array('automatic', $available_sort_options)){
                                        ?>
                                        <li value="automatic" <?php if ($sortby === 'automatic'){ echo 'style="text-decoration:underline;"';} ?>><?php esc_html_e('Automatic','b2bking');?></li>
                                        <?php
                                    }

                                    if (in_array('bestselling', $available_sort_options)){
                                        ?>
                                        <li value="bestselling" <?php if ($sortby === 'bestselling'){ echo 'style="text-decoration:underline;"';} ?>><?php esc_html_e('Best Selling','b2bking');?></li>
                                        <?php
                                    }

                                    if (in_array('latest', $available_sort_options)){
                                        ?>
                                        <li value="latest" <?php if ($sortby === 'latest'){ echo 'style="text-decoration:underline;"';} ?>><?php esc_html_e('Latest','b2bking');?></li>
                                        <?php
                                    }

                                    if (in_array('atoz', $available_sort_options)){
                                        ?>
                                        <li value="atoz" <?php if ($sortby === 'atoz'){ echo 'style="text-decoration:underline;"';} ?>><?php esc_html_e('Alphabetically, A->Z','b2bking');?></li>
                                        <?php
                                    }

                                    if (in_array('ztoa', $available_sort_options)){
                                        ?>
                                        <li value="ztoa" <?php if ($sortby === 'ztoa'){ echo 'style="text-decoration:underline;"';} ?>><?php esc_html_e('Alphabetically, Z->A','b2bking');?></li>
                                        <?php
                                    }
                                    ?>
                                </ul>
                                <div class="b2bking_categories_header_separator <?php 
                                if ($skipsort){
                                    echo 'b2bking_bulkorder_filters_list_sortby_hidden';
                                }
                                ?>"></div>
                                <?php
                                if (apply_filters('b2bking_bulkorder_instock_filter', false)){
                                    ?>
                                    <div class="b2bking_bulkorder_filter_header b2bking_bulkorder_filter_instock"><?php esc_html_e('In Stock','b2bking');?></div>
                                    <ul class="b2bking_bulkorder_filters_list_instock">
                                        <?php
                                        if (apply_filters('b2bking_bulkorder_instock_filter_default_all', true)){
                                            ?>
                                            <li value="all" style="text-decoration:underline;"><?php esc_html_e('View All', 'b2bking'); ?></li>
                                            <?php
                                        }
                                        ?>
                                        <li value="instock" <?php if (!apply_filters('b2bking_bulkorder_instock_filter_default_all', true)){echo 'style="text-decoration:underline;"'; } ?>><?php esc_html_e('In Stock', 'b2bking'); ?></li>
                                        <li value="outofstock"><?php esc_html_e('Out of Stock', 'b2bking'); ?></li>
                                    </ul>
                                    <div class="b2bking_categories_header_separator"></div>
                                    <?php
                                }
                                ?>
                                <div class="b2bking_bulkorder_filter_header b2bking_bulkorder_filter_header_categories <?php if ($category !== 0){

                                    if (!apply_filters('b2bking_bulkorder_category_shortcode_show_subcategories', false)){
                                        echo 'b2bking_categories_orderform_hidden';
                                    }

                                } ?>"><?php echo apply_filters('b2bking_creamform_taxonomy_filter_name', esc_html__('Categories','b2bking'));?></div>
                                <ul class="b2bking_bulkorder_filters_list <?php if ($category !== 0){

                                    if (!apply_filters('b2bking_bulkorder_category_shortcode_show_subcategories', false)){
                                        echo 'b2bking_categories_orderform_hidden';
                                    }
                                } ?>">
                                    <?php

                                    if (!apply_filters('b2bking_bulkorder_category_shortcode_show_subcategories', false)){
                                        ?>
                                        <!-- <li value="0"  <?php //if (intval($category) === 0){ echo 'style="text-decoration:underline;"';} ?>><?php //esc_html_e('All Products','b2bking');?></li> -->
                                        
                                        <li class="menu-heading"><?php esc_html_e('Finished Products','b2bking');?></li>
                                        <?php                                        
                                        $finished_products = wc_get_products([
                                            'status' => 'publish',
                                            'limit' => -1,
                                            'category' => ['finished-products']
                                        ]);

                                        foreach ($finished_products as $key=>$finished) { 
                                            $class = ($key == 0)? 'first-finished-product' : ''; ?>                                                
                                            <li value=0 product-id="<?php echo $finished->get_id(); ?>" class="<?php echo $class; ?>" >&nbsp; -<?php echo esc_html($finished->get_name()); ?></li>
                                            <?php
                                        } ?>
                                        <li class="menu-heading" value=-1>Parts & Components</li> <!-- Edited -->
                                        <?php 
                                        $parts_parent_term = get_term_by( 'slug', 'parts-components', 'product_cat' );
                                        $parts_child_terms = get_terms( array(
                                            'taxonomy' => 'product_cat',
                                            'parent'   => $parts_parent_term->term_id,
                                            'hide_empty' => false,
                                        ) );
                                        foreach ($parts_child_terms as $parts_child_term) { ?>
                                            <li class="default-category parts-category-menu" value="<?php echo esc_attr($parts_child_term->term_id); ?>">
                                                &nbsp - <?php echo esc_html($parts_child_term->name); ?>
                                            </li>
                                            <?php
                                        } ?>

                                        <li class="menu-heading" value=-1>Extrusions</li> <!-- Edited -->
                                        <?php 
                                        $extrusions_parent_term = get_term_by( 'slug', 'extrusions', 'product_cat' );
                                        $extrusions_child_terms = get_terms( array(
                                            'taxonomy' => 'product_cat',
                                            'parent'   => $extrusions_parent_term->term_id,
                                            'hide_empty' => false,
                                        ) );                                        
                                        foreach ($extrusions_child_terms as $extrusions_child_term) { ?>
                                            <li class="default-category parts-category-menu" value="<?php echo esc_attr($extrusions_child_term->term_id); ?>">
                                                &nbsp - <?php echo esc_html($extrusions_child_term->name); ?>
                                            </li>
                                            <?php
                                        }

                                    }

                                $exclude_ids = explode(',', $exclude);
                                
                                $exclude_ids_categories = array(21, 23);
                                foreach($exclude_ids as $exclude_option){
                                    $exclude = explode('_',$exclude_option);
                                    
                                    if ($exclude[0] === 'category'){
                                        $cat_id = $exclude[1];
                                        array_push($exclude_ids_categories, $cat_id);
                                    }
                                }


                                if (!function_exists('buildCategoryHierarchy')) {
                                    function buildCategoryHierarchy($categories) {
                                        $items = [];
                                        foreach ($categories as $category) {
                                            $items[$category->term_id] = $category;
                                            $items[$category->term_id]->children = [];
                                        }

                                        $tree = [];
                                        foreach ($items as $item) {
                                            if ($item->category_parent == 0) {
                                                $tree[$item->term_id] = &$items[$item->term_id];
                                            } else {
                                                if (isset($items[$item->category_parent]->children)){
                                                    $items[$item->category_parent]->children[$item->term_id] = &$items[$item->term_id];
                                                }
                                            }
                                        }

                                        return $tree;
                                    }
                                }

                                if (!function_exists('flattenCategoryHierarchy')) {
                                    function flattenCategoryHierarchy($tree, &$result = []) {
                                        foreach ($tree as $item) {
                                            $result[] = $item;
                                            if (!empty($item->children)) {
                                                flattenCategoryHierarchy($item->children, $result);
                                            }
                                        }
                                        return $result;
                                    }
                                }

                                // Retrieve categories
                                $categories = get_categories(array('hide_empty' => apply_filters('b2bking_orderform_hide_empty_categories', false), 'taxonomy' => apply_filters('b2bking_creamform_taxonomy_filter', 'product_cat')));

                                // hide categories if visibility is enabled and the category is explicitly hidden for the current user group / username
                                if (intval(get_option('b2bking_disable_visibility_setting', 0)) === 0){
                                    if (intval(get_option( 'b2bking_all_products_visible_all_users_setting', 1 )) !== 1){

                                        $currentuserid = get_current_user_id();
                                        $account_type = get_user_meta($currentuserid,'b2bking_account_type', true);
                                        if ($account_type === 'subaccount'){
                                            // for all intents and purposes set current user as the subaccount parent
                                            $parent_user_id = get_user_meta($currentuserid, 'b2bking_account_parent', true);
                                            $currentuserid = $parent_user_id;
                                        }
                                        $currentusergroupidnr = b2bking()->get_user_group($currentuserid);

                                        // if user is B2C, set to B2C
                                        if (get_user_meta($currentuserid,'b2bking_b2buser', true) !== 'yes'){
                                            $currentusergroupidnr = 'b2c';
                                        }
                                        if (!is_user_logged_in()){
                                            $currentusergroupidnr = 0;
                                        }

                                        $currentuser = wp_get_current_user();
                                        // if user is guest, set to 0
                                        if ($currentuser === false){
                                            $currentuserlogin = 0;
                                        } else {
                                            $currentuserlogin = $currentuser -> user_login;
                                        }


                                        foreach ($categories as $index => $term){
                                            $has_enabled = false;
                                            
                                            $group_meta = get_term_meta( $term->term_id, 'b2bking_group_'.$currentusergroupidnr, true );
                                            if (intval($group_meta) === 1){
                                                $has_enabled = true;
                                            // else check user
                                            } else {
                                                $userlistcommas = get_term_meta( $term->term_id, 'b2bking_category_users_textarea', true );
                                                $userarray = explode(',', $userlistcommas);
                                                $visible = 'no';
                                                foreach ($userarray as $user){
                                                    if (trim($user) === $currentuserlogin){
                                                        $has_enabled = true;
                                                        break;
                                                    }
                                                }
                                            }

                                            if (!$has_enabled){
                                                unset($categories[$index]);
                                            }
                                        }
                                        
                                    }
                                }

                                // Build hierarchy and flatten it back into a list
                                $tree = buildCategoryHierarchy($categories);
                                $sortedCategories = flattenCategoryHierarchy($tree);
                                $categories = $sortedCategories;
                                // Use $sortedCategories for sorted list of categories

                                foreach ($categories as $cat) {
                                    // Check if we're filtering for a specific category and its subcategories
                                    $show_category = true;
                                    
                                    if ($category !== 0 && apply_filters('b2bking_bulkorder_category_shortcode_show_subcategories', false)) {
                                        // Only show the selected category and its subcategories
                                        $is_selected_category = (intval($cat->term_id) === intval($category));
                                        $is_subcategory = false;
                                        
                                        // Check if this is a subcategory of the selected category
                                        $parent_id = $cat->category_parent;
                                        while ($parent_id != 0) {
                                            if (intval($parent_id) === intval($category)) {
                                                $is_subcategory = true;
                                                break;
                                            }
                                            $parent_term = get_term($parent_id);
                                            $parent_id = $parent_term->parent;
                                        }
                                        
                                        // Only show if it's the selected category or its subcategory
                                        $show_category = $is_selected_category || $is_subcategory;
                                    }
                                    
                                    // Proceed only if we should show this category
                                    if ($show_category) {
                                        // remove excluded categories 
                                        if (!in_array($cat->term_id, $exclude_ids_categories)) {
                                            if (apply_filters('b2bking_bulkorder_category_hierarchical', true)) {
                                                // allow setting a limit on number of categories children shown
                                                $parents = 0;
                                                $parentcat = $cat->category_parent;
                                                while ($parentcat != 0) {
                                                    $parents++;
                                                    $newparent = get_term($parentcat);
                                                    $parentcat = $newparent->parent;
                                                }
                                                $levels_limit = apply_filters('b2bking_bulkorder_categories_hierarchy_levels_limit', 2);
                                                if ($parents > $levels_limit) {
                                                    continue;
                                                }
                                            }
                                            //if ( $cat->parent == 0 ) {  // Edited
                                            $ancestors = get_ancestors($cat->term_id, 'product_cat');
                                            if (count($ancestors) === 1) { /*
                                                ?>
                                                <li class="default-category parts-category-menu" value="<?php echo esc_attr($cat->term_id); ?>" <?php if (intval($category) === intval($cat->term_id)){ echo 'style="text-decoration:underline;"';} ?>>
                                                    <?php
                                                    // show category hierarchy or not
                                                    if (apply_filters('b2bking_bulkorder_category_hierarchical', true)) {
                                                        while ($parents > 0) {
                                                            echo '—';
                                                            $parents--;
                                                        }
                                                    }
                                                    echo esc_html($cat->name);
                                                    ?>
                                                </li>
                                                <?php */
                                            }
                                        }
                                    }
                                }
                                
                                ?>
                                </ul>                                
                            </div>
                            <div class="b2bking_bulkorder_form_container_cream_filters_content_second">
                                <?php
                                if ($attributes !== 'no'){
                                    $attributes_slugs = explode(',', $attributes);
                                    $attributes_slugs = array_map('trim', $attributes_slugs);

                                    foreach ($attributes_slugs as $slug){
                                        if (!empty($slug)){
                                            $attribute_taxonomy   = 'pa_' . $slug; 

                                            ?>
                                            <div class="b2bking_bulkorder_filter_header <?php echo esc_attr($attribute_taxonomy); ?>"><?php echo wc_attribute_label( $attribute_taxonomy ); ?></div>
                                                <ul class="b2bking_bulkorder_filters_list_attributes <?php echo esc_attr($attribute_taxonomy); ?>">
                                                    <input type="hidden" class="b2bking_attribute_value b2bking_attribute_value_<?php echo esc_attr($slug);?>" value="0">
                                                    <?php

                                                    $terms = get_terms( array(
                                                        'taxonomy'   => $attribute_taxonomy,
                                                        'hide_empty' => false,
                                                    ) );
                                                    ?>

                                                    <li value="0" <?php echo 'style="text-decoration:underline;"'; ?>><?php esc_html_e('View All','b2bking');?></li>

                                                    <?php

                                                    foreach ( $terms as $term ) {
                                                        ?>
                                                        <li value="<?php echo esc_attr($term->term_id); ?>"><?php echo '— '.esc_html($term->name);?></li>
                                                        <?php
                                                    }
                                                    
                                                    ?>
                                                </ul>
                                                <div class="b2bking_categories_header_separator"></div>
                                            <?php
                                        }
                                        
                                    }
                                    
                                }
                                ?>
                            </div>
                            
                        </div>
                        <div class="b2bking_bulkorder_form_cream_main_container_content b2bking_filters_open">
                            <div class="b2bking_bulkorder_before_top_cream"></div>
                            <div class="b2bking_bulkorder_form_container_top b2bking_bulkorder_form_container_top_indigo b2bking_bulkorder_form_container_top_cream">
                                <?php do_action('b2bking_bulkorder_column_header_start'); ?>

                                <div class="b2bking_bulkorder_form_container_content_header_product b2bking_bulkorder_form_container_content_header_product_indigo b2bking_bulkorder_form_container_content_header_product_cream">
                                    <?php esc_html_e('Product', 'b2bking'); ?>
                                </div>
                                <?php
                                if ($showsku === 'yes'){
                                    ?>
                                    <div class="b2bking_bulkorder_form_container_content_header_cream_sku">
                                        <?php esc_html_e('SKU', 'b2bking'); ?>
                                    </div>
                                    <input type="hidden" class="b2bking_order_form_show_sku" value="yes">
                                    <?php
                                }
                                if ($showstock === 'yes'){
                                    ?>
                                    <div class="b2bking_bulkorder_form_container_content_header_cream_stock">
                                        <?php esc_html_e('In Stock', 'b2bking'); ?>
                                    </div>
                                    <input type="hidden" class="b2bking_order_form_show_stock" value="yes">
                                    <?php
                                }

                                do_action('b2bking_bulkorder_cream_custom_heading');
                                ?>
                                <div class="b2bking_bulkorder_form_container_content_header_qty b2bking_bulkorder_form_container_content_header_qty_indigo b2bking_bulkorder_form_container_content_header_qty_cream">
                                    <?php esc_html_e('Qty', 'b2bking'); ?>
                                </div>
                                <?php do_action('b2bking_bulkorder_column_header_mid'); ?>
                                <div class="b2bking_bulkorder_form_container_content_header_subtotal b2bking_bulkorder_form_container_content_header_subtotal_indigo b2bking_bulkorder_form_container_content_header_subtotal_cream">
                                    <?php esc_html_e('Subtotal', 'b2bking'); ?>
                                </div>
                                <div class="b2bking_bulkorder_form_container_content_header_subtotal b2bking_bulkorder_form_container_content_header_cart_indigo b2bking_bulkorder_form_container_content_header_cart_cream">
                                    <?php 

                                    if (apply_filters('b2bking_cream_hide_individual_addtocart', false)){
                                        esc_html_e('Select', 'b2bking'); 
                                    } else {
                                        esc_html_e('Cart', 'b2bking'); 
                                    }

                                    ?>
                                </div>
                                <!--<div class="b2bking_bulkorder_form_container_content_header_bulkorder b2bking_bulkorder_form_container_content_header_bulkorder_indigo b2bking_bulkorder_form_container_content_header_bulkorder_cream">
                                    <?php //esc_html_e('Bulk Order', 'b2bking'); ?>
                                </div>-->
                                <?php
                                    if ($multiselect === 'yes'){
                                        ?>
                                        <div class="b2bking_bulkorder_form_container_content_header_multiselect_cream <?php if (apply_filters('b2bking_cream_hide_individual_addtocart', false)){ echo 'b2bking_hidden_img'; }?>">
                                            <!-- <input type="checkbox" class="b2bking_cream_select_checkbox_all"> -->
                                        </div>
                                        <?php
                                    }
                                ?>
                                <?php do_action('b2bking_bulkorder_column_header_end'); ?>
                            </div>
                            <div class="b2bking_bulkorder_form_container_content b2bking_bulkorder_form_container_content_indigo b2bking_bulkorder_form_container_content_cream">

                                <input type="hidden" id="b2bking_indigo_order_form" class="b2bking_cream_order_form" value="1">
                                <!-- initialize hidden loader to get it to load instantly -->
                                <img class="b2bking_loader_hidden" src="<?php echo plugins_url('includes/assets/images/loader.svg', $b2b_plugin_file); ?>">
                                <img class="b2bking_loader_hidden" src="<?php echo plugins_url('includes/assets/images/no_products.svg', $b2b_plugin_file); ?>">
                                <img class="b2bking_loader_hidden" src="<?php echo plugins_url('includes/assets/images/close.svg', $b2b_plugin_file); ?>">
                                <img class="b2bking_loader_hidden" src="<?php echo plugins_url('includes/assets/images/filter.svg', $b2b_plugin_file); ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>
        </div>
        <?php

        $content = ob_get_clean();
        return apply_filters('b2bking_bulkorder_content', $content);
    }


    /*
    * Loads only single Finished product on right side
    */    
    public function pass_single_product_id_to_display_only_that_product( $product_ids ) {
        $selectedProductID = $_POST['selectedProductID']; 
        
        // Get the first product from finished product category for default display on page load
        $finished_products = wc_get_products([
            'status' => 'publish',
            'limit' => 1,
            'category' => ['finished-products']
        ]);
        $first_finished_product_id = $finished_products[0]->get_id();
        
        $final_product_id = ($selectedProductID != 0) ? $selectedProductID : $first_finished_product_id;        
        
        if($_POST['searchValue']){ // when searching products
            return $product_ids; 
        }else{
            return [$final_product_id];  // when clicking category on left panel
        }        
    } 



    /*
    * Loads parts-components content
    */
    public function load_products_by_category() {
        $category_id = intval($_POST['category_id']);

        //if($category_id != 23){ return false; }        

        $finished_category = get_term_by('slug', 'finished-products', 'product_cat');
        $finished_products = wc_get_products([
            'status' => 'publish',
            'limit' => -1,
            'category' => [$finished_category->slug]
        ]);

        $parts_parent = get_term_by('slug', 'parts-components', 'product_cat');
        $child_terms = get_terms([
            'taxonomy' => 'product_cat',
            'parent' => $category_id, //$parts_parent->term_id,
            'hide_empty' => false
        ]);

        ob_start();

        echo '<div class="part-product-loading" style="display:none;">Loading....</div>';

        if(!empty($child_terms)):
            foreach ($finished_products as $finished) {            

                // Find all parts child terms linked to this finished product
                $linked_child_terms = [];
                if(!empty($child_terms)){
                    foreach ($child_terms as $term) {
                        $related_finished_product_id = get_field('related_finished_product', 'product_cat_' . $category_id /*$term->term_id*/);
                        if ($related_finished_product_id) {                    
                                if ($related_finished_product_id == $finished->get_id()) {
                                    $linked_child_terms[] = $term;                            
                                }                    
                        }
                    }  
                }         
                
                // Dropdown for linked parts categories
                if (!empty($linked_child_terms)) {
                    echo '<div class="finished-product-row">';
                    echo '<h3>' . esc_html($finished->get_name()) . '</h3>';
                    echo '<div class="parts-term-filter">Filter: ';
                    echo '<select class="parts-category-dropdown" data-finished="' . esc_attr($finished->get_id()) . '">';
                    foreach ($linked_child_terms as $term) {
                        echo '<option value="' . esc_attr($term->term_id) . '">' . esc_html($term->name) . '</option>';
                    }
                    echo '</select></div>';                
                    
                    // Load the default category parts (first child category)
                    if (!empty($linked_child_terms)) {
                        $default_cat = $linked_child_terms[0];
                        echo $this->render_parts_products_html($default_cat, $finished->get_id());
                    } 
                    echo '</div>';  
                }         
            }
        else:
            echo '<div class="finished-product-row">';
            $related_finished_product_id = get_field('related_finished_product', 'product_cat_' . $category_id );
            if($related_finished_product_id){
                $related_finished_product = wc_get_product( $related_finished_product_id );
                echo '<h3>' . esc_html($related_finished_product->get_name()) . '</h3>';
            }else{
                $term = get_term( $category_id, 'product_cat');
                echo '<h3>' . $term->name . '</h3>';
            } 
            echo $this->render_parts_products_html($category_id, null);  
            echo '</div>';
        endif;    



        echo '<button id="add-all-to-cart-btn" data-finished="' . esc_attr($finished_product_id) . '">
                <img class="b2bking_cream_add_selected_cart_icon" src="'.plugin_dir_url( dirname( __FILE__ ) ) . 'assets/images/cart.svg'.'"> Add all selected items to Order
              </button>';

        wp_send_json_success(['html' => ob_get_clean()]);
    }


    /*
    * Loading parts products on changing child term dropdown
    */
    public function load_parts_products_by_category() {  
        $cat_id = intval($_POST['category_id']);
        $finished_id = intval($_POST['finished_id']);

        if (!$cat_id) {
            wp_send_json_error(['html' => '<p>Invalid category.</p>']);
        }

        $html = $this->render_parts_products_html($cat_id, $finished_id);

        wp_send_json_success(['html' => $html]);
    }


   
    /**
     * Common function for generating the HTML for parts products list       
     */
    public function render_parts_products_html($category_id, $finished_product_id = null) {
        $html = '';

        $parts_products = wc_get_products([
            'status' => 'publish',
            'limit' => -1,
            'category' => [get_term($category_id, 'product_cat')->slug]
        ]);        

        ob_start();

        if (!empty($parts_products)) {
            echo '<div class="parts-products">';
            foreach ($parts_products as $part) {
                $part_image = get_the_post_thumbnail_url($part->get_id());
                echo '<div class="part-item" data-productid="'.$part->get_id().'">';
                echo '<div class="part-box-left"><img src="'.$part_image.'" alt="" /></div>';
                echo '<div class="part-box-right"><h4>';
                echo esc_html($part->get_name());
                echo '</h4>';
                echo '<p>'.esc_html($part->get_description()).'</p>';

                //get attribute
                $custom_attributes = get_post_meta( $part->get_id(), '_cpa_custom_attributes', true );                
                if ( is_array( $custom_attributes ) || !empty( $custom_attributes ) ) :                    
                    $attribute_conditions = [];

                    foreach ( $custom_attributes as $attr ) {
                        $slug = sanitize_title( $attr['label'] );
                        $attribute_conditions[ $slug ] = [
                            'depends_on' => $attr['depends_on'] ?? '',
                            'show_when'  => !empty($attr['show_when']) ? array_map('trim', explode(',', $attr['show_when'])) : [],
                            'hide_when'  => !empty($attr['hide_when']) ? array_map('trim', explode(',', $attr['hide_when'])) : [],
                            'formula'    => $attr['formula'] ?? '',
                        ];
                    }        

                    echo '<div class="cpa-product-fields" data-productid="'.$part->get_id().'">';

                    foreach ( $custom_attributes as $attr ) {
                        $name = sanitize_title( $attr['label'] );

                        echo '<p class="form-row form-row-wide cpa-attribute" data-attribute="' .  strtolower(trim(preg_replace('/\s+/', '_', $attr['label'])))  . '">';

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

                        // Popup Label Button
                        if ( ! empty( $attr['popup_label'] ) && ! empty( $attr['popup_content'] ) ) {
                            echo '<button type="button" class="button cpa-popup-trigger" data-content="' . esc_attr( $attr['popup_content'] ) . '"><span class="dashicons dashicons-search"></span> ' . esc_html( $attr['popup_label'] ) . '</button>';
                        }
                        echo '</p>';
                    }
                    echo '</div>';

                    echo '<script class="cpa-attribute-conditions part_cpa_attribute_conditions_'.$part->get_id().'" type="application/json">';
                    echo wp_json_encode( $attribute_conditions );
                    echo '</script>';
                endif;    







                echo '<input type="checkbox" value="' . esc_attr($part->get_id()) . '"> '; 
                //echo '<input type="number" class="part-qty" value="1" min="1">';
                $min = 0; $max = 999999; $step = 1; $value = $min;?>
                <div class="b2bking_cream_input_group" data-product-id="<?php echo $part->get_id(); ?>">
                  <button type="button" class="b2bking_cream_input_minus_button b2bking_cream_input_button">-</button>
                  <input
                    type="number"
                    name="custom_qty_<?php echo $part->get_id(); ?>"                                  
                    class="b2bking_bulkorder_form_container_content_line_qty b2bking_bulkorder_form_container_content_line_qty_cream"
                    value="0"
                    min="0"
                    max="999999"
                    step="1"
                    data-product-id="<?php echo $part->get_id(); ?>"
                  />
                  <button type="button" class="b2bking_cream_input_plus_button b2bking_cream_input_button">+</button>
                </div>
                <?php
                echo '</div></div>';
            }
            echo '</div>';
        } else {
            echo '<p class="no-parts">No parts found in this category.</p>';
        }

        $html = ob_get_clean();

        return $html;
    }


    
    
    /*
    * Bulk order for Parts products
    */
    public function handle_b2bking_bulkorder_add_multiple() {
        // --- Security check ---
        if (empty($_POST['security'])) {
            wp_send_json_error(['message' => 'Missing security nonce']);
        }

        $additions = json_decode(stripslashes($_POST['additions']), true);

        if (empty($additions) || !is_array($additions)) {
            wp_send_json_error(['message' => 'Invalid additions data']);
        }

        if (!function_exists('b2bking_bulkorder_add_cart_item_function')) {
            wp_send_json_error(['message' => 'B2BKing core function not found']);
        }

        $results = [];
        $success_count = 0;

        foreach ($additions as $item) {
            $product_id = intval($item['productid']);
            $quantity   = intval($item['productqty'] ?? 1);
            $attributes = $item['attributes'] ?? [];
            $customdata = $item['customdata'] ?? [];

            // Call B2BKing’s own function directly
            $result = b2bking_bulkorder_add_cart_item_function($product_id, $quantity, $attributes, $customdata);

            if ($result === 'success') {
                $success_count++;
                $results[] = [
                    'product_id' => $product_id,
                    'status' => 'added',
                ];
            } else {
                $results[] = [
                    'product_id' => $product_id,
                    'status' => 'failed',
                    'error' => $result,
                ];
            }
        }

        if ($success_count > 0) {
            wp_send_json_success([
                'message' => "Successfully added {$success_count} product(s) to the cart.",
                'items' => $results,
            ]);
        } else {
            wp_send_json_error([
                'message' => 'Failed to add products to the cart.',
                'items' => $results,
            ]);
        }


    }

















}