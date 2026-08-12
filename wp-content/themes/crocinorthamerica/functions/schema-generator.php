<?php
// Defining Schema Generation Functionality
class Schema
{
    /**
     * @var
     */
    protected $schema = [];
    /**
     * @var
     */
    protected $defaultSchemaData = [
        '@context' => 'http://schema.org',
        '@type' => null,
    ];
    public function __construct(string $type)
    {
        $this->defaultSchemaData['@type'] = $type;
    }
    /**
     * Open Schema script
     * @return void
     */
    private function openSchema()
    {
        echo '<script type="application/ld+json">'.PHP_EOL;
    }
    /**
     * Close Schema script
     * @return void
     */
    private function closeSchema()
    {
        echo PHP_EOL.'</script>'.PHP_EOL;
    }
    public function setData(array $data)
    {
        $this->schema = array_merge($this->defaultSchemaData, $data);
    }
    public function print()
    {
        $this->openSchema();
        echo json_encode($this->schema, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        $this->closeSchema();
    }
}
function generateSchema($type, $data)
{
    $schema = new Schema($type);
    $schema->setData($data);
    $schema->print();
}
function otm_person_schema()
{
    $page_schema_markup = get_post_meta(get_the_ID(), 'schema-markup', true);
    $person_social_profiles = $page_schema_markup["person-social-profiles"];
    $urls = [];
    foreach ($person_social_profiles as $person_social_profile_link) {
        $urls[] = $person_social_profile_link["social-profile-url"];
    };
    $data = [
        'name' => $page_schema_markup["person-name"],
        'url' => get_the_permalink(),
        'image' => esc_url($page_schema_markup["person-image"]['url']),
        'jobTitle' => $page_schema_markup["person-job-title"],
        'worksFor' => [
            '@type' => 'Organization',
            'name' => $page_schema_markup["person-company"],
        ],
    ];
    if ($urls) {
        $data['sameAs'] = $urls;
    }
    generateSchema('Person', $data);
}
function otm_faq_schema()
{
    $faqs = get_option('otm_theme_options')['faq'];
    $data = [];
    $faq_group = [];
    if ($faqs):
    foreach ($faqs as $faq) {
        $faq_group[] = [
            '@type' => 'Question',
            'name' => $faq['question'],
            'acceptedAnswer' => [
                '@type' => 'Answer',
                'text' => $faq['answer'],
            ],
        ];
    };
    $data['mainEntity'] = $faq_group;
    generateSchema('FAQPage', $data);
    endif;
}
function otm_local_business_schema()
{
    if (get_post_meta(get_the_ID(), 'enable-structured-data', true) != 1) {
        return;
    }

    if (get_post_meta(get_the_ID(), 'enable-location-schema', true) == 1) {
        $location_schema = get_post_meta(get_the_ID(), 'location-schema', true);
        $current_location_schema = get_option('otm_theme_options')['multiple-location-schema'];
        $get_schema = array_search($location_schema, array_column($current_location_schema, 'location-title'));
        // Schema variables
        if ($current_location_schema[$get_schema]['location-url']) {
            $location_url = $current_location_schema[$get_schema]['location-url'];
        } else {
            $location_url = get_bloginfo('url');
        }
        $schema_telephone = $current_location_schema[$get_schema]['phone'];
        $streetAddress = $current_location_schema[$get_schema]['street'];
        $addressLocality = $current_location_schema[$get_schema]['city'];
        $postalCode = $current_location_schema[$get_schema]['zip-code'];
        $addressRegion = $current_location_schema[$get_schema]['state'];
        $latitude = $current_location_schema[$get_schema]['latitude'];
        $longitude = $current_location_schema[$get_schema]['longitude'];
    } else {
        $location_url = get_bloginfo('url');
        $schema_telephone = get_option('otm_theme_options')['local-business-tabs']['local-business-phone'];
        $streetAddress = get_option('otm_theme_options')['local-business-address-fieldset']['local-business-street'];
        $addressLocality = get_option('otm_theme_options')['local-business-address-fieldset']['local-business-city'];
        $postalCode = get_option('otm_theme_options')['local-business-address-fieldset']['local-business-zip'];
        $addressRegion = get_option('otm_theme_options')['local-business-address-fieldset']['local-business-state'];
        $latitude = get_option('otm_theme_options')['local-business-address-fieldset']['local-business-lat'];
        $longitude = get_option('otm_theme_options')['local-business-address-fieldset']['local-business-long'];
    }
    $data = [
        '@type' => get_option('otm_theme_options')['local-business-tabs']['local-business-type'],
        '@id' => get_bloginfo('url') . '#LocalBusiness',
        'name' => get_option('otm_theme_options')['local-business-tabs']['local-business-name'],
        'image' => esc_url(get_option('otm_theme_options')['local-business-tabs']['local-business-image']['url']),
        'logo' => esc_url(get_option('otm_theme_options')['local-business-tabs']['local-business-image']['url']),
        'url' => $location_url,
        'telephone' => $schema_telephone,
        'priceRange' => get_option('otm_theme_options')['local-business-tabs']['local-business-price-range'],
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => $streetAddress,
            'addressLocality' => $addressLocality,
            'postalCode' => $postalCode,
            'addressCountry' => get_option('otm_theme_options')['local-business-address-fieldset']['local-business-country'],
        ],
        'geo' => [
            '@type' => 'GeoCoordinates',
            'latitude' => $latitude,
            'longitude' => $longitude,
        ],
    ];
    if (get_option('otm_theme_options')['local-business-address-fieldset']['local-business-country'] == 'US') {
        $data['address']['addressRegion'] = get_option('otm_theme_options')['local-business-address-fieldset']['local-business-state'];
    } elseif (get_option('otm_theme_options')['local-business-address-fieldset']['local-business-country'] == 'CA') {
        $data['address']['addressRegion'] = get_option('otm_theme_options')['local-business-address-fieldset']['local-business-region'];
    }
    $local_business_open_247 = get_option('otm_theme_options')['local-business-opening-hours-fieldset']["local-business-open-247"];
    $local_business_opening_hours = get_option('otm_theme_options')['local-business-opening-hours-fieldset']["local-business-opening-hours"];
    if ($local_business_open_247):
    $data['openingHoursSpecification']['@type'] = 'OpeningHoursSpecification';
    $data['openingHoursSpecification']['dayOfWeek'] = [
        'Monday',
        'Tuesday',
        'Wednesday',
        'Thursday',
        'Friday',
        'Saturday',
        'Sunday',
    ];
    $data['openingHoursSpecification']['opens'] = '00:00';
    $data['openingHoursSpecification']['closes'] = '23:59'; else:
    $wh_group = [];
    foreach ($local_business_opening_hours as $opening_hours_entry) {
        $wh_group[] = [
            '@type' => 'OpeningHoursSpecification',
            'dayOfWeek' => $opening_hours_entry['local-business-opening-hours-days'],
            'opens' => $opening_hours_entry['local-business-opening-hours-opens'],
            'closes' => $opening_hours_entry['local-business-opening-hours-closes'],
        ];
    };
    $data['openingHoursSpecification'] = $wh_group;
    endif;
    //Testimonials
    if (get_option('otm_theme_options')['local-business-tabs']['use-local-business-rating'] == true):
    if (get_option('otm_theme_options')['local-business-tabs']['local-business-use-testimonial-rating'] == true):
      $testimonials = get_option('otm_theme_options')['testimonials'];
    $testimonial_ratings = [];
    if ($testimonials):
        foreach ($testimonials as $testimonial):
          array_push($testimonial_ratings, $testimonial['testimonial-rating']);
    endforeach;
    $testimonial_ratings_average = round(array_sum($testimonial_ratings) / count($testimonial_ratings), 1);
    $data['aggregateRating'] = [
        '@type' => 'AggregateRating',
        'ratingValue' => (string) $testimonial_ratings_average,
        'ratingCount' => (string) sizeof(get_option('otm_theme_options')['testimonials']),
    ];
    endif; else:
      $data['aggregateRating'] = [
          '@type' => 'AggregateRating',
          'ratingValue' => get_option('otm_theme_options')['local-business-tabs']['local-business-rating'],
          'ratingCount' => get_option('otm_theme_options')['local-business-tabs']['local-business-rating-count'],
      ];
    endif;
    endif;
    //Social Profiles
    if (get_option('otm_theme_options')['local-business-tabs']['use-local-business-social-profiles'] == true):
    if (get_option('otm_theme_options')['local-business-tabs']['local-business-use-social-profiles'] == true):
      $social_profiles = get_option('otm_theme_options')['social-profiles'];
    $social_profiles_urls = [];
    if ($social_profiles):
        foreach ($social_profiles as $social_profile_link) {
            $social_profiles_urls[] = $social_profile_link["social-profile-url"];
        };
    $data['sameAs'] = $social_profiles_urls;
    endif; else:
      $social_profiles = get_option('otm_theme_options')['local-business-tabs']['local-business-social-profiles'];
    $social_profiles_urls = [];
    foreach ($social_profiles as $social_profile_link) {
        $social_profiles_urls[] = $social_profile_link["social-profile-url"];
    };
    if ($social_profiles_urls) {
        $data['sameAs'] = $social_profiles_urls;
    }
    endif;
    endif;
    generateSchema('Local', $data);
}
if (get_option('otm_theme_options')['use-local-business-schema'] == true) {
    add_action('wp_head', 'otm_local_business_schema', 10);
}
function otm_website_schema()
{
    $data = [
        'name' => get_bloginfo('title'),
        'url' => get_bloginfo('url'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => get_bloginfo('url') . '?s={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];
    generateSchema('WebSite', $data);
}
add_action('wp_head', 'otm_website_schema', 10);
function otm_schema_markup()
{
    $page_schema_markup = get_post_meta(get_the_ID(), 'schema-markup', true);
    $page_schema_enabled = get_post_meta(get_the_ID(), 'enable-schema-markup', true);
    if ($page_schema_enabled && $page_schema_markup):
        $page_schema_markup = $page_schema_markup['schema-markup'];
    if ($page_schema_markup == 'person'):
          otm_person_schema(); elseif (is_page_template('page-faqs.php')):
          otm_faq_schema();
    endif; else:
        return false;
    endif;
}
add_action('wp_head', 'otm_schema_markup', 100);
