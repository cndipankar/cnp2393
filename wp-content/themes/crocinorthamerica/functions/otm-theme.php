<?php
/*
|--------------------------------------------------------------------------
| OTM Theme  Functions
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Configure Codestar Framework
|--------------------------------------------------------------------------
*/

// Require Codestar Framework Files
require get_template_directory() . '/functions/codestar-framework/codestar-framework.php';

// CS Framework Enqueue Fixes
function csf_add_my_custom_css()
{
    wp_dequeue_style('csf-fa');
    wp_enqueue_style('csf-fa5', get_template_directory_uri() . '/csf-override/assets/css/font-awesome-5.7.2.css', [], '5.7.2', 'all');
    wp_enqueue_style('otm-custom-icons-font', get_template_directory_uri() . '/csf-override/assets/css/custom-icons-font.css', [], null, 'all');
    wp_enqueue_style('otm-csf-override', get_template_directory_uri() . '/csf-override/assets/css/otm-csf-override.css', [], null, 'all');
}
add_action('csf_enqueue', 'csf_add_my_custom_css');

// Remove Codestar Framework Welcome Page
add_filter('csf_welcome_page', '__return_false');

// Multiple locations Schema variables
$location_schema = get_option('otm_theme_options')['multiple-location-schema'];

$innerpage_options = [];
if ($location_schema) {
    foreach ($location_schema as $options):
        $innerpage_options[$options['location-title']] = $options['location-title'];
    endforeach;
}

/*
|--------------------------------------------------------------------------
| Admin Options / Theme Options Panel
|--------------------------------------------------------------------------
*/

if (class_exists('CSF')) {
    $prefix = 'otm_theme_options';

    CSF::createOptions($prefix, [
        'sticky_header' => false,
        'framework_title' => otm_primary_logo_url() ? '<img class="frame-logo" src="'. otm_primary_logo_url() .'" alt="">' : get_bloginfo('title') . ' Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug' => 'otm-theme-options',
        'menu_position' => 80,
        'menu_icon' => 'dashicons-admin-generic',
        'show_reset_all' => false,
        'footer_text' => ' ',
        'footer_credit' => ' ',
        'theme' => 'light',
        'admin_bar_menu_icon' => 'dashicons-admin-generic',
        'show_in_customizer' => true,
    ]);
    CSF::createSection($prefix, [
        'title' => 'Overview',
        'icon' => 'fas fa-star',
        'fields' => [
            [
                'id' => 'logos',
                'type' => 'tabbed',
                'title' => 'Logos',
                'tabs' => [
                    [
                        'title' => 'Primary',
                        'fields' => [
                            [
                                'id' => 'primary-logo',
                                'type' => 'media',
                                'title' => 'Primary Logo',
                                'subtitle' => 'Preferably a logo with dark accent colors.',
                                'library' => 'image',
                                'button_title' => 'Select Logo',
                                'desc' => 'Allowed Image Formats: jpeg, png, svg.',
                                'placeholder' => 'No Logo Selected',
                            ] ,
                            [
                                'id' => 'primary-logo-webp',
                                'type' => 'media',
                                'title' => 'Primary Logo (WebP)',
                                'library' => 'image',
                                'button_title' => 'Select Logo',
                                'desc' => 'Allowed Image Formats: webp.',
                                'placeholder' => 'No Logo Selected',
                                'subtitle' => 'To learn more about WebP <a href="https://developers.google.com/speed/webp/" target="_blank">click here</a>.',
                            ] ,
                        ],
                    ] ,
                    [
                        'title' => 'Secondary',
                        'fields' => [
                            [
                                'id' => 'secondary-logo',
                                'type' => 'media',
                                'title' => 'Secondary Logo',
                                'subtitle' => 'Preferably a logo with light accent colors.',
                                'library' => 'image',
                                'button_title' => 'Select Logo',
                                'desc' => 'Allowed Image Formats: jpeg, png, svg.',
                                'placeholder' => 'No Logo Selected',
                            ] ,
                            [
                                'id' => 'secondary-logo-webp',
                                'type' => 'media',
                                'title' => 'Secondary Logo (WebP)',
                                'library' => 'image',
                                'button_title' => 'Select Logo',
                                'desc' => 'Allowed Image Formats: webp.',
                                'placeholder' => 'No Logo Selected',
                                'subtitle' => 'To learn more about WebP <a href="https://developers.google.com/speed/webp/" target="_blank">click here</a>.',
                            ] ,
                        ],
                    ] ,
                    [
                        'title' => 'Mobile',
                        'fields' => [
                            [
                                'id' => 'mobile-logo',
                                'type' => 'media',
                                'title' => 'Mobile Logo',
                                'library' => 'image',
                                'button_title' => 'Select Logo',
                                'desc' => 'Allowed Image Formats: jpeg, png, svg.',
                                'placeholder' => 'No Logo Selected',
                            ] ,
                            [
                                'id' => 'mobile-logo-webp',
                                'type' => 'media',
                                'title' => 'Mobile Logo (WebP)',
                                'library' => 'image',
                                'button_title' => 'Select Logo',
                                'desc' => 'Allowed Image Formats: webp.',
                                'placeholder' => 'No Logo Selected',
                                'subtitle' => 'To learn more about WebP <a href="https://developers.google.com/speed/webp/" target="_blank">click here</a>.',
                            ] ,
                        ],
                    ] ,
                ],
            ] ,
            [
                'id' => 'contact-info',
                'type' => 'tabbed',
                'title' => 'Contact Information',
                'tabs' => [
                    [
                        'title' => 'General',
                        'fields' => [
                            [
                                'id' => 'email-address',
                                'type' => 'text',
                                'title' => 'Email Address',
                                'validate' => 'csf_validate_email',
                                'after' => '<div class="shortcode">Shortcode: [otm-email-address]</div>',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                            [
                                'id' => 'phone-number',
                                'type' => 'text',
                                'title' => 'Phone Number',
                                'subtitle' => 'E.g. (123) 456-7890, 123-456-7890;',
                                'after' => '<div class="shortcode">Shortcode: [otm-phone-number]</div>',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                            [
                                'id' => 'phone-number-2',
                                'type' => 'text',
                                'title' => 'Additional Phone Number',
                                'subtitle' => 'E.g. (123) 456-7890, 123-456-7890;',
                                'after' => '<div class="shortcode">Shortcode: [otm-phone-number-2]</div>',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                            [
                                'id' => 'fax-number',
                                'type' => 'text',
                                'title' => 'Fax Number',
                                'subtitle' => 'E.g. (123) 456-7890, 123-456-7890;',
                                'after' => '<div class="shortcode">Shortcode: [otm-fax-number]</div>',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                        ],
                    ] ,
                    [
                        'title' => 'Address',
                        'fields' => [
                            [
                                'id' => 'full-address',
                                'type' => 'text',
                                'title' => 'Full Address',
                                'after' => '<div class="shortcode">Shortcode: [otm-full-address]</div>',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                            [
                                'id' => 'address-line-1',
                                'type' => 'text',
                                'title' => 'Address Line 1',
                                'after' => '<div class="shortcode">Shortcode: [otm-address-line-1]</div>',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                            [
                                'id' => 'address-line-2',
                                'type' => 'text',
                                'title' => 'Address Line 2',
                                'after' => '<div class="shortcode">Shortcode: [otm-address-line-2]</div>',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                            [
                                'id' => 'address-link',
                                'type' => 'text',
                                'title' => 'Address Link',
                                'validate' => 'csf_validate_url',
                                'after' => '<div class="shortcode">Shortcode: [otm-address-link]</div>',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                        ],
                    ] ,
                ],
            ] ,
        ],
    ]);
    CSF::createSection($prefix, [
        'title' => 'Social Profiles',
        'icon' => 'fas fa-share-alt',
        'fields' => [
            [
                'id' => 'social-profiles',
                'type' => 'group',
                'title' => 'Social Profiles',
                'button_title' => 'Add Social Profile',
                'remove_title' => 'Remove Social Profile',
                'fields' => [
                    [
                        'id' => 'social-profile',
                        'type' => 'text',
                        'title' => 'Social Profile Name',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'social-profile-icon',
                        'type' => 'icon',
                        'title' => 'Social Profile Icon',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'social-profile-url',
                        'type' => 'text',
                        'title' => 'Social Profile URL',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                ],
            ] ,
        ],
    ]);
    CSF::createSection($prefix, [
        'title' => 'Testimonials',
        'icon' => 'fas fa-edit',
        'fields' => [
            [
                'id' => 'testimonials',
                'type' => 'group',
                'title' => 'Testimonials',
                'button_title' => 'Add Testimonial',
                'remove_title' => 'Remove Testimonial',
                'fields' => [

                    [
                        'id' => 'testimonial-author',
                        'type' => 'text',
                        'title' => 'Testimonial Author',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ],
                    ],

                    [
                        'id' => 'testimonial-date',
                        'type' => 'date',
                        'title' => 'Testimonial Date',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ],
                    ],

                    [
                        'id' => 'testimonial',
                        'type' => 'wp_editor',
                        'media_buttons' => false,
                        'title' => 'Testimonial',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ],
                    ],

                    [
                        'id' => 'testimonial-rating',
                        'type' => 'select',
                        'title' => 'Testimonial Rating',
                        'options' => [
                            '5' => '★★★★★',
                            '4' => '★★★★',
                            '3' => '★★★',
                            '2' => '★★',
                            '1' => '★',
                        ],
                        'default' => '5',
                    ],

                ],
            ],
        ],
    ]);
    CSF::createSection($prefix, [
        'title' => 'FAQs',
        'icon' => 'fas fa-question-circle',
        'fields' => [
            [
                'id' => 'faq',
                'type' => 'group',
                'title' => 'FAQs',
                'button_title' => 'Add FAQ',
                'remove_title' => 'Remove FAQ',
                'fields' => [
                    [
                        'id' => 'question',
                        'type' => 'text',
                        'title' => 'Question',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'answer',
                        'type' => 'textarea',
                        'title' => 'Answer',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                ],
            ] ,
        ],
    ]);
    CSF::createSection($prefix, [
        'title' => 'Copyright Text',
        'icon' => 'fas fa-copyright',
        'fields' => [
            [
                'id' => 'copyright-text',
                'type' => 'textarea',
                'title' => 'Copyright Text',
                'after' => '<span class="box">Suggested shortcodes: [otm-current-year], [otm-site-title]</span>',
                'default' => 'Copyright © [otm-site-title] [otm-current-year] All Rights Reserved.',
                'attributes' => [
                    'autocomplete' => 'off',
                ],
            ],
        ],
    ]);
    CSF::createSection($prefix, [
        'title' => 'Custom CSS',
        'icon' => 'fas fa-paint-brush',
        'fields' => [
            [
                'id' => 'custom-css-code',
                'type' => 'code_editor',
                'title' => 'Custom CSS Code',
                'subtitle' => 'Adds custom CSS code in the document <code>&lt;head&gt;</code>.',
                'settings' => [
                    'mode' => 'css',
                ],
            ],
        ],
    ]);
    CSF::createSection($prefix, [
        'title' => 'Custom Scripts',
        'icon' => 'fas fa-code',
        'fields' => [
            [
                'type' => 'submessage',
                'style' => 'info',
                'content' => 'The custom Javascript code needs to be wrapped in-between <code>&lt;script&gt;&lt;/script&gt;</code> tags',
            ],
            [
                'id' => 'custom-js-code-head',
                'type' => 'code_editor',
                'title' => 'Custom Javascript Code - Head',
                'subtitle' => 'Adds custom Javascript code in the document <code>&lt;head&gt;</code> tag.',
                'settings' => [
                    'mode' => 'htmlmixed',
                ],
            ],
            [
                'id' => 'custom-js-code-body',
                'type' => 'code_editor',
                'title' => 'Custom Javascript Code - Body',
                'subtitle' => 'Adds custom Javascript code after opening <code>&lt;body&gt;</code> tag.',
                'settings' => [
                    'mode' => 'htmlmixed',
                ],
            ],
            [
                'id' => 'custom-js-code-footer',
                'type' => 'code_editor',
                'title' => 'Custom Javascript Code - Footer',
                'subtitle' => 'Adds custom Javascript code before closing <code>&lt;/body&gt;</code> tag.',
                'settings' => [
                    'mode' => 'htmlmixed',
                ],
            ],
        ],
    ]);
    CSF::createSection($prefix, [
        'title' => 'Schema Structured Data',
        'icon' => 'far fa-file-code',
        'fields' => [
            [
                'type' => 'content',
                'content' => 'To learn more about what is structured data click <a href="https://developers.google.com/search/docs/guides/intro-structured-data" target="_blank">here</a>.',
            ],
            [
                'id' => 'use-local-business-schema',
                'type' => 'switcher',
                'title' => 'Enable structured data throughout the website?',
                'text_on' => 'Yes',
                'text_off' => 'No',
                'default' => false,
            ],
            [
                'id' => 'local-business-tabs',
                'type' => 'tabbed',
                'title' => 'General',
                'dependency' => ['use-local-business-schema', '==', true],
                'tabs' => [
                    [
                        'title' => 'Information',
                        'fields' => [
                            [
                                'id' => 'local-business-name',
                                'type' => 'text',
                                'title' => 'Bussiness Name',
                            ],
                            [
                                'id' => 'local-business-type',
                                'type' => 'select',
                                'title' => 'Business Type',
                                'placeholder' => 'Select an option',
                                'chosen' => true,
                                'options' => [
                                    'ProfessionalService' => 'ProfessionalService',
                                    'LocalBusiness' => 'LocalBusiness',
                                    'AnimalShelter' => 'AnimalShelter',
                                    'AutomotiveBusiness' => 'AutomotiveBusiness',
                                    'AutoBodyShop' => 'AutoBodyShop',
                                    'AutoDealer' => '&nbsp;&nbsp;AutoDealer',
                                    'AutoPartsStore' => '&nbsp;&nbsp;AutoPartsStore',
                                    'AutoRental' => '&nbsp;&nbsp;AutoRental',
                                    'AutoRepair' => '&nbsp;&nbsp;AutoRepair',
                                    'AutoWash' => '&nbsp;&nbsp;AutoWash',
                                    'GasStation' => '&nbsp;&nbsp;GasStation',
                                    'MotorcycleDealer' => '&nbsp;&nbsp;MotorcycleDealer',
                                    'MotorcycleRepair' => '&nbsp;&nbsp;MotorcycleRepair',
                                    'ChildCare' => 'ChildCare',
                                    'Dentist' => 'Dentist',
                                    'DryCleaningOrLaundry' => 'DryCleaningOrLaundry',
                                    'EmergencyService' => '&nbsp;&nbsp;EmergencyService',
                                    'FireStation' => '&nbsp;&nbsp;FireStation',
                                    'Hospital' => '&nbsp;&nbsp;Hospital',
                                    'PoliceStation' => '&nbsp;&nbsp;PoliceStation',
                                    'EmploymentAgency' => 'EmploymentAgency',
                                    'EntertainmentBusiness' => 'EntertainmentBusiness',
                                    'AdultEntertainment' => '&nbsp;&nbsp;AdultEntertainment',
                                    'AmusementPark' => '&nbsp;&nbsp;AmusementPark',
                                    'ArtGallery' => '&nbsp;&nbsp;ArtGallery',
                                    'Casino' => '&nbsp;&nbsp;Casino',
                                    'ComedyClub' => '&nbsp;&nbsp;ComedyClub',
                                    'MovieTheater' => '&nbsp;&nbsp;MovieTheater',
                                    'NightClub' => '&nbsp;&nbsp;NightClub',
                                    'FinancialService' => 'FinancialService',
                                    'AccountingService' => '&nbsp;&nbsp;AccountingService',
                                    'AutomatedTeller' => '&nbsp;&nbsp;AutomatedTeller',
                                    'BankOrCreditUnion' => '&nbsp;&nbsp;BankOrCreditUnion',
                                    'InsuranceAgency' => '&nbsp;&nbsp;InsuranceAgency',
                                    'FoodEstablishment' => '&nbsp;&nbsp;FoodEstablishment',
                                    'Bakery' => '&nbsp;&nbsp;Bakery',
                                    'BarOrPub' => '&nbsp;&nbsp;BarOrPub',
                                    'Brewery' => '&nbsp;&nbsp;Brewery',
                                    'CafeOrCoffeeShop' => '&nbsp;&nbsp;CafeOrCoffeeShop',
                                    'FastFoodRestaurant' => '&nbsp;&nbsp;FastFoodRestaurant',
                                    'IceCreamShop' => '&nbsp;&nbsp;IceCreamShop',
                                    'Restaurant' => '&nbsp;&nbsp;Restaurant',
                                    'Winery' => '&nbsp;&nbsp;Winery',
                                    'GovernmentOffice' => 'GovernmentOffice',
                                    'PostOffice' => '&nbsp;&nbsp;PostOffice',
                                    'HealthAndBeautyBusiness' => 'HealthAndBeautyBusiness',
                                    'BeautySalon' => '&nbsp;&nbsp;BeautySalon',
                                    'DaySpa' => '&nbsp;&nbsp;DaySpa',
                                    'HairSalon' => '&nbsp;&nbsp;HairSalon',
                                    'HealthClub' => '&nbsp;&nbsp;HealthClub',
                                    'NailSalon' => '&nbsp;&nbsp;NailSalon',
                                    'TattooParlor' => '&nbsp;&nbsp;TattooParlor',
                                    'HomeAndConstructionBusiness' => 'HomeAndConstructionBusiness',
                                    'Electrician' => '&nbsp;&nbsp;Electrician',
                                    'GeneralContractor' => '&nbsp;&nbsp;GeneralContractor',
                                    'HVACBusiness' => '&nbsp;&nbsp;HVACBusiness',
                                    'HousePainter' => '&nbsp;&nbsp;HousePainter',
                                    'Locksmith' => '&nbsp;&nbsp;Locksmith',
                                    'MovingCompany' => '&nbsp;&nbsp;MovingCompany',
                                    'Plumber' => '&nbsp;&nbsp;Plumber',
                                    'RoofingContractor' => '&nbsp;&nbsp;RoofingContractor',
                                    'InternetCafe' => 'InternetCafe',
                                    'LegalService' => 'LegalService',
                                    'Notary' => '&nbsp;&nbsp;Notary',
                                    'Library' => 'Library',
                                    'LodgingBusiness' => 'LodgingBusiness',
                                    'BedAndBreakfast' => '&nbsp;&nbsp;BedAndBreakfast',
                                    'Campground' => '&nbsp;&nbsp;Campground',
                                    'Hostel' => '&nbsp;&nbsp;Hostel',
                                    'Hotel' => '&nbsp;&nbsp;Hotel',
                                    'Motel' => '&nbsp;&nbsp;Motel',
                                    'Resort' => '&nbsp;&nbsp;Resort',
                                    'RadioStation' => 'RadioStation',
                                    'RealEstateAgent' => 'RealEstateAgent',
                                    'RecyclingCenter' => 'RecyclingCenter',
                                    'SelfStorage' => 'SelfStorage',
                                    'ShoppingCenter' => 'ShoppingCenter',
                                    'SportsActivityLocation' => 'SportsActivityLocation',
                                    'BowlingAlley' => '&nbsp;&nbsp;BowlingAlley',
                                    'ExerciseGym' => '&nbsp;&nbsp;ExerciseGym',
                                    'GolfCourse' => '&nbsp;&nbsp;GolfCourse',
                                    'PublicSwimmingPool' => '&nbsp;&nbsp;PublicSwimmingPool',
                                    'SkiResort' => '&nbsp;&nbsp;SkiResort',
                                    'SportsClub' => '&nbsp;&nbsp;SportsClub',
                                    'StadiumOrArena' => '&nbsp;&nbsp;StadiumOrArena',
                                    'TennisComplex' => '&nbsp;&nbsp;TennisComplex',
                                    'Store' => 'Store',
                                    'BikeStore' => '&nbsp;&nbsp;BikeStore',
                                    'BookStore' => '&nbsp;&nbsp;BookStore',
                                    'ClothingStore' => '&nbsp;&nbsp;ClothingStore',
                                    'ComputerStore' => '&nbsp;&nbsp;ComputerStore',
                                    'ConvenienceStore' => '&nbsp;&nbsp;ConvenienceStore',
                                    'DepartmentStore' => '&nbsp;&nbsp;DepartmentStore',
                                    'ElectronicsStore' => '&nbsp;&nbsp;ElectronicsStore',
                                    'Florist' => '&nbsp;&nbsp;Florist',
                                    'FurnitureStore' => '&nbsp;&nbsp;FurnitureStore',
                                    'GardenStore' => '&nbsp;&nbsp;GardenStore',
                                    'GroceryStore' => '&nbsp;&nbsp;GroceryStore',
                                    'HardwareStore' => '&nbsp;&nbsp;HardwareStore',
                                    'HobbyShop' => '&nbsp;&nbsp;HobbyShop',
                                    'HomeGoodsStore' => '&nbsp;&nbsp;HomeGoodsStore',
                                    'JewelryStore' => '&nbsp;&nbsp;JewelryStore',
                                    'LiquorStore' => '&nbsp;&nbsp;LiquorStore',
                                    'MensClothingStore' => '&nbsp;&nbsp;MensClothingStore',
                                    'MobilePhoneStore' => '&nbsp;&nbsp;MobilePhoneStore',
                                    'MovieRentalStore' => '&nbsp;&nbsp;MovieRentalStore',
                                    'MusicStore' => '&nbsp;&nbsp;MusicStore',
                                    'OfficeEquipmentStore' => '&nbsp;&nbsp;OfficeEquipmentStore',
                                    'OutletStore' => '&nbsp;&nbsp;OutletStore',
                                    'PawnShop' => '&nbsp;&nbsp;PawnShop',
                                    'PetStore' => '&nbsp;&nbsp;PetStore',
                                    'ShoeStore' => '&nbsp;&nbsp;ShoeStore',
                                    'SportingGoodsStore' => '&nbsp;&nbsp;SportingGoodsStore',
                                    'TireShop' => '&nbsp;&nbsp;TireShop',
                                    'ToyStore' => '&nbsp;&nbsp;ToyStore',
                                    'WholesaleStore' => '&nbsp;&nbsp;WholesaleStore',
                                    'TelevisionStation' => 'TelevisionStation',
                                    'TouristInformationCenter' => 'TouristInformationCenter',
                                    'TravelAgency' => 'TravelAgency',
                                ],
                            ],
                            [
                                'id' => 'local-business-image',
                                'type' => 'media',
                                'title' => 'Image',
                                'library' => 'image',
                                'button_title' => 'Select Image',
                                'placeholder' => 'No Image Selected',
                            ],
                            [
                                'id' => 'local-business-phone',
                                'type' => 'text',
                                'title' => 'Phone',
                            ],
                            [
                                'id' => 'local-business-price-range',
                                'type' => 'text',
                                'title' => 'Price Range',
                            ],
                        ],
                    ],
                    [
                        'title' => 'Social Profiles',
                        'fields' => [
                            [
                                'id' => 'use-local-business-social-profiles',
                                'type' => 'switcher',
                                'title' => 'Display social profiles in structured data?',
                                'text_on' => 'Yes',
                                'text_off' => 'No',
                                'default' => false,
                            ],
                            [
                                'id' => 'local-business-use-social-profiles',
                                'type' => 'switcher',
                                'title' => 'Use social profiles defined in theme options?',
                                'text_on' => 'Yes',
                                'text_off' => 'No',
                                'default' => true,
                                'dependency' => ['use-local-business-social-profiles', '==', true],
                            ],
                            [
                                'id' => 'local-business-social-profiles',
                                'type' => 'group',
                                'button_title' => 'Add Social Profile',
                                'remove_title' => 'Remove Social Profile',
                                'dependency' => ['use-local-business-social-profiles|local-business-use-social-profiles', '==|==', true | false],
                                'fields' => [
                                    [
                                        'id' => 'social-profile',
                                        'type' => 'text',
                                        'title' => 'Social Profile',
                                        'attributes' => [
                                            'autocomplete' => 'off',
                                        ] ,
                                    ] ,
                                    [
                                        'id' => 'social-profile-url',
                                        'type' => 'text',
                                        'title' => 'Social Profile URL',
                                        'attributes' => [
                                            'autocomplete' => 'off',
                                        ] ,
                                    ] ,
                                ],
                            ] ,
                        ],
                    ],
                    [
                        'title' => 'Star Rating',
                        'fields' => [
                            [
                                'id' => 'use-local-business-rating',
                                'type' => 'switcher',
                                'title' => 'Display star rating in structured data?',
                                'text_on' => 'Yes',
                                'text_off' => 'No',
                                'default' => false,
                            ],
                            [
                                'id' => 'local-business-use-testimonial-rating',
                                'type' => 'switcher',
                                'title' => 'Calculate rating from testimonials that are defined in theme options?',
                                'text_on' => 'Yes',
                                'text_off' => 'No',
                                'default' => true,
                                'dependency' => ['use-local-business-rating', '==', true],
                            ],
                            [
                                'id' => 'local-business-rating',
                                'type' => 'select',
                                'title' => 'Rating',
                                'dependency' => ['use-local-business-rating|local-business-use-testimonial-rating', '==|==', true | false],
                                'options' => [
                                    '5' => '★★★★★',
                                    '4' => '★★★★',
                                    '3' => '★★★',
                                    '2' => '★★',
                                    '1' => '★',
                                ],
                                'default' => '5',
                            ],
                            [
                                'id' => 'local-business-rating-count',
                                'type' => 'text',
                                'title' => 'Rating Count',
                                'dependency' => ['use-local-business-rating|local-business-use-testimonial-rating', '==|==', true | false],
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                        ],
                    ],
                ],
            ],
            [
                'id' => 'local-business-address-fieldset',
                'type' => 'fieldset',
                'title' => 'Address',
                'dependency' => ['use-local-business-schema', '==', true],
                'fields' => [
                    [
                        'id' => 'local-business-street',
                        'type' => 'text',
                        'title' => 'Street',
                    ],
                    [
                        'id' => 'local-business-city',
                        'type' => 'text',
                        'title' => 'City',
                    ],
                    [
                        'id' => 'local-business-zip',
                        'type' => 'text',
                        'title' => 'ZIP Code',
                    ],
                    [
                        'id' => 'local-business-country',
                        'type' => 'select',
                        'title' => 'Country',
                        'placeholder' => 'Select a country',
                        'chosen' => true,
                        'default' => 'US',
                        'options' => [
                            'CA' => 'Canada',
                            'GB' => 'United Kingdom',
                            'US' => 'United States',
                            'AF' => 'Afghanistan',
                            'AX' => 'Åland Islands',
                            'AL' => 'Albania',
                            'DZ' => 'Algeria',
                            'AS' => 'American Samoa',
                            'AD' => 'Andorra',
                            'AO' => 'Angola',
                            'AI' => 'Anguilla',
                            'AQ' => 'Antarctica',
                            'AG' => 'Antigua and Barbuda',
                            'AR' => 'Argentina',
                            'AM' => 'Armenia',
                            'AW' => 'Aruba',
                            'AU' => 'Australia',
                            'AT' => 'Austria',
                            'AZ' => 'Azerbaijan',
                            'BS' => 'Bahamas',
                            'BH' => 'Bahrain',
                            'BD' => 'Bangladesh',
                            'BB' => 'Barbados',
                            'BY' => 'Belarus',
                            'BE' => 'Belgium',
                            'BZ' => 'Belize',
                            'BJ' => 'Benin',
                            'BM' => 'Bermuda',
                            'BT' => 'Bhutan',
                            'BO' => 'Bolivia, Plurinational State of',
                            'BQ' => 'Bonaire, Sint Eustatius and Saba',
                            'BA' => 'Bosnia and Herzegovina',
                            'BW' => 'Botswana',
                            'BV' => 'Bouvet Island',
                            'BR' => 'Brazil',
                            'IO' => 'British Indian Ocean Territory',
                            'BN' => 'Brunei Darussalam',
                            'BG' => 'Bulgaria',
                            'BF' => 'Burkina Faso',
                            'BI' => 'Burundi',
                            'KH' => 'Cambodia',
                            'CM' => 'Cameroon',
                            'CV' => 'Cape Verde',
                            'KY' => 'Cayman Islands',
                            'CF' => 'Central African Republic',
                            'TD' => 'Chad',
                            'CL' => 'Chile',
                            'CN' => 'China',
                            'CX' => 'Christmas Island',
                            'CC' => 'Cocos (Keeling) Islands',
                            'CO' => 'Colombia',
                            'KM' => 'Comoros',
                            'CG' => 'Congo',
                            'CD' => 'Congo, the Democratic Republic of the',
                            'CK' => 'Cook Islands',
                            'CR' => 'Costa Rica',
                            'CI' => 'Côte dIvoire',
                            'HR' => 'Croatia',
                            'CU' => 'Cuba',
                            'CW' => 'Curaçao',
                            'CY' => 'Cyprus',
                            'CZ' => 'Czech Republic',
                            'DK' => 'Denmark',
                            'DJ' => 'Djibouti',
                            'DM' => 'Dominica',
                            'DO' => 'Dominican Republic',
                            'EC' => 'Ecuador',
                            'EG' => 'Egypt',
                            'SV' => 'El Salvador',
                            'GQ' => 'Equatorial Guinea',
                            'ER' => 'Eritrea',
                            'EE' => 'Estonia',
                            'ET' => 'Ethiopia',
                            'FK' => 'Falkland Islands (Malvinas)',
                            'FO' => 'Faroe Islands',
                            'FJ' => 'Fiji',
                            'FI' => 'Finland',
                            'FR' => 'France',
                            'GF' => 'French Guiana',
                            'PF' => 'French Polynesia',
                            'TF' => 'French Southern Territories',
                            'GA' => 'Gabon',
                            'GM' => 'Gambia',
                            'GE' => 'Georgia',
                            'DE' => 'Germany',
                            'GH' => 'Ghana',
                            'GI' => 'Gibraltar',
                            'GR' => 'Greece',
                            'GL' => 'Greenland',
                            'GD' => 'Grenada',
                            'GP' => 'Guadeloupe',
                            'GU' => 'Guam',
                            'GT' => 'Guatemala',
                            'GG' => 'Guernsey',
                            'GN' => 'Guinea',
                            'GW' => 'Guinea-Bissau',
                            'GY' => 'Guyana',
                            'HT' => 'Haiti',
                            'HM' => 'Heard Island and McDonald Islands',
                            'VA' => 'Holy See (Vatican City State)',
                            'HN' => 'Honduras',
                            'HK' => 'Hong Kong',
                            'HU' => 'Hungary',
                            'IS' => 'Iceland',
                            'IN' => 'India',
                            'ID' => 'Indonesia',
                            'IR' => 'Iran, Islamic Republic of',
                            'IQ' => 'Iraq',
                            'IE' => 'Ireland',
                            'IM' => 'Isle of Man',
                            'IL' => 'Israel',
                            'IT' => 'Italy',
                            'JM' => 'Jamaica',
                            'JP' => 'Japan',
                            'JE' => 'Jersey',
                            'JO' => 'Jordan',
                            'KZ' => 'Kazakhstan',
                            'KE' => 'Kenya',
                            'KI' => 'Kiribati',
                            'KP' => 'Korea, Democratic Peoples Republic of',
                            'KR' => 'Korea, Republic of',
                            'KW' => 'Kuwait',
                            'KG' => 'Kyrgyzstan',
                            'LA' => 'Lao Peoples Democratic Republic',
                            'LV' => 'Latvia',
                            'LB' => 'Lebanon',
                            'LS' => 'Lesotho',
                            'LR' => 'Liberia',
                            'LY' => 'Libya',
                            'LI' => 'Liechtenstein',
                            'LT' => 'Lithuania',
                            'LU' => 'Luxembourg',
                            'MO' => 'Macao',
                            'MK' => 'Macedonia, the former Yugoslav Republic of',
                            'MG' => 'Madagascar',
                            'MW' => 'Malawi',
                            'MY' => 'Malaysia',
                            'MV' => 'Maldives',
                            'ML' => 'Mali',
                            'MT' => 'Malta',
                            'MH' => 'Marshall Islands',
                            'MQ' => 'Martinique',
                            'MR' => 'Mauritania',
                            'MU' => 'Mauritius',
                            'YT' => 'Mayotte',
                            'MX' => 'Mexico',
                            'FM' => 'Micronesia, Federated States of',
                            'MD' => 'Moldova, Republic of',
                            'MC' => 'Monaco',
                            'MN' => 'Mongolia',
                            'ME' => 'Montenegro',
                            'MS' => 'Montserrat',
                            'MA' => 'Morocco',
                            'MZ' => 'Mozambique',
                            'MM' => 'Myanmar',
                            'NA' => 'Namibia',
                            'NR' => 'Nauru',
                            'NP' => 'Nepal',
                            'NL' => 'Netherlands',
                            'NC' => 'New Caledonia',
                            'NZ' => 'New Zealand',
                            'NI' => 'Nicaragua',
                            'NE' => 'Niger',
                            'NG' => 'Nigeria',
                            'NU' => 'Niue',
                            'NF' => 'Norfolk Island',
                            'MP' => 'Northern Mariana Islands',
                            'NO' => 'Norway',
                            'OM' => 'Oman',
                            'PK' => 'Pakistan',
                            'PW' => 'Palau',
                            'PS' => 'Palestinian Territory, Occupied',
                            'PA' => 'Panama',
                            'PG' => 'Papua New Guinea',
                            'PY' => 'Paraguay',
                            'PE' => 'Peru',
                            'PH' => 'Philippines',
                            'PN' => 'Pitcairn',
                            'PL' => 'Poland',
                            'PT' => 'Portugal',
                            'PR' => 'Puerto Rico',
                            'QA' => 'Qatar',
                            'RE' => 'Réunion',
                            'RO' => 'Romania',
                            'RU' => 'Russian Federation',
                            'RW' => 'Rwanda',
                            'BL' => 'Saint Barthélemy',
                            'SH' => 'Saint Helena, Ascension and Tristan da Cunha',
                            'KN' => 'Saint Kitts and Nevis',
                            'LC' => 'Saint Lucia',
                            'MF' => 'Saint Martin (French part)',
                            'PM' => 'Saint Pierre and Miquelon',
                            'VC' => 'Saint Vincent and the Grenadines',
                            'WS' => 'Samoa',
                            'SM' => 'San Marino',
                            'ST' => 'Sao Tome and Principe',
                            'SA' => 'Saudi Arabia',
                            'SN' => 'Senegal',
                            'RS' => 'Serbia',
                            'SC' => 'Seychelles',
                            'SL' => 'Sierra Leone',
                            'SG' => 'Singapore',
                            'SX' => 'Sint Maarten (Dutch part)',
                            'SK' => 'Slovakia',
                            'SI' => 'Slovenia',
                            'SB' => 'Solomon Islands',
                            'SO' => 'Somalia',
                            'ZA' => 'South Africa',
                            'GS' => 'South Georgia and the South Sandwich Islands',
                            'SS' => 'South Sudan',
                            'ES' => 'Spain',
                            'LK' => 'Sri Lanka',
                            'SD' => 'Sudan',
                            'SR' => 'Suriname',
                            'SJ' => 'Svalbard and Jan Mayen',
                            'SZ' => 'Swaziland',
                            'SE' => 'Sweden',
                            'CH' => 'Switzerland',
                            'SY' => 'Syrian Arab Republic',
                            'TW' => 'Taiwan, Province of China',
                            'TJ' => 'Tajikistan',
                            'TZ' => 'Tanzania, United Republic of',
                            'TH' => 'Thailand',
                            'TL' => 'Timor-Leste',
                            'TG' => 'Togo',
                            'TK' => 'Tokelau',
                            'TO' => 'Tonga',
                            'TT' => 'Trinidad and Tobago',
                            'TN' => 'Tunisia',
                            'TR' => 'Turkey',
                            'TM' => 'Turkmenistan',
                            'TC' => 'Turks and Caicos Islands',
                            'TV' => 'Tuvalu',
                            'UG' => 'Uganda',
                            'UA' => 'Ukraine',
                            'AE' => 'United Arab Emirates',
                            'UM' => 'United States Minor Outlying Islands',
                            'UY' => 'Uruguay',
                            'UZ' => 'Uzbekistan',
                            'VU' => 'Vanuatu',
                            'VE' => 'Venezuela, Bolivarian Republic of',
                            'VN' => 'Viet Nam',
                            'VG' => 'Virgin Islands, British',
                            'VI' => 'Virgin Islands, U.S.',
                            'WF' => 'Wallis and Futuna',
                            'EH' => 'Western Sahara',
                            'YE' => 'Yemen',
                            'ZM' => 'Zambia',
                            'ZW' => 'Zimbabwe',
                        ],
                    ],
                    [
                        'id' => 'local-business-state',
                        'type' => 'select',
                        'title' => 'State / Province / Region',
                        'chosen' => true,
                        'dependency' => ['local-business-country', '==', 'US'],
                        'options' => [
                            'AL' => 'Alabama (AL)',
                            'AK' => 'Alaska (AK)',
                            'AZ' => 'Arizona (AZ)',
                            'AR' => 'Arkansas (AR)',
                            'CA' => 'California (CA)',
                            'CO' => 'Colorado (CO)',
                            'CT' => 'Connecticut (CT)',
                            'DE' => 'Delaware (DE)',
                            'DC' => 'District Of Columbia (DC)',
                            'FL' => 'Florida (FL)',
                            'GA' => 'Georgia (GA)',
                            'HI' => 'Hawaii (HI)',
                            'ID' => 'Idaho (ID)',
                            'IL' => 'Illinois (IL)',
                            'IN' => 'Indiana (IN)',
                            'IA' => 'Iowa (IA)',
                            'KS' => 'Kansas (KS)',
                            'KY' => 'Kentucky (KY)',
                            'LA' => 'Louisiana (LA)',
                            'ME' => 'Maine (ME)',
                            'MD' => 'Maryland (MD)',
                            'MA' => 'Massachusetts (MA)',
                            'MI' => 'Michigan (MI)',
                            'MN' => 'Minnesota (MN)',
                            'MS' => 'Mississippi (MS)',
                            'MO' => 'Missouri (MO)',
                            'MT' => 'Montana (MT)',
                            'NE' => 'Nebraska (NE)',
                            'NV' => 'Nevada (NV)',
                            'NH' => 'New Hampshire (NH)',
                            'NJ' => 'New Jersey (NJ)',
                            'NM' => 'New Mexico (NM)',
                            'NY' => 'New York (NY)',
                            'NC' => 'North Carolina (NC)',
                            'ND' => 'North Dakota (ND)',
                            'OH' => 'Ohio (OH)',
                            'OK' => 'Oklahoma (OK)',
                            'OR' => 'Oregon (OR)',
                            'PA' => 'Pennsylvania (PA)',
                            'RI' => 'Rhode Island (RI)',
                            'SC' => 'South Carolina (SC)',
                            'SD' => 'South Dakota (SD)',
                            'TN' => 'Tennessee (TN)',
                            'TX' => 'Texas (TX)',
                            'UT' => 'Utah (UT)',
                            'VT' => 'Vermont (VT)',
                            'VA' => 'Virginia (VA)',
                            'WA' => 'Washington (WA)',
                            'WV' => 'West Virginia (WV)',
                            'WI' => 'Wisconsin (WI)',
                            'WY' => 'Wyoming (WY)',
                        ],
                    ],
                    [
                        'id' => 'local-business-region',
                        'type' => 'select',
                        'chosen' => true,
                        'title' => 'State / Province / Region',
                        'dependency' => ['local-business-country', '==', 'CA'],
                        'options' => [
                            'AB' => 'Alberta',
                            'BC' => 'British Columbia',
                            'MB' => 'Manitoba',
                            'NB' => 'New Brunswick',
                            'NL' => 'Newfoundland and Labrador',
                            'NS' => 'Nova Scotia',
                            'ON' => 'Ontario',
                            'PE' => 'Prince Edward Island',
                            'QC' => 'Quebec',
                            'SK' => 'Saskatchewan',
                            'NT' => 'Northwest Territories',
                            'NU' => 'Nunavut',
                            'YT' => 'Yukon',
                        ],
                    ],
                    [
                        'id' => 'local-business-lat',
                        'type' => 'text',
                        'title' => 'Latitude',
                        'desc' => 'You can use <a href="https://www.latlong.net/" target="_blank">this tool</a> to find the proper coordinates.',
                    ],
                    [
                        'id' => 'local-business-long',
                        'type' => 'text',
                        'title' => 'Longitude',
                        'desc' => 'You can use <a href="https://www.latlong.net/" target="_blank">this tool</a> to find the proper coordinates.',
                    ],
                ],
            ],
            [
                'id' => 'local-business-opening-hours-fieldset',
                'type' => 'fieldset',
                'title' => 'Opening Hours',
                'dependency' => ['use-local-business-schema', '==', true],
                'fields' => [
                    [
                        'id' => 'local-business-open-247',
                        'type' => 'switcher',
                        'title' => 'Open 24/7?',
                        'text_on' => 'Yes',
                        'text_off' => 'No',
                    ],
                    [
                        'id' => 'local-business-opening-hours',
                        'type' => 'group',
                        'title' => '',
                        'button_title' => 'Add Opening Hours',
                        'remove_title' => 'Remove Opening Hours',
                        'dependency' => ['local-business-open-247', '==', 'false'],
                        'fields' => [
                            [
                                'id' => 'local-business-opening-hours-id',
                                'type' => 'text',
                                'title' => 'Identifier',
                                'desc' => 'e.g. Monday-Friday 8:00-21:00',
                            ],
                            [
                                'id' => 'local-business-opening-hours-days',
                                'type' => 'button_set',
                                'title' => 'Day(s) of the week',
                                'multiple' => true,
                                'placeholder' => 'Select an option',
                                'options' => [
                                    'Monday' => 'Monday',
                                    'Tuesday' => 'Tuesday',
                                    'Wednesday' => 'Wednesday',
                                    'Thursday' => 'Thursday',
                                    'Friday' => 'Friday',
                                    'Saturtday' => 'Saturtday',
                                    'Sunday' => 'Sunday',
                                ],
                            ],
                            [
                                'id' => 'local-business-opening-hours-opens',
                                'type' => 'text',
                                'title' => 'Opens at',
                                'desc' => 'e.g. 08:00',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                            [
                                'id' => 'local-business-opening-hours-closes',
                                'type' => 'text',
                                'title' => 'Closes at',
                                'desc' => 'e.g. 21:00',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                        ],
                    ] ,
                ],
            ],
        ],
    ]);

    CSF::createSection($prefix, [
        'title' => 'Multiple location Schema Structured Data',
        'icon' => 'far fa-file-code',
        'fields' => [
            [
                'id' => 'multiple-location-schema',
                'type' => 'group',
                'title' => 'Locations',
                'button_title' => 'Add new location',
                'remove_title' => 'Remove location',
                'fields' => [
                    [
                        'id' => 'location-title',
                        'type' => 'text',
                        'title' => 'Location',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'location-url',
                        'type' => 'text',
                        'title' => 'Location Page URL',
                        'desc' => 'Optional',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'phone',
                        'type' => 'text',
                        'title' => 'Phone',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'street',
                        'type' => 'text',
                        'title' => 'Street',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'city',
                        'type' => 'text',
                        'title' => 'City',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'zip-code',
                        'type' => 'text',
                        'title' => 'ZIP Code',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'state',
                        'type' => 'select',
                        'title' => 'State',
                        'chosen' => true,
                        'options' => [
                            'AL' => 'Alabama (AL)',
                            'AK' => 'Alaska (AK)',
                            'AZ' => 'Arizona (AZ)',
                            'AR' => 'Arkansas (AR)',
                            'CA' => 'California (CA)',
                            'CO' => 'Colorado (CO)',
                            'CT' => 'Connecticut (CT)',
                            'DE' => 'Delaware (DE)',
                            'DC' => 'District Of Columbia (DC)',
                            'FL' => 'Florida (FL)',
                            'GA' => 'Georgia (GA)',
                            'HI' => 'Hawaii (HI)',
                            'ID' => 'Idaho (ID)',
                            'IL' => 'Illinois (IL)',
                            'IN' => 'Indiana (IN)',
                            'IA' => 'Iowa (IA)',
                            'KS' => 'Kansas (KS)',
                            'KY' => 'Kentucky (KY)',
                            'LA' => 'Louisiana (LA)',
                            'ME' => 'Maine (ME)',
                            'MD' => 'Maryland (MD)',
                            'MA' => 'Massachusetts (MA)',
                            'MI' => 'Michigan (MI)',
                            'MN' => 'Minnesota (MN)',
                            'MS' => 'Mississippi (MS)',
                            'MO' => 'Missouri (MO)',
                            'MT' => 'Montana (MT)',
                            'NE' => 'Nebraska (NE)',
                            'NV' => 'Nevada (NV)',
                            'NH' => 'New Hampshire (NH)',
                            'NJ' => 'New Jersey (NJ)',
                            'NM' => 'New Mexico (NM)',
                            'NY' => 'New York (NY)',
                            'NC' => 'North Carolina (NC)',
                            'ND' => 'North Dakota (ND)',
                            'OH' => 'Ohio (OH)',
                            'OK' => 'Oklahoma (OK)',
                            'OR' => 'Oregon (OR)',
                            'PA' => 'Pennsylvania (PA)',
                            'RI' => 'Rhode Island (RI)',
                            'SC' => 'South Carolina (SC)',
                            'SD' => 'South Dakota (SD)',
                            'TN' => 'Tennessee (TN)',
                            'TX' => 'Texas (TX)',
                            'UT' => 'Utah (UT)',
                            'VT' => 'Vermont (VT)',
                            'VA' => 'Virginia (VA)',
                            'WA' => 'Washington (WA)',
                            'WV' => 'West Virginia (WV)',
                            'WI' => 'Wisconsin (WI)',
                            'WY' => 'Wyoming (WY)',
                        ],
                    ],
                    [
                        'id' => 'latitude',
                        'type' => 'text',
                        'title' => 'Latitude',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'longitude',
                        'type' => 'text',
                        'title' => 'Longitude',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                ],
            ] ,
        ],
    ]);

    CSF::createSection($prefix, [
        'title' => 'Backup',
        'icon' => 'fas fa-hdd',
        'fields' => [
            [
                'type' => 'backup',
            ],
        ],
    ]);
}

/*
|--------------------------------------------------------------------------
| Page Options
|--------------------------------------------------------------------------
*/
if (class_exists('CSF')) {
    $prefix = 'otm_page_options';

    CSF::createMetabox($prefix, [
        'title' => 'Page Options',
        'post_type' => 'page',
        'priority' => 'high',
        'data_type' => 'unserialize',
    ]);

    CSF::createSection($prefix, [
        'title' => 'Headings',
        'fields' => [
            [
                'id' => 'heading-1',
                'type' => 'text',
                'title' => 'Heading 1',
                'subtitle' => 'Defines the most important heading. This field overrides the main page title on the front-end',
            ],
        ],
    ]);

    CSF::createSection($prefix, [
        'title' => 'Custom scripts',
        'fields' => [
            [
                'id' => 'header-scripts',
                'type' => 'code_editor',
                'title' => 'Header Scripts',
            ],
            [
                'id' => 'footer-scripts',
                'type' => 'code_editor',
                'title' => 'Footer Scripts',
            ],
        ],
    ]);

    CSF::createSection($prefix, [
        'title' => 'FAQ Schema',
        'fields' => [
            [
                'id' => 'faq-schema',
                'type' => 'group',
                'title' => 'Questions',
                'button_title' => 'Add Question',
                'remove_title' => 'Remove Question',
                'fields' => [
                    [
                        'id' => 'question',
                        'type' => 'text',
                        'title' => 'Question',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                    [
                        'id' => 'answer',
                        'type' => 'textarea',
                        'title' => 'Answer',
                        'attributes' => [
                            'autocomplete' => 'off',
                        ] ,
                    ] ,
                ],
            ] ,
        ],
    ]);

    CSF::createSection($prefix, [
        'title' => 'Schema Markup',
        'fields' => [
            [
                'id' => 'enable-location-schema',
                'type' => 'switcher',
                'title' => 'Enable Custom Location Schema On This Page?',
                'text_on' => 'Yes',
                'text_off' => 'No',
            ],
            [
                'id' => 'location-schema',
                'type' => 'select',
                'title' => 'Select Location',
                'dependency' => [ 'enable-location-schema', '==', 'true' ],
                'options' => $innerpage_options,
            ],
            [
                'id' => 'enable-structured-data',
                'type' => 'switcher',
                'title' => 'Enable structured data on this page?',
                'text_on' => 'Yes',
                'text_off' => 'No',
            ],
            [
                'id' => 'enable-schema-markup',
                'type' => 'switcher',
                'title' => 'Enable Other Schema Markup On This Page?',
                'text_on' => 'Yes',
                'text_off' => 'No',
            ],
            [
                'id' => 'schema-markup',
                'type' => 'fieldset',
                'title' => 'Schema Markup',
                'dependency' => [ 'enable-schema-markup', '==', 'true' ],
                'fields' => [
                    [
                        'id' => 'schema-markup',
                        'type' => 'select',
                        'title' => 'Schema Markup',
                        'placeholder' => 'Select A Markup',
                        'options' => [
                            'person' => 'Person',
                        ],
                    ],
                    // Person
                    [
                        'id' => 'person-name',
                        'type' => 'text',
                        'title' => 'Person Name',
                        'dependency' => [ 'schema-markup', '==', 'person' ],
                    ],
                    [
                        'id' => 'person-job-title',
                        'type' => 'text',
                        'title' => 'Job Title',
                        'dependency' => [ 'schema-markup', '==', 'person' ],
                    ],
                    [
                        'id' => 'person-company',
                        'type' => 'text',
                        'title' => 'Company',
                        'dependency' => [ 'schema-markup', '==', 'person' ],
                    ],
                    [
                        'id' => 'person-image',
                        'type' => 'media',
                        'title' => 'Image',
                        'library' => 'image',
                        'button_title' => 'Select Image',
                        'placeholder' => 'No Image Selected',
                        'dependency' => [ 'schema-markup', '==', 'person' ],
                    ] ,
                    [
                        'id' => 'person-social-profiles',
                        'type' => 'group',
                        'title' => 'Social Profiles',
                        'button_title' => 'Add Social Profile',
                        'remove_title' => 'Remove Social Profile',
                        'dependency' => [ 'schema-markup', '==', 'person' ],
                        'fields' => [
                            [
                                'id' => 'social-profile',
                                'type' => 'text',
                                'title' => 'Social Profile',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                            [
                                'id' => 'social-profile-url',
                                'type' => 'text',
                                'title' => 'Social Profile URL',
                                'attributes' => [
                                    'autocomplete' => 'off',
                                ] ,
                            ] ,
                        ],
                    ] ,

                ],
            ],
        ],
    ]);
}

/*
|--------------------------------------------------------------------------
| Page Option Functions / Shortcodes
|--------------------------------------------------------------------------
*/

/*--- Functions ---*/

// Page Heading Function
function otm_page_heading()
{
    $heading = get_post_meta(get_the_ID(), 'heading-1', true);
    if ($heading) {
        echo $heading;
    } else {
        the_title();
    }
}

// Page Header Scripts Function
function otm_page_header_scripts()
{
    $header_scripts = get_post_meta(get_the_ID(), 'header-scripts', true);
    if ($header_scripts) {
        echo $header_scripts;
    };
}

// Page Footer Scripts Function
function otm_page_footer_scripts()
{
    $footer_scripts = get_post_meta(get_the_ID(), 'footer-scripts', true);
    if ($footer_scripts) {
        echo $footer_scripts;
    };
}

// FAQ Schema
function otm_page_faq_schema()
{
    $faq_schema = get_post_meta(get_the_ID(), 'faq-schema', true);
    if ($faq_schema) {
        get_template_part('functions/faq-schema-generator');
    };
}

/*
|--------------------------------------------------------------------------
| Theme Option Functions / Shortcodes
|--------------------------------------------------------------------------
*/

/*--- Overview - Logos ---*/

// Primary Logo URL Function
function otm_primary_logo_url()
{
    if (isset(get_option('otm_theme_options')['logos']['primary-logo']['url'])) {
        return get_option('otm_theme_options')['logos']['primary-logo']['url'];
    }
}

function otm_primary_webp_logo_url()
{
    if (isset(get_option('otm_theme_options')['logos']['primary-logo-webp']['url'])) {
        return get_option('otm_theme_options')['logos']['primary-logo-webp']['url'];
    }
}

// Secondary Logo URL Function
function otm_secondary_logo_url()
{
    if (isset(get_option('otm_theme_options')['logos']['secondary-logo']['url'])) {
        return get_option('otm_theme_options')['logos']['secondary-logo']['url'];
    }
}

function otm_secondary_webp_logo_url()
{
    if (isset(get_option('otm_theme_options')['logos']['secondary-logo-webp']['url'])) {
        return get_option('otm_theme_options')['logos']['secondary-logo-webp']['url'];
    }
}

// Mobile Logo URL Function
function otm_mobile_logo_url()
{
    if (isset(get_option('otm_theme_options')['logos']['mobile-logo']['url'])) {
        return get_option('otm_theme_options')['logos']['mobile-logo']['url'];
    }
}

function otm_mobile_webp_logo_url()
{
    if (isset(get_option('otm_theme_options')['logos']['mobile-logo-webp']['url'])) {
        return get_option('otm_theme_options')['logos']['mobile-logo-webp']['url'];
    }
}

/*--- Overview - Contact Information - General ---*/

// Email Address Function
function otm_email_address()
{
    return get_option('otm_theme_options')['contact-info']['email-address'];
}
// Email Address Shortcode
add_shortcode('otm-email-address', 'otm_email_address');

function otm_phone_number()
{
    return get_option('otm_theme_options')['contact-info']['phone-number'];
}
add_shortcode('otm-phone-number', 'otm_phone_number');

function otm_phone_number_2()
{
    return get_option('otm_theme_options')['contact-info']['phone-number-2'];
}
add_shortcode('otm-phone-number-2', 'otm_phone_number_2');

function otm_fax_number()
{
    return get_option('otm_theme_options')['contact-info']['fax-number'];
}
add_shortcode('otm-fax-number', 'otm_fax_number');

/*--- Overview - Contact Information - Address ---*/

// Full Address Function
function otm_full_address()
{
    return get_option('otm_theme_options')['contact-info']['full-address'];
}
// Full Address Shortcode
add_shortcode('otm-full-address', 'otm_full_address');

// Address Line 1 Function
function otm_address_line1()
{
    return get_option('otm_theme_options')['contact-info']['address-line-1'];
}
// Address Line 1 Shortcode
add_shortcode('otm-address-line-1', 'otm_address_line1');

// Address Line 2 Function
function otm_address_line2()
{
    return get_option('otm_theme_options')['contact-info']['address-line-2'];
}
// Address Line 2 Shortcode
add_shortcode('otm-address-line-2', 'otm_address_line2');

// Address Link Function
function otm_address_link()
{
    return get_option('otm_theme_options')['contact-info']['address-link'];
}
// Address Link Shortcode
add_shortcode('otm-address-link', 'otm_address_link');

/*--- Copyright Text ---*/

// Copyright Text Function
function otm_copyright_text()
{
    echo do_shortcode(get_option('otm_theme_options')['copyright-text']);
}

/*--- Custom CSS Code ---*/

// Custom CSS Code Function
function otm_custom_css_code()
{
    if (get_option('otm_theme_options')['custom-css-code'] !== '') {
        echo '<!-- BEGIN Theme Custom CSS -->' . PHP_EOL;
        echo '<style>' . PHP_EOL;
        echo get_option('otm_theme_options')['custom-css-code'] . PHP_EOL;
        echo '</style>' . PHP_EOL;
        echo '<!-- END Theme Custom CSS -->' . PHP_EOL;
    }
}
add_action('wp_head', 'otm_custom_css_code', 100);

/*--- Custom Javascript Code ---*/

// Custom Javascript Code in Head Function
function otm_custom_js_code_head()
{
    if (get_option('otm_theme_options')['custom-js-code-head'] !== '') {
        echo '<!-- BEGIN Theme Custom Head JS -->' . PHP_EOL;
        echo get_option('otm_theme_options')['custom-js-code-head'] . PHP_EOL;
        echo '<!-- END Theme Custom Head JS -->' . PHP_EOL;
    }
}
add_action('wp_head', 'otm_custom_js_code_head', 100);

// Custom Javascript Code after <body> Function
function otm_custom_js_code_body()
{
    if (get_option('otm_theme_options')['custom-js-code-body'] !== '') {
        echo '<!-- BEGIN Theme Custom Body JS -->' . PHP_EOL;
        echo get_option('otm_theme_options')['custom-js-code-body'] . PHP_EOL;
        echo '<!-- END Theme Custom Body JS -->' . PHP_EOL;
    }
}

// Custom Javascript Code before </body> Function
function otm_custom_js_code_footer()
{
    if (get_option('otm_theme_options')['custom-js-code-footer'] !== '') {
        echo '<!-- BEGIN Theme Custom Footer JS -->' . PHP_EOL;
        echo get_option('otm_theme_options')['custom-js-code-footer'] . PHP_EOL;
        echo '<!-- END Theme Custom Footer JS -->' . PHP_EOL;
    }
}
add_action('wp_footer', 'otm_custom_js_code_footer', 200);

/*
|--------------------------------------------------------------------------
| Theme Shortcodes
|--------------------------------------------------------------------------
*/

/*--- Get Current Year ---*/
function otm_shortcode_current_year()
{
    return date('Y');
}
add_shortcode('otm-current-year', 'otm_shortcode_current_year');

/*--- Get Site Name ---*/
function otm_shortcode_site_name()
{
    return get_bloginfo('name');
}
add_shortcode('otm-site-title', 'otm_shortcode_site_name');

/*--- Nav Map Link ---*/
function otm_shortcode_nav_map_link()
{
    return '<a href="' . get_site_url() . '/site-map.htm" title="Nav Map">Nav Map</a>';
}
add_shortcode('otm-nav-map-link', 'otm_shortcode_nav_map_link');

/*--- Testimonial Blockquotes ---*/
function otm_shortcode_testimonial_blockquotes()
{
    ob_start();
    get_template_part('includes/testimonial-blockquotes');

    return ob_get_clean();
}
add_shortcode('otm-testimonial-blockquotes', 'otm_shortcode_testimonial_blockquotes');

/*--- Testimonial Slider ---*/
function otm_shortcode_testimonial_slider()
{
    ob_start();
    get_template_part('includes/testimonials-slider');

    return ob_get_clean();
}
add_shortcode('otm-testimonial-slider', 'otm_shortcode_testimonial_slider');

/*
|--------------------------------------------------------------------------
| Miscellaneous Functions
|--------------------------------------------------------------------------
*/

// Remove WordPress, comments, and customize menu from admin bar
function otm_admin_bar_customizations($wp_admin_bar)
{
    $wp_admin_bar->remove_node('wp-logo');
    $wp_admin_bar->remove_menu('comments');
    $wp_admin_bar->remove_menu('customize');
    if (! is_admin()):
  $args = [
      'id' => 'current-post-id',
      'title' => 'Current Post ID: ' . get_the_ID(),
      'href' => get_edit_post_link(),
      'meta' => false,
  ];
    $wp_admin_bar->add_node($args);
    endif;
}
add_action('admin_bar_menu', 'otm_admin_bar_customizations', 999);

// WordPress admin panel footer text modification
function otm_admin_footer_text()
{
    echo '';
}
add_filter('admin_footer_text', 'otm_admin_footer_text');

// WordPress login logo link title modification
function otm_loginlogo_title()
{
    return get_bloginfo('name');
}

// WordPress login logo link url modification
function otm_loginlogo_url($url)
{
    return home_url();
}

// If the primary logo is added in the theme options panel
if (isset(get_option('otm_theme_options')['logos']['primary-logo']['url'])) {
    add_filter('login_headertext', 'otm_loginlogo_title');
    add_filter('login_headerurl', 'otm_loginlogo_url');
}

// Customize the login page
function otm_customize_login() { ?>
    <style type="text/css">
			#login h1 a, .login h1 a {
				<?php //if (get_option('otm_theme_options')['logos']['primary-logo']['url']): ?>
					background-image: url(<?php //echo get_option('otm_theme_options')['logos']['primary-logo']['url']; ?>);
				<?php //endif; ?>
				height:65px;
				width:320px;
				background-size: contain;
				background-repeat: no-repeat;
				padding-bottom: 0px;
			}
			#login h1 a:focus, .login h1 a:focus {
				box-shadow: none;
			}
			.login form {
				border-radius: 10px;
				padding: 25px!important;
			}
			#nav,
			#backtoblog {
				text-align: center;
			}
			.forgetmenot {
				margin-top: 5px !important;
			}
			.login form .input, .login input[type=text],
			#rememberme {
				border-radius: 5px;
			}
			input[type=text]:focus, input[type=password]:focus, input[type=checkbox]:focus, input[type=tel]:focus, input[type=email]:focus {
			  border-color: #d6d6d6!important;
			  box-shadow: 0 0 2px rgba(114, 119, 124,.8)!important;
			}
    </style>
<?php }
add_action('login_enqueue_scripts', 'otm_customize_login');

// Enable WebP uploading through the media library
function webp_upload_mimes($existing_mimes)
{
    // add webp to the list of mime types
    $existing_mimes['webp'] = 'image/webp';

    // return the array back to the function with our added mime type
    return $existing_mimes;
}
add_filter('mime_types', 'webp_upload_mimes');


// Wordpress Editor Styles
function otm_editor_style()
{
    // Add support for editor styles.
    add_theme_support('editor-styles');

    // Enqueue editor styles.
    add_editor_style('assets/css/editor-style.min.css');
}
add_action('after_setup_theme', 'otm_editor_style');


// Site Logo Functionality
function otm_site_logo($logo_type = 'primary')
{
    $site_logo_url = '';
    $site_webp_logo_url = '';

    if ($logo_type == 'primary' && otm_primary_logo_url()):
    $site_logo_url = otm_primary_logo_url();
    $site_webp_logo_url = otm_primary_webp_logo_url(); elseif ($logo_type == 'secondary' && otm_secondary_logo_url()):
    $site_logo_url = otm_secondary_logo_url();
    $site_webp_logo_url = otm_secondary_webp_logo_url(); elseif ($logo_type == 'mobile' && otm_mobile_logo_url()):
    $site_logo_url = otm_mobile_logo_url();
    $site_webp_logo_url = otm_mobile_webp_logo_url();
    endif;

    if ($site_logo_url):
    $site_logo_ext = pathinfo($site_logo_url, PATHINFO_EXTENSION);
    endif;

    if (! has_action('navbar_brand')) { ?>
    <a class="logo" href="<?php echo esc_url(home_url('/')); ?>">
    <?php if ($site_logo_url): ?>
      <picture>
        <?php if ($site_webp_logo_url): ?>
        <source srcset="<?php echo $site_webp_logo_url; ?>" type="image/webp">
        <?php endif ?>
        <source srcset="<?php echo $site_logo_url ?>" type="image/<?php echo $site_logo_ext; ?>">
        <img src="<?php echo $site_logo_url ?>" alt="<?php bloginfo('name'); ?> Logo" title="<?php bloginfo('name'); ?>">
      </picture>
    <?php else: ?>
      <?php bloginfo('name'); ?>
    <?php endif ?>
    </a>
    <?php
    }
}

/*
|--------------------------------------------------------------------------
| Required/Recommended Plugins Functionality
|--------------------------------------------------------------------------
*/

require get_template_directory() . '/functions/class-tgm-plugin-activation.php';

function otm_theme_register_required_plugins()
{
    $plugins = [

        [
            'name' => 'Yoast SEO',
            'slug' => 'wordpress-seo',
            'is_callable' => 'wpseo_init',
        ],

        [
            'name' => 'Max Mega Menu',
            'slug' => 'megamenu',
            'required' => true,
        ],

    ];

    $config = [
        'id' => 'otm-theme-plugins',     // Unique ID for hashing notices for multiple instances of TGMPA.
        'default_path' => '',                      // Default absolute path to bundled plugins.
        'menu' => 'otm-install-plugins',   // Menu slug.
        'parent_slug' => 'themes.php',            // Parent menu slug.
        'capability' => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
        'has_notices' => true,                    // Show admin notices or not.
        'dismissable' => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg' => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => false,                   // Automatically activate plugins after installation or not.
        'message' => '',                      // Message to output right before the plugins table.
    ];

    tgmpa($plugins, $config);
}

add_action('tgmpa_register', 'otm_theme_register_required_plugins');

/*
|--------------------------------------------------------------------------
| Add Mega Menu Starter Theme
|--------------------------------------------------------------------------
*/

function megamenu_add_theme_starter_theme($themes)
{
    $themes["starter_theme_1560249534"] = [
        'title' => 'Starter Theme',
        'container_background_from' => 'rgba(255, 255, 255, 0)',
        'container_background_to' => 'rgba(255, 255, 255, 0)',
        'menu_item_background_hover_from' => 'rgba(255, 255, 255, 0)',
        'menu_item_background_hover_to' => 'rgba(255, 255, 255, 0)',
        'menu_item_link_font_size' => '15px',
        'menu_item_link_color' => 'rgb(85, 85, 85)',
        'menu_item_link_color_hover' => 'rgb(50, 122, 231)',
        'panel_header_border_color' => '#555',
        'panel_font_size' => '14px',
        'panel_font_color' => '#666',
        'panel_font_family' => 'inherit',
        'panel_second_level_font_color' => '#555',
        'panel_second_level_font_color_hover' => '#555',
        'panel_second_level_text_transform' => 'uppercase',
        'panel_second_level_font' => 'inherit',
        'panel_second_level_font_size' => '16px',
        'panel_second_level_font_weight' => 'bold',
        'panel_second_level_font_weight_hover' => 'bold',
        'panel_second_level_text_decoration' => 'none',
        'panel_second_level_text_decoration_hover' => 'none',
        'panel_second_level_border_color' => '#555',
        'panel_third_level_font_color' => '#666',
        'panel_third_level_font_color_hover' => '#666',
        'panel_third_level_font' => 'inherit',
        'panel_third_level_font_size' => '14px',
        'flyout_width' => 'auto',
        'flyout_menu_background_from' => 'rgb(255, 255, 255)',
        'flyout_menu_background_to' => 'rgb(255, 255, 255)',
        'flyout_background_from' => 'rgb(255, 255, 255)',
        'flyout_background_to' => 'rgb(255, 255, 255)',
        'flyout_background_hover_from' => 'rgb(50, 122, 231)',
        'flyout_background_hover_to' => 'rgb(50, 122, 231)',
        'flyout_link_size' => '14px',
        'flyout_link_color' => 'rgb(51, 51, 51)',
        'flyout_link_color_hover' => 'rgb(255, 255, 255)',
        'flyout_link_family' => 'inherit',
        'responsive_breakpoint' => '991px',
        'shadow' => 'on',
        'transitions' => 'on',
        'toggle_background_from' => '#222',
        'toggle_background_to' => '#222',
        'mobile_background_from' => 'rgb(255, 255, 255)',
        'mobile_background_to' => 'rgb(255, 255, 255)',
        'mobile_menu_item_link_font_size' => '15px',
        'mobile_menu_item_link_color' => 'rgb(51, 51, 51)',
        'mobile_menu_item_link_text_align' => 'left',
        'mobile_menu_item_link_color_hover' => 'rgb(255, 255, 255)',
        'mobile_menu_item_background_hover_from' => 'rgb(50, 122, 231)',
        'mobile_menu_item_background_hover_to' => 'rgb(50, 122, 231)',
        'disable_mobile_toggle' => 'on',
        'custom_css' => '/** Push menu onto new line **/
#{$wrap} {
    clear: both;
}',
    ];

    return $themes;
}
add_filter("megamenu_themes", "megamenu_add_theme_starter_theme");

