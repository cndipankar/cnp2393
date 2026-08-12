<?php
/*
 * The Page Content Loop
 */
?>

<?php if (have_posts()): while (have_posts()): the_post(); ?>
  <article id="post_<?php the_ID()?>" <?php post_class()?>>
    <h1 class="page-title">
    <?php otm_page_heading(); ?>
    </h1>
    <?php if (wp_is_mobile() && has_post_thumbnail()):
      the_post_thumbnail('medium', ['class' => 'aligncenter']);
      elseif (has_post_thumbnail()) :
      the_post_thumbnail(); ?>
    <hr>
    <?php endif;?>
    <?php the_content(); ?>
    <?php wp_link_pages(); ?>
  </article>
<?php
  endwhile;
  else :
    get_template_part('loops/404');
  endif;
?>
