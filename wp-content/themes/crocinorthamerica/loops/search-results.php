<?php
/*
 * The Search Results Loop
 */
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
<div <?php post_class("mb-md-3 mb-2 p-md-2 p-1 border-top border-secondary post-block"); ?> >
  <h2>
    <a href="<?php the_permalink(); ?>" title="<?php echo the_title()?>">
      <?php the_title()?>
    </a>
  </h2>
  <?php the_excerpt(); ?>
  <a class="btn btn-secondary" href="<?php the_permalink(); ?>" title="Click To Read Full Article">Read More</a>
</div>
<hr>

<?php endwhile; else: ?>
<div <?php post_class("mb-md-3 mb-2 p-md-2 p-1 border-top border-secondary post-block no-results"); ?> >
  <p class="mb-0 p-1 border border-primary d-flex justify-content-center">
    <i class="fas fa-exclamation-triangle text-secondary mr-1"></i> <strong><?php _e('Sorry, your search yielded no results.', 'otm_theme'); ?></strong>
  </p>
</div>
<hr>
<h4>Want to search again?</h4>
<?php get_template_part('includes/search-form');?>
<?php endif; ?>
