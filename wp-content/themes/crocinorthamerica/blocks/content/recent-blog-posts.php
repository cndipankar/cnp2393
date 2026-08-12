<?php
/**
 *
 * Recent Blog Posts - Block Template.
 *
 */
if( ! empty( $block['className'] ) ) {
    $classes .= ' ' . $block['className'];
  }
if( ! empty( $block['align'] ) ) {
    $classes .= ' align' . $block['align'];
}
$h = get_field('heading');
$btn = get_field('btn-text');
$url = get_field('btn-url');
$blog = [
  'post_type' => 'post',
  'order' => 'DESC',
  'posts_per_page' => '4',
];
$posts = new WP_Query( $blog );
?>

<div class="blog-heading">
  <?php 
    if($h['tag'] == 'h2'):
        if($h['heading']): 
        echo '<h2 class="title">' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h2>';
        endif;
    elseif($h['tag'] == 'h3'):
        if($h['heading']): 
        echo '<h3 class="title">' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h3>';
        endif;
    endif;
    if ($btn):
      echo '<a class="txt-btn" href="' . $url . '" title="' . $btn . '">' . $btn . '</a>';
    endif;
  ?>
</div>
<div class="blog-wrapper">
  <?php while($posts->have_posts()): $posts->the_post(); ?>
  <div class="card">
  <a href="<?php the_permalink(); ?>" class="stretched-link" title="<?php echo the_title(); ?> - Click to Read More" data-text="Read more">
    <?php if(has_post_thumbnail()):?>
      <div class="img-wrapper">
        <img src="<?php echo the_post_thumbnail_url('medium'); ?>" alt="Image for <?php echo the_title(); ?> post" title="<?php echo the_title(); ?>">
        <div class="hover"></div>
        <svg class="arrow" width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.9375 1.75L25.1875 13L13.9375 24.25M23.625 13H0.8125" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="square"/>
        </svg>
      </div>
    <?php else: ?>
      <div class="img-wrapper">
        <img class="placeholder" src="<?php echo get_template_directory_uri(); ?>/assets/img/backgrounds/placeholder.png" alt="Blog Image Placeholder" title="<?php echo the_title(); ?>">
        <div class="hover"></div>
        <svg class="arrow" width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path d="M13.9375 1.75L25.1875 13L13.9375 24.25M23.625 13H0.8125" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="square"/>
        </svg>
      </div>
    <?php endif;?>
  </a>
    <div class="card-body">    
      <h5 class="card-title"><?php echo mb_strimwidth(get_the_title(), 0, 75, '...'); ?></h5>    
      <a href="<?php the_permalink(); ?>" class="btn1 small stretched-link" title="<?php echo the_title(); ?> - Click to Read More" data-text="Read more">Read more</a>
    </div>
  </div>
  <?php
    endwhile;
    wp_reset_query();
  ?>
</div>