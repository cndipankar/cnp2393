<?php get_header(); ?>
<section class="inner-hero">
  <div class="container">
    <div class="small">
      <div class="img-wrapper">
        <picture>
          <source srcset="/wp-content/themes/crocinorthamerica/assets/img/backgrounds/croci-office.webp" type="image/webp">
          <source srcset="/wp-content/themes/crocinorthamerica/assets/img/backgrounds/croci-office.jpg" type="image/jpeg">
          <img src="/wp-content/themes/crocinorthamerica/assets/img/backgrounds/croci-office.jpg" alt=" " title=" ">
        </picture>
      </div>
      <div class="hero-content">
        <p class="sub-heading">Croci</p>
        <h2>News</h2>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container inner">
    <div class="row main">
      <div class="col-12 col-lg-8">
        <?php get_template_part('loops/single-post-custom', get_post_format()); ?>
      </div>
      <div class="col-12 col-lg-4">
        <?php get_sidebar('post'); ?>
      </div>
    </div>
  </div>
</section>
<?php get_template_part('includes/client-reviews');?>
<?php get_template_part('includes/footer-contact-section');?>
<?php get_footer(); ?>