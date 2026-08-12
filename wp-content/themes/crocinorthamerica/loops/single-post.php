<?php
/*
 * The Single Post
 */
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
  <article role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
      <h1 class="page-title"><?php the_title()?></h1>
      <h6>By <?php the_author(); ?> on <?php the_date('F j, Y'); ?></h6>
    <?php if (wp_is_mobile() && has_post_thumbnail()):
      the_post_thumbnail('medium', ['class' => 'aligncenter']);
      elseif (has_post_thumbnail()) :
      the_post_thumbnail('large'); ?> 
    <hr>
    <?php endif;?>
    <?php
      the_content();
      wp_link_pages();
    ?>
  </article>
  <hr>
  <section class="py-3">
    <?php if (has_category()) :?>
    <p class="mb-1"><i class="fas fa-tag text-secondary mr-1"></i> <strong>Category:</strong> <?php the_category(', '); ?></p>
    <?php endif; ?>  
    <?php if (has_tag()) :?>
    <p class="mb-0"><i class="fas fa-tag text-secondary mr-1"></i> <?php the_tags('<strong>Tags:</strong> '); ?></p>
    <?php endif; ?>
  </section>
<?php
  endwhile; else :
    get_template_part('loops/404');
  endif;
?>
