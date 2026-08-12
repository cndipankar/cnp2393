<?php
/**
 *
 * Animated Product Cards - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -1);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
$c1 = get_field('card_one');
$c2 = get_field('card_two');
?>

<div class="animated-product-cards">
    <div class="img-cards">
        <div class="ac">
            <a href="<?php if ($c1['url']): echo $c1['url']; endif; ?>" title="<?php if ($c1['img']['title']): echo $c1['img']['title']; endif ?>">
                <picture>
                    <?php if ($c1['webp_img']): ?>
                    <source srcset="<?php echo $c1['webp_img']['url'] ?>" type="image/webp">
                    <?php endif; ?>
                    <source srcset="<?php echo $c1['img']['url'] ?>" type="<?php echo $c1['img']["mime_type"] ?>">
                    <?php echo '<img alt="'. $c1['img']['alt'] .'" title="'. $c1['img']['title'] .'" src="'. $c1['img']["url"] .'">'; ?>
                </picture>
                <?php if ($c1['heading']): 
                    echo '<h3>' . $c1['heading'] . '</h3>';
                    echo '<div class="line"></div>';
                endif; ?>
            </a>
        </div>
        <div class="ac">
            <a href="<?php if ($c2['url']): echo $c2['url']; endif; ?>" title="<?php if ($c2['img']['title']): echo $c2['img']['title']; endif ?>">
                <picture>
                    <?php if ($c2['webp_img']): ?>
                    <source srcset="<?php echo $c2['webp_img']['url'] ?>" type="image/webp">
                    <?php endif; ?>
                    <source srcset="<?php echo $c2['img']['url'] ?>" type="<?php echo $c2['img']["mime_type"] ?>">
                    <?php echo '<img alt="'. $c2['img']['alt'] .'" title="'. $c2['img']['title'] .'" src="'. $c2['img']["url"] .'">'; ?>
                </picture>
                <?php if ($c2['heading']): 
                    echo '<h3>' . $c2['heading'] . '</h3>';
                    echo '<div class="line"></div>';
                endif; ?>
            </a>
        </div>
    </div>
</div>