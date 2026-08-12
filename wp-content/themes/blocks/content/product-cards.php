<?php
/**
 *
 * Animated Cards - Block Template.
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
<div class="product-cards">
    <?php if ( have_rows('product_cards') ) : ?>
    
        <?php while( have_rows('product_cards') ) : the_row(); 
                        
            $img = get_sub_field('img');
            $webp = get_sub_field('webp_img');
            $h = get_sub_field('heading');
            $sub = get_sub_field('sub-heading');
            $addDesc = get_sub_field('add_description');
            $desc = get_sub_field('description');
         ?>
            <div class="product-card <?php if($addDesc == '0'): echo 'small'; endif; ?>">
                <picture>
                    <?php if ($webp): ?>
                    <source srcset="<?php echo $webp['url'] ?>" type="image/webp">
                    <?php endif; ?>
                    <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img["mime_type"] ?>">
                    <?php echo '<img class="img-fluid" alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">'; ?>
                </picture>
                <?php if ($h): ?>
                    <h4><?php echo $h; ?></h4>          
                <?php endif; ?>
                <?php if ($sub): ?>
                    <p class="sub"><?php echo $sub; ?></p>          
                <?php endif; ?>
                <?php if ($addDesc == '1'): ?>
                    <p><?php if ($desc): echo $desc; endif; ?></p>          
                <?php endif; ?>
            </div>
        <?php endwhile; ?>
    
    <?php endif; ?>
    
</div>