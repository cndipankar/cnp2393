<?php
/**
 *
 * Shutter Plan - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -1);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
$h = get_field('heading');
?>
<div class="shutter-plans">
    <?php 
        if($h['tag'] == 'h2'):
            if($h['heading']): 
            echo '<h2 class="title text-left"><span class="marker"></span>' . $h['heading'] . '</h2>';
            endif;
        elseif($h['tag'] == 'h3'):
            if($h['heading']): 
            echo '<h3 class="title text-left"><span class="marker"></span>' . $h['heading'] . '</h3>';
            endif;
        endif;
    ?>
    <?php if ( have_rows('shutter_plans') ) : ?>
        <div class="plan-views">
            <?php while( have_rows('shutter_plans') ) : the_row();
            $heading = get_sub_field('heading');
            $img = get_sub_field('img');        
            ?>
            <div class="plan-view">
                <?php if ( $heading ) : ?>
                    <h4><?php echo $heading; ?></h4>
                <?php endif; ?>
                <?php if ( $img ) : ?>
                    <a href="<?php echo $img['url']; ?>" data-toggle="lightbox"
                        data-title="<?php if($heading): echo $heading; endif; ?>" data-gallery="<?php echo 'gallery-' . $id; ?>">
                        <img src="<?php echo $img['url']; ?>" alt="<?php echo $img['alt']; ?>" title="<?php echo $img['title']; ?>">
                    </a>
                    <div class="hover"></div>
                    <svg class="icon" width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19.9734 8.75C13.8844 8.75 7.63828 12.2742 2.72109 19.3227C2.58074 19.5261 2.50382 19.7665 2.50007 20.0136C2.49631 20.2607 2.56589 20.5034 2.7 20.7109C6.47812 26.625 12.6406 31.25 19.9734 31.25C27.2266 31.25 33.5156 26.6109 37.3008 20.6836C37.4317 20.4801 37.5014 20.2432 37.5014 20.0012C37.5014 19.7592 37.4317 19.5223 37.3008 19.3187C33.507 13.4594 27.1719 8.75 19.9734 8.75Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 26.25C23.4518 26.25 26.25 23.4518 26.25 20C26.25 16.5482 23.4518 13.75 20 13.75C16.5482 13.75 13.75 16.5482 13.75 20C13.75 23.4518 16.5482 26.25 20 26.25Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10"/>
                    </svg>
                <?php endif; ?> 
            </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
    
</div>