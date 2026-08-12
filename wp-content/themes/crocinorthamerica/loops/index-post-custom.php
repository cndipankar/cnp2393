<?php
/*
 * The Index Post (or excerpt)
 * ===========================
 * Used by index.php, category.php and author.php
 */
?>


<div class="card">
  <a href="<?php the_permalink(); ?>" class="stretched-link" title="<?php echo the_title(); ?> - Click to Read More"
    data-text="Read more">
    <?php if(has_post_thumbnail()):?>
    <div class="img-wrapper">
      <img src="<?php echo the_post_thumbnail_url('large'); ?>" alt="Image for <?php echo the_title(); ?> post"
        title="<?php echo the_title(); ?>">
      <div class="hover"></div>
      <svg class="arrow" width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13.9375 1.75L25.1875 13L13.9375 24.25M23.625 13H0.8125" stroke="white" stroke-width="1.5"
          stroke-miterlimit="10" stroke-linecap="square" />
      </svg>
    </div>
    <?php else: ?>
    <div class="img-wrapper">
      <img class="placeholder"
        src="<?php echo get_template_directory_uri(); ?>/assets/img/backgrounds/blog-img-placeholder.png"
        alt="Blog Image Placeholder" title="<?php echo the_title(); ?>">
      <div class="hover"></div>
      <svg class="arrow" width="27" height="26" viewBox="0 0 27 26" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M13.9375 1.75L25.1875 13L13.9375 24.25M23.625 13H0.8125" stroke="white" stroke-width="1.5"
          stroke-miterlimit="10" stroke-linecap="square" />
      </svg>
    </div>
    <?php endif;?>
  </a>
  <div class="card-body">
    <time class="date" datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date('m/d/o'); ?></time>
    <h3 class="card-title"><?php echo mb_strimwidth(get_the_title(), 0, 75, '...'); ?></h3>
    <a href="<?php the_permalink(); ?>" class="btn1 small stretched-link"
      title="<?php echo the_title(); ?> - Click to Read More" data-text="Read article">Read article</a>
  </div>
</div>