<?php
/**
 *
 * Testimonials Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -2);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
?>
<?php $testimonials = get_option('otm_theme_options')['testimonials']; if ($testimonials): ?>
<div class="testimonials">
    <?php foreach ($testimonials as $testimonial): ?>
        <div class="testimonial-block">
        <p><?php echo $testimonial['testimonial'] ?></p>
        <div class="author">
            <?php if ($testimonial['testimonial-author']) { ?>
            <div class="divider"></div>
            <h6><?php echo $testimonial['testimonial-author'] ?></h6>
            <?php } ?>
        </div>
        </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="p-lg-5 p-md-4 p-3 bg-white">Please add testimonials.</p>
<?php endif; ?>
</div>