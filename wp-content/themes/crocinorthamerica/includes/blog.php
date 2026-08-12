<section class="blog-section">
  <div class="container">
    <h4>Latest News</h4>
    <div class="row">
      <?php
        $blogrow = [
            'post_type' => 'post',
            'order' => 'DESC',
            'posts_per_page' => '3',
        ];
        $threeposts = new WP_Query($blogrow);
      ?>
      <?php while ($threeposts->have_posts()): $threeposts->the_post(); ?>
      <div class="col-12 col-md-4">
        <div class="card p-2">
          <?php if (has_post_thumbnail()):?>
          <img class="card-img-top" src="<?php echo the_post_thumbnail_url('medium'); ?>" alt="Image for <?php echo the_title(); ?> post" title="<?php echo the_title(); ?>">
          <?php endif;?>
          <div class="card-block">
            <h5 class="card-title"><?php echo the_title(); ?></h5>
            <time class="date d-block"><?php echo the_date('F j, Y');?></time>
            <p class="card-text"><?php echo wp_trim_words(get_the_content(), 15, ' ...');?></p>
            <a href="<?php the_permalink(); ?>" class="btn btn-secondary" title="<?php echo the_title(); ?> - Click to Read More">Read Article</a>
          </div>
        </div><!--/.card-->
      </div><!--/.col-->
      <?php
        endwhile;
        wp_reset_query();
      ?>
    </div><!--/.row-->
  </div><!--/.container-->
</section>
