<?php
/*
 * The Index Post (or excerpt)
 * ===========================
 * Used by index.php, category.php and author.php
 */
?>


<div <?php post_class("post-block"); ?> >
  <div class="row">
    <div class="col-12 col-md-4 d-flex flex-wrap">
      <?php if (has_post_thumbnail()):?>
      <img class="card-img-top align-self-center" src="<?php echo the_post_thumbnail_url('thumbnail'); ?>" alt="Image for <?php echo the_title(); ?> post" title="<?php echo the_title(); ?>">
      <?php endif;?>
    </div><!--/.col-->
    <div class="col-12 col-md-8">
      <h2>
        <a href="<?php the_permalink(); ?>" title="<?php echo the_title()?>">
          <?php the_title()?>
        </a>
      </h2>
      <?php the_excerpt(); ?>
      <a class="btn btn-secondary" href="<?php the_permalink(); ?>" title="Click To Read Full Article">Read More</a>
    </div>
  </div>
</div>
