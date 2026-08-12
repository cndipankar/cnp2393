<section class="testimonial-section">
  <div class="container">
    <h4>Client Testimonials</h4>
    <?php $testimonials = get_option('otm_theme_options')['testimonials']; if ($testimonials): ?>
    <div class="testimonial-wrapper position-relative px-2">
      <div class="swiper-container testimonial-slider">
        <div class="swiper-wrapper">
           <?php foreach ($testimonials as $testimonial): ?>
          <div class="swiper-slide">
            <p class="text-center">“<?php echo $testimonial['testimonial'] ?>”</p>
            <div class="info d-flex flex-wrap justify-content-center">
              <?php if ($testimonial['testimonial-author']) { ?>
                <h6 class="text-center w-100 mb-1"><?php echo $testimonial['testimonial-author'] ?></h6>
              <?php } ?>
              <?php if ($testimonial['testimonial-date']) { ?>
                <p>
                <strong><?php echo $testimonial['testimonial-date'] ?> - </strong>
                <?php } ?>
                <?php for ($i = 0; $i < $testimonial['testimonial-rating']; $i++) { ?>
                <i class="fa fa-star star text-secondary"></i>
                <?php } ?>
                </p>
            </div><!--/.info-->
          </div><!--/.swiper-slide-->
          <?php endforeach; ?>
          <?php else: ?>
            <p class="text-center px-lg-5 px-md-4 px-3">No Testimonials.</p>
          <?php endif; ?>

        </div>
      </div><!--/.testimonial-slider-->
      <!-- Add Navigation Arrows -->
      <div class="test-next slider-nav position-absolute text-center bg-none easing"><i class="icon-arrow-right text-secondary easing"></i></div>
      <div class="test-prev slider-nav position-absolute text-center bg-none easing"><i class="icon-arrow-left text-secondary easing"></i></div>
    </div><!--/.testimonial-wrapper-->
  </div><!--/.container-->
</section>
