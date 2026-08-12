<?php
function otm_block_categories($categories) {
    return array_merge(
        $categories,
        [
            [
                'slug' => 'otm-hero-section-blocks',
                'title' => __('OTM Hero Section Blocks', 'otm-hero-section-blocks'),
            ],
            [
                'slug' => 'otm-section-blocks',
                'title' => __('OTM Section Blocks', 'otm-section-blocks'),
            ],
            [
                'slug' => 'otm-content-blocks',
                'title' => __('OTM Content Blocks', 'otm-content-blocks'),
            ],
            [
                'slug' => 'otm-form-blocks',
                'title' => __('OTM Form Blocks', 'otm-form-blocks'),
            ],
            [
                'slug' => 'otm-interlinking-blocks',
                'title' => __('OTM Internal Linking Blocks', 'otm-interlinking-blocks'),
            ],
            [
                'slug' => 'otm-accordion-blocks',
                'title' => __('OTM Accordion Blocks', 'otm-accordion-blocks'),
            ],
            [
                'slug' => 'otm-cta-blocks',
                'title' => __('OTM CTA Blocks', 'otm-cta-blocks'),
            ],
            [
                'slug' => 'otm-slider-blocks',
                'title' => __('OTM Slider Blocks', 'otm-slider-blocks'),
            ],
        ]
    );
}
add_action('block_categories', 'otm_block_categories', 1, 2);