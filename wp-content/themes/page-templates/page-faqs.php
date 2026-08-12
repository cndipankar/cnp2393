<?php /* Template Name: FAQ Page */?>

<?php get_header(); ?>
<main class="main-content">
  <div class="container">
    <?php if (function_exists('yoast_breadcrumb')) {
    $before = '<div class="breadcrumb-wrap"><p class="breadcrumbs">';
    $after = '</p></div>';
    yoast_breadcrumb($before, $after);
} ?>
    <div class="row">

      <div class="col-12">
        <section class="content">
          <?php get_template_part('loops/page-content'); ?>

          <?php
            $faqs = get_option('otm_theme_options')['faq'];

            if ($faqs): ?>
              <div class="accordion" id="faqAccordion">
              <?php
              $i = 1;
              foreach ($faqs as $faq): ?>
                <div class="card">
                  <div class="card-header" id="question-<?php echo $i; ?>">
                    <h5 class="mb-0">
                      <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#faq-<?php echo $i; ?>" aria-expanded="false" aria-controls="faq-<?php echo $i; ?>">
                        <?php echo $faq['question']; ?>
                      </button>
                    </h5>
                  </div>
                  <div id="faq-<?php echo $i; ?>" class="collapse" aria-labelledby="question-<?php echo $i; ?>" data-parent="#faqAccordion">
                    <div class="card-body">
                      <?php echo $faq['answer']; ?>
                    </div>
                  </div>
                </div>
                <?php $i++; ?>
              <?php endforeach; ?>
              </div>
            <?php endif; ?>
        </section><!--/.content-->
      </div><!--/.col-->

    </div><!--/.row-->
  </div><!--/.container-->
</main>
<?php get_footer(); ?>
