<?php
/**
 *
 * Product Gallery - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -2);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
$h = get_field('heading');
$btn = get_field('btn-text');
$url = get_field('btn-url');
?>

<div class="product-gallery">
    <div class="slider-heading">
        <?php 
            if($h['tag'] == 'h2'):
                if($h['heading']): 
                echo '<h2>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h2>';
                endif;
            elseif($h['tag'] == 'h3'):
                if($h['heading']): 
                echo '<h3>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h3>';
                endif;
            endif;
            if ($btn):
            echo '<a class="txt-btn" href="' . $url . '" title="' . $btn . '">' . $btn . '</a>';
            endif;
        ?>
    </div>
    <div class="slider">
    <?php if ( have_rows('product_images') ) : ?>
        <div class="swiper-container lightbox-wrapper product-slider">
            <div class="swiper-wrapper">
                <?php while( have_rows('product_images') ) : the_row(); 
                    $img = get_sub_field('img');
                    $webp = get_sub_field('webp_img');
                    $credit = get_sub_field('credit');
                ?>
                <div class="swiper-slide">
                    <a href="<?php echo $img['url']; ?>" data-toggle="lightbox"
                        data-footer="<?php if($credit): echo $credit; endif; ?>" data-gallery="<?php echo 'gallery-' . $id; ?>">
                        <picture>
                            <?php if ($webp): ?>
                            <source srcset="<?php echo $webp['url'] ?>" type="image/webp">
                            <?php endif; ?>
                            <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img["mime_type"] ?>">
                            <?php echo '<img class="img-fluid" alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">'; ?>
                        </picture>
                    </a>
                    <div class="hover"></div>
                    <svg class="icon" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.9734 8.75C13.8844 8.75 7.63828 12.2742 2.72109 19.3227C2.58074 19.5261 2.50382 19.7665 2.50007 20.0136C2.49631 20.2607 2.56589 20.5034 2.7 20.7109C6.47812 26.625 12.6406 31.25 19.9734 31.25C27.2266 31.25 33.5156 26.6109 37.3008 20.6836C37.4317 20.4801 37.5014 20.2432 37.5014 20.0012C37.5014 19.7592 37.4317 19.5223 37.3008 19.3187C33.507 13.4594 27.1719 8.75 19.9734 8.75Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 26.25C23.4518 26.25 26.25 23.4518 26.25 20C26.25 16.5482 23.4518 13.75 20 13.75C16.5482 13.75 13.75 16.5482 13.75 20C13.75 23.4518 16.5482 26.25 20 26.25Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10"/>
                    </svg>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
        <?php else: ?>
            <p>Please add product images.</p>
    <?php endif; ?>
        <div class="slider-nav-wrapper">
            <div class="test-nav-prev slider-nav"><svg width="8" height="13" viewBox="0 0 8 13" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M6.5 2L2 6.5L6.5 11" stroke="#E10915" stroke-width="2" stroke-miterlimit="10"
                stroke-linecap="square" />
            </svg> Previous
            </div>
            <div class="test-nav-next slider-nav">Next <svg width="8" height="13" viewBox="0 0 8 13" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M2 2L6.5 6.5L2 11" stroke="#E10915" stroke-width="2" stroke-miterlimit="10"
                stroke-linecap="square" />
            </svg>
            </div>
        </div>
    </div>
</div>