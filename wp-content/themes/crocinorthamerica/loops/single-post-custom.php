<?php
/*
 * The Single Post
 */
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
  <article class="post" role="article" id="post_<?php the_ID()?>" <?php post_class()?>>
    <time class="date" datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date('m/d/o'); ?></time>
      <h1 class="post-title"><span class="marker"></span><?php the_title()?></h1>
      <div class="img-wrapper">
        <?php if (wp_is_mobile() && has_post_thumbnail()):
          the_post_thumbnail('medium', ['class' => 'aligncenter']);
          elseif (has_post_thumbnail()) :
          the_post_thumbnail('large'); ?>
          <?php else:?>
            <img class="placeholder" src="<?php echo get_template_directory_uri(); ?>/assets/img/backgrounds/blog-img-placeholder.png" alt="Blog Image Placeholder" title="<?php echo the_title(); ?>">
        <?php endif;?>
      </div>
    <?php
      the_content();
      wp_link_pages();
    ?>
  </article>
  <a href="/news/" class="txt-btn" title="Back to news">Back to news</a>
<?php
  endwhile; else :
    get_template_part('loops/404');
  endif;
?>