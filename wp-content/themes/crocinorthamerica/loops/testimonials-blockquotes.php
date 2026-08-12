<?php $testimonials = get_option('otm_theme_options')['testimonials']; if ($testimonials): ?>
  <?php foreach ($testimonials as $testimonial): ?>

    <blockquote class="wp-block-quote testimonial">
      <?php if ($testimonial['testimonial-author']) { ?>
        <h5 class="mb-1"><?php echo $testimonial['testimonial-author'] ?></h5>
      <?php } ?>
      <p>“<?php echo $testimonial['testimonial'] ?>”</p>
      <?php if ($testimonial['testimonial-date']) { ?>
      <p>
      <strong><?php echo $testimonial['testimonial-date'] ?> - </strong>
      <?php } ?>
      <?php for ($i = 0; $i < $testimonial['testimonial-rating']; $i++) { ?>
      <i class="fa fa-star star text-secondary"></i>
      <?php } ?>
      </p>
    </blockquote>
    <hr>
  <?php endforeach; ?>          
  <?php else: ?>
    <p class="text-white text-center px-lg-5 px-md-4 px-3">No Testimonials.</p>
  <?php endif; ?>
