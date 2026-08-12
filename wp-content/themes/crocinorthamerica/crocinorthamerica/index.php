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
        <h1><?php echo single_post_title(); ?></h1>
      </div>
    </div>
  </div>
</section>
<section class="content">
  <div class="container inner">
  <div class="blog-wrapper">
    <?php
        get_template_part('loops/index-loop');
        if (function_exists('otm_theme_pagination')) {
            otm_theme_pagination();
        } elseif (is_paged()) { ?>
      <ul class="pagination">
        <li class="page-item older">
          <?php next_posts_link('<i class="fas fa-arrow-left"></i> ' . __('Previous', 'otm_theme')) ?></li>
        <li class="page-item newer">
          <?php previous_posts_link(__('Next', 'otm_theme') . ' <i class="fas fa-arrow-right"></i>') ?></li>
      </ul>
    <?php } ?>
  </div>
  </div>
</section>
<?php get_template_part('includes/client-reviews');?>
<?php get_template_part('includes/footer-contact-section');?>
<?php get_footer(); ?>

