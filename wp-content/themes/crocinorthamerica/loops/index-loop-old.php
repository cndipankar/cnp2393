<?php
/*
 * The Default Loop (used by index.php, category.php and author.php)
 * =================================================================
 * If you require only post excerpts to be shown in index and category pages,
 * use the [---more---] block within blog posts.
 */
$blog = [
  'post_type' => 'post',
  'order' => 'DESC',
  'posts_per_page' => '12',
];
$posts = new WP_Query( $blog );
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

  <?php get_template_part('loops/index-post-custom', get_post_format()); ?>

  <?php endwhile; ?>

  <?php
  else :
    get_template_part('loops/404');
  endif;
?>
