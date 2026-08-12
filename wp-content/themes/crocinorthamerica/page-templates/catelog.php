
<?php 
/* Template Name: Catalog Landing page*/
if(!is_user_logged_in()){
	wp_redirect(home_url());
}

get_header(); ?>
<main id="main" role="main">
  <article class="inner-page-wrapper">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      <?php the_content(); ?>
    <?php endwhile; endif; ?>
  </article>
</main>
<?php get_footer(); ?>