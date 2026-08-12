<?php
/*
 * Setup
 */

if (! function_exists('otm_theme_setup')) {
    function otm_theme_setup()
    {

        // Gutenberg
        add_theme_support('wp-block-styles');

        // Theme
        add_theme_support('post-thumbnails');

        update_option('thumbnail_size_w', 255);
        update_option('thumbnail_size_h', 255); /* internal max-width of col-3 */
        update_option('medium_size_w', 350);
        update_option('medium_size_h', 350); /* internal max-width of col-4 */
        update_option('medium_large_size_w', 540);
        update_option('medium_large_size_h', 540); /* internal max-width of col-6 */
        update_option('large_size_w', 825);
        update_option('large_size_h', 825); /* internal max-width of col-9 */

        if (! isset($content_width)) {
            $content_width = 1100;
        }

        add_theme_support('post-formats', [
            'aside',
            'gallery',
            'link',
            'image',
            'quote',
            'status',
            'video',
            'audio',
            'chat',
        ]);

        add_theme_support('automatic-feed-links');
    }
}
add_action('init', 'otm_theme_setup');
