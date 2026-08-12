<?php 
$blog = [
    'post_type' => 'post',
    'order' => 'DESC',
    'posts_per_page' => '5',
];
$posts = new WP_Query( $blog );
?>
<aside class="sidebar">
    <h3 class="sidebar-title">Latest Articles</h3>
    <?php while($posts->have_posts()): $posts->the_post(); ?>
    <div class="post">
        <time class="date" datetime="<?php echo get_the_date('c'); ?>" itemprop="datePublished"><?php echo get_the_date('m/d/o'); ?></time>
        <a href="<?php the_permalink(); ?>" title="<?php echo the_title();?>"><?php echo mb_strimwidth(get_the_title(), 0, 75, '...'); ?></a>
    </div>
    <?php
        endwhile;
        wp_reset_query();
    ?>
</aside>