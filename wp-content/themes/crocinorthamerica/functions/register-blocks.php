<?php

function register_blocks() {
    if (function_exists('acf_register_block_type')) {
        $default = [
            'align' => false,
        ];

        acf_register_block_type([
            'name' => 'homepage-hero',
            'title' => __('Homepage Hero', 'crocinorthamerica'),
            'render_template' => 'blocks/hero-sections/homepage-hero.php',
            'category' => 'otm-hero-section-blocks',
            'icon' => 'cover-image',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'content-section',
            'title' => __('Content Section', 'crocinorthamerica'),
            'render_template' => 'blocks/content/content-section.php',
            'category' => 'otm-content-blocks',
            'icon' => 'welcome-add-page',
            'supports' => [
                'align' => true,
                'anchor' => true,
                'customClassName' => true,
                'jsx' => true,
            ],
        ]);

        acf_register_block_type([
            'name' => 'heading-blocks',
            'title' => __('Heading Block', 'crocinorthamerica'),
            'render_template' => 'blocks/content/heading-block.php',
            'category' => 'otm-content-blocks',
            'icon' => 'heading',
            'supports' => [
                'align' => true,
                'anchor' => true,
                'customClassName' => true,
                'jsx' => true,
            ],
        ]);

        acf_register_block_type([
            'name' => 'responsive-image',
            'title' => __('Responsive Image', 'crocinorthamerica'),
            'render_template' => 'blocks/content/responsive-image.php',
            'category' => 'otm-content-blocks',
            'icon' => 'images-alt',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'otm-button',
            'title' => __('OTM Button', 'crocinorthamerica'),
            'render_template' => 'blocks/content/otm-button.php',
            'category' => 'otm-content-blocks',
            'icon' => 'button',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'testimonial-slider',
            'title' => __('Testimonial Slider', 'crocinorthamerica'),
            'render_template' => 'blocks/sliders/testimonial-slider.php',
            'category' => 'otm-slider-blocks',
            'icon' => 'testimonial',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'contact-form',
            'title' => __('Contact Form', 'crocinorthamerica'),
            'render_template' => 'blocks/forms/contact-form.php',
            'category' => 'otm-form-blocks',
            'icon' => 'format-aside',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'image-cta',
            'title' => __('Image CTA', 'crocinorthamerica'),
            'render_template' => 'blocks/cta/image-cta.php',
            'category' => 'otm-cta-blocks',
            'icon' => 'megaphone',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'logo-box',
            'title' => __('Logo Box', 'crocinorthamerica'),
            'render_template' => 'blocks/content/logo-box.php',
            'category' => 'otm-content-blocks',
            'icon' => 'excerpt-view',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'text-cta',
            'title' => __('Text CTA', 'crocinorthamerica'),
            'render_template' => 'blocks/cta/text-cta.php',
            'category' => 'otm-cta-blocks',
            'icon' => 'megaphone',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'animated-cards-heading',
            'title' => __('Animated Cards with Heading', 'crocinorthamerica'),
            'render_template' => 'blocks/content/animated-cards-heading.php',
            'category' => 'otm-content-blocks',
            'icon' => 'slides',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'animated-cards',
            'title' => __('Animated Cards', 'crocinorthamerica'),
            'render_template' => 'blocks/content/animated-cards.php',
            'category' => 'otm-content-blocks',
            'icon' => 'slides',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'service-offerings',
            'title' => __('Service Offerings', 'crocinorthamerica'),
            'render_template' => 'blocks/content/service-offerings.php',
            'category' => 'otm-content-blocks',
            'icon' => 'list-view',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'recent-blog-posts',
            'title' => __('Recent Blog Posts', 'crocinorthamerica'),
            'render_template' => 'blocks/content/recent-blog-posts.php',
            'category' => 'otm-content-blocks',
            'icon' => 'admin-post',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'contact-section',
            'title' => __('Contact Section', 'crocinorthamerica'),
            'render_template' => 'blocks/forms/contact-section.php',
            'category' => 'otm-form-blocks',
            'icon' => 'format-aside',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'inner-page-hero',
            'title' => __('Inner Page Hero', 'crocinorthamerica'),
            'render_template' => 'blocks/hero-sections/inner-page-hero.php',
            'category' => 'otm-hero-section-blocks',
            'icon' => 'cover-image',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'two-column-content',
            'title' => __('Two-column Content', 'crocinorthamerica'),
            'render_template' => 'blocks/content/two-column-content.php',
            'category' => 'otm-content-blocks',
            'icon' => 'columns',
            'supports' => [
                'align' => true,
                'anchor' => true,
                'customClassName' => true,
                'jsx' => true,
            ],
        ]);

        acf_register_block_type([
            'name' => 'product-gallery-slider',
            'title' => __('Product Gallery Slider', 'crocinorthamerica'),
            'render_template' => 'blocks/sliders/product-gallery-slider.php',
            'category' => 'otm-slider-blocks',
            'icon' => 'products',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'two-column-list-content',
            'title' => __('Two-column List Content', 'crocinorthamerica'),
            'render_template' => 'blocks/content/two-column-list-content.php',
            'category' => 'otm-content-blocks',
            'icon' => 'columns',
            'supports' => [
                'align' => true,
                'anchor' => true,
                'customClassName' => true,
                'jsx' => true,
            ],
        ]);

        acf_register_block_type([
            'name' => 'contact-info',
            'title' => __('Contact Info', 'crocinorthamerica'),
            'render_template' => 'blocks/content/contact-info.php',
            'category' => 'otm-content-blocks',
            'icon' => 'info',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'categorized-gallery',
            'title' => __('Categorized Gallery', 'crocinorthamerica'),
            'render_template' => 'blocks/content/categorized-gallery.php',
            'category' => 'otm-content-blocks',
            'icon' => 'images-alt',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'product-cards',
            'title' => __('Product Cards', 'crocinorthamerica'),
            'render_template' => 'blocks/content/product-cards.php',
            'category' => 'otm-content-blocks',
            'icon' => 'index-card',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'product-page-content',
            'title' => __('Product Page Content', 'crocinorthamerica'),
            'render_template' => 'blocks/content/product-page-content.php',
            'category' => 'otm-content-blocks',
            'icon' => 'columns',
            'supports' => [
                'align' => true,
                'anchor' => true,
                'customClassName' => true,
                'jsx' => true,
            ],
        ]);

        acf_register_block_type([
            'name' => 'animated-product-cards',
            'title' => __('Animated Product Cards', 'crocinorthamerica'),
            'render_template' => 'blocks/content/animated-product-cards.php',
            'category' => 'otm-content-blocks',
            'icon' => 'slides',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'product-profiles',
            'title' => __('Product Profiles', 'crocinorthamerica'),
            'render_template' => 'blocks/content/product-profiles.php',
            'category' => 'otm-content-blocks',
            'icon' => 'text-page',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'download-card',
            'title' => __('Download Card', 'crocinorthamerica'),
            'render_template' => 'blocks/content/download-card.php',
            'category' => 'otm-content-blocks',
            'icon' => 'download',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'login-prompt',
            'title' => __('Login Prompt', 'crocinorthamerica'),
            'render_template' => 'blocks/content/login-prompt.php',
            'category' => 'otm-content-blocks',
            'icon' => 'lock',
            'supports' => $default,
        ]);

        acf_register_block_type([
            'name' => 'shutter-plan',
            'title' => __('Shutter Plan', 'crocinorthamerica'),
            'render_template' => 'blocks/content/shutter-plan.php',
            'category' => 'otm-content-blocks',
            'icon' => 'media-document',
            'supports' => $default,
        ]);
    }
}

add_action('acf/init', 'register_blocks');