<?php
/**
 *
 * Product Profiles - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -1);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
?>
<div class="product-profiles">
    <?php if( have_rows('product_profiles') ): ?>
        <ul class="nav nav-tabs" role="tablist">
            <?php $i=0; while ( have_rows('product_profiles') ) : the_row(); ?>
                <?php 
                    $pc = get_sub_field('profile_code');
                    $url = sanitize_title($pc); 
                ?>
                <li role="presentation" <?php if ($i==0) { ?>class="active"<?php } ?>  >
                    <a class="btn2 <?php if ($i==0) { ?>active<?php } ?>" href="#<?php echo $url ?>" aria-controls="<?php echo $url ?>" role="tab" data-toggle="tab"><?php echo $pc; ?></a>
                </li>
            <?php $i++; endwhile; ?>
        </ul>
        <div class="tab-content">
            <?php $i=0; while ( have_rows('product_profiles') ) : the_row(); ?>
                <?php 
                    $img = get_sub_field('img');
                    $webp = get_sub_field('webp_img');
                    $pc = get_sub_field('profile_code');
                    $url = sanitize_title($pc);
                    $den = get_sub_field('density');
                    $series = get_sub_field('series');
                    $desc = get_sub_field('description');
                    $add = get_sub_field('add_color_options');
                ?>
                <div role="tabpanel" class="tab-pane fade <?php if ($i==0) { ?>show active<?php } ?>" id="<?php echo $url; ?>">
                    <div class="main-content">
                        <div class="img-wrapper">
                            <picture>
                                <?php if ($webp): ?>
                                <source srcset="<?php echo $webp['url'] ?>" type="image/webp">
                                <?php endif; ?>
                                <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img["mime_type"] ?>">
                                <?php echo '<img class="img-fluid" alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">'; ?>
                            </picture>
                        </div>
                        <div class="content">
                            <h3><strong><?php echo $pc; ?></strong> <?php echo $den; ?></h3>
                            <p class="sub"><?php echo $series; ?></p>
                            <?php echo $desc; ?>
                        </div>
                    </div>
                    <?php if($add == '1'): ?>
                        <hr>
                        <p class="title">Color options:</p>
                        <div class="color-options">
                            <?php if ( have_rows('color_options') ) : ?>
                                <?php while( have_rows('color_options') ) : the_row(); 
                                $color = get_sub_field('color_option'); ?>
                                <img src="<?php echo $color['url']; ?>" alt="<?php echo $color['alt']; ?>" title="<?php echo $color['title']; ?>">
                                <?php endwhile; ?>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php $i++; endwhile; ?>
        </div>
    <?php endif; ?>
</div>