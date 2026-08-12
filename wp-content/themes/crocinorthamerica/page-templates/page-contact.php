<?php /* Template Name: Contact Page */?>

<?php get_header(); ?>
<main class="main-content">
  <div class="container">
    <?php if (function_exists('yoast_breadcrumb')) {
    $before = '<div class="breadcrumb-wrap"><p class="breadcrumbs">';
    $after = '</p></div>';
    yoast_breadcrumb($before, $after);
} ?>
    <section class="content">
      <?php if (have_posts()): while (have_posts()): the_post(); ?>
      <article itemscope itemtype="http://schema.org/Article" id="post_<?php the_ID()?>" <?php post_class()?>>
        <h1 class="page-title" itemprop="headline">
          <?php otm_page_heading(); ?>
        </h1>
        <?php the_content(); ?>
        <div class="row">
          <div class="col-12 col-md-6 col-lg-5">
            <div class="form-wrapper p-15">
              <h3>Request Consultation</h3>
              <?php get_template_part('includes/vertical-form')?>
            </div><!--/.form-wrapper-->
          </div><!--/.col-->
          <div class="col-12 col-md-6 col-lg-7 contact-info">
            <h3>Contact Info</h3>
            <h6 class="mb-1">Address</h6>
            <p><i class="fas fa-map-marker-alt text-secondary mr-1"></i> <?php echo do_shortcode(' [otm-full-address]'); ?></p>
            <h6 class="mb-1">Working Hours</h6>
            <p><i class="far fa-calendar-alt text-secondary mr-1"></i> Monday to Friday<br>
            <i class="far fa-clock text-secondary mr-1"></i> 8:30AM-5:00PM<br></p>
            <h6 class="mb-1">Phone and Fax</h6>
            <p><i class="fas fa-phone text-secondary mr-1"></i></i> <a href="tel:+1-281-643-2000" title="Call Us"><?php echo do_shortcode('[otm-phone-number]'); ?></a><br>
            <i class="fas fa-fax text-secondary mr-1"></i> <a href="tel:+1-281-643-2000" title="Fax Us"><?php echo do_shortcode('[otm-fax-number]'); ?></a></p>
            <h6 class="mb-1">Email</h6>
            <p><i class="far fa-envelope text-secondary mr-1"></i> <a href="mailto:<?php echo do_shortcode('[otm-email-address]'); ?>" title="Email Us"><?php echo do_shortcode('[otm-email-address]'); ?></a>
            </p>
          </div><!--/.contact-info-->
        </div><!--/.row-->
      </article>
      <?php
        endwhile;
        else :
          get_template_part('loops/404');
        endif;
      ?>
    </section>
  </div><!--/.container-->
</main>
<?php get_footer(); ?>
