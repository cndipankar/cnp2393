<?php if (! defined('ABSPATH')) {
    die;
} // Cannot access directly.

//
// Set a unique slug-like ID
//
$prefix = 'csf_demo_customizer';

//
// Create customize options
//
CSF::createCustomizeOptions($prefix);

//
// Create a section
//
CSF::createSection($prefix, [
    'title' => 'CSF - Overview',
    'priority' => 1,
    'fields' => [

        //
        // A text field
        //
        [
            'id' => 'opt-text',
            'type' => 'text',
            'title' => 'Text',
        ],

        [
            'id' => 'opt-textarea',
            'type' => 'textarea',
            'title' => 'Textarea',
            'help' => 'The help text of the field.',
        ],

        [
            'id' => 'opt-upload',
            'type' => 'upload',
            'title' => 'Upload',
        ],

        [
            'id' => 'opt-switcher',
            'type' => 'switcher',
            'title' => 'Switcher',
            'label' => 'The label text of the switcher.',
        ],

        [
            'id' => 'opt-color',
            'type' => 'color',
            'title' => 'Color',
            'default' => '#3498db',
        ],

        [
            'id' => 'opt-checkbox',
            'type' => 'checkbox',
            'title' => 'Checkbox',
            'label' => 'The label text of the checkbox.',
        ],

        [
            'id' => 'opt-radio',
            'type' => 'radio',
            'title' => 'Radio',
            'options' => [
                'yes' => 'Yes, Please.',
                'no' => 'No, Thank you.',
            ],
            'default' => 'yes',
        ],

        [
            'id' => 'opt-select',
            'type' => 'select',
            'title' => 'Select',
            'placeholder' => 'Select an option',
            'options' => [
                'opt-1' => 'Option 1',
                'opt-2' => 'Option 2',
                'opt-3' => 'Option 3',
            ],
        ],

    ],
]);

//
// Create a section
//
CSF::createSection($prefix, [
    'id' => 'nested_panel',
    'title' => 'CSF - Nested Panels',
    'priority' => 2,
]);

//
// Create a section
//
CSF::createSection($prefix, [
    'parent' => 'nested_panel',
    'title' => 'Nested Panel 1',
    'priority' => 3,
    'fields' => [

        [
            'id' => 'opt-text-1',
            'type' => 'text',
            'title' => 'Text',
        ],

        [
            'id' => 'opt-textarea-1',
            'type' => 'textarea',
            'title' => 'Textarea',
        ],

    ],
]);

//
// Create a section
//
CSF::createSection($prefix, [
    'parent' => 'nested_panel',
    'title' => 'Nested Panel 2',
    'priority' => 4,
    'fields' => [

        [
            'id' => 'opt-color-1',
            'type' => 'color',
            'title' => 'Color 1',
        ],

        [
            'id' => 'opt-color-2',
            'type' => 'color',
            'title' => 'Color 2',
        ],

        [
            'id' => 'opt-color-3',
            'type' => 'color',
            'title' => 'Color 3',
        ],

    ],
]);

//
// Create a section
//
CSF::createSection($prefix, [
    'title' => 'CSF - Reset & Backup',
    'priority' => 3,
    'fields' => [

        [
            'type' => 'backup',
        ],

    ],
]);
