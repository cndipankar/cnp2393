<?php $all_queastions = get_post_meta(get_the_ID(), 'faq-schema', true);

$quetion_to_json = function ($question) {
    return '{ "@type": "Question",
                "name": "' . $question['question'] . '",
                "acceptedAnswer": {
                "@type": "Answer",
                "text": "' . $question['answer'] . '"
                }
            }';
};

$all_faqs_array = array_map($quetion_to_json, $all_queastions);

$all_faqs = join(',', $all_faqs_array);

echo '<script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "FAQPage",
      "mainEntity": [' . $all_faqs . ']
    }
</script>';
