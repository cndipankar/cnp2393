<?php $testimonials = get_option('otm_theme_options')['testimonials']; ?>

<?php if ($testimonials): ?>

  <?php foreach ($testimonials as $testimonial): ?>
    <blockquote>
      <p><?php echo $testimonial['testimonial'] ?></p>
      <?php if ($testimonial['testimonial-author']): ?>
      <p>
        <cite>- <?php echo $testimonial['testimonial-author']; ?></cite>
        <?php if ($testimonial['testimonial-date']): ?>
        <span>, <?php echo $testimonial['testimonial-date']; ?></span>
        <?php endif; ?>
      </p>
      <?php endif; ?>
    </blockquote><!--/.testimonial-blockquote-->
  <?php endforeach; ?>

<?php else: ?>

<p>No Testimonials Yet.</p>

<?php endif; ?>
