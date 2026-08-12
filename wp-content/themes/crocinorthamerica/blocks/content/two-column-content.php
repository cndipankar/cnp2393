<?php
/**
 *
 * Two-column Content - Block Template.
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
$c = get_field('content');
$align = get_field('alignment');
$img = get_field('img');
$webp = get_field('webp_img');
$addOverlay = get_field('add_overlay');
$overlay = get_field('overlay');
$rev = get_field('reverse');
?>

<div class="row tcc <?php echo esc_attr($classes); if ($rev == '1'): echo 'flex-row-reverse '; endif; if($align): echo $align; endif; ?>">
    <div class="col-12 col-lg-6">
        <div class="content <?php if ($rev == '1'): echo 'ml-lg-auto'; endif;?>">
            <?php 
                if($h['tag'] == 'h2'):
                    if($h['heading']): 
                    echo '<h2><span class="marker"></span>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h2>';
                    endif;
                elseif($h['tag'] == 'h3'):
                    if($h['heading']): 
                    echo '<h3><span class="marker"></span>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h3>';
                    endif;
                endif;
                if ($c):
                    echo $c;
                endif;
            ?>
            <InnerBlocks />
        </div>
    </div>
    <div class="col-12 col-lg-6">
        <div class="img-wrapper">
            <?php if ($img): ?>
                <picture>
                <?php if ($webp): ?>
                    <source srcset="<?php echo $webp['url'] ?>" type="image/webp">
                <?php endif; ?>
                <source srcset="<?php echo $img["url"] ?>" type="<?php echo $img['mime_type'] ?>">
                <?php
                    echo '<img alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">';
                ?>
                </picture>
            <?php endif; ?>
            <?php if ($addOverlay == '1'): ?>
                <?php if ($overlay['heading']): ?>
                    <div class="overlay">
                        <h4><?php echo $overlay['heading']; ?> <span><?php if ($overlay['highlight']): echo $overlay['highlight']; endif; ?></span></h4>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</div>