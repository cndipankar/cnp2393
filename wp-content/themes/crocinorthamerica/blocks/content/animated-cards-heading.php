<?php
/**
 *
 * Animated Cards with Heading - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -1);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
$heading = get_field('heading');
$rhead = get_field('red_heading');
$desc = get_field('description');
$c1 = get_field('card_one');
$c2 = get_field('card_two');
$c3 = get_field('card_three');
?>

<div class="ach">
    <div class="row">
        <div class="col-12 col-lg-3">
            <div class="content">
            <?php if ($heading): 
                echo '<h2>' . $heading . ' <span>' . $rhead . '</span>' . '</h2>';
            endif;
            if ($desc): 
                echo '<p>' . $desc . '</p>';
            endif; ?>
            </div>
        </div>
        <div class="col-12 col-lg-9">
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
                            echo '<h3>' . $c1['heading'] . '<br><span>' . $c1['bold_heading'] . '</span>' . '</h3>';
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
                            echo '<h3>' . $c2['heading'] . '<br><span>' . $c2['bold_heading'] . '</span>' . '</h3>';
                            echo '<div class="line"></div>';
                        endif; ?>
                    </a>
                </div>
                <div class="ac">
                    <a href="<?php if ($c3['url']): echo $c3['url']; endif; ?>" title="<?php if ($c3['img']['title']): echo $c3['img']['title']; endif ?>">
                        <picture>
                            <?php if ($c3['webp_img']): ?>
                            <source srcset="<?php echo $c3['webp_img']['url'] ?>" type="image/webp">
                            <?php endif; ?>
                            <source srcset="<?php echo $c3['img']['url'] ?>" type="<?php echo $c3['img']["mime_type"] ?>">
                            <?php echo '<img alt="'. $c3['img']['alt'] .'" title="'. $c3['img']['title'] .'" src="'. $c3['img']["url"] .'">'; ?>
                        </picture>
                        <?php if ($c3['heading']): 
                            echo '<h3>' . $c3['heading'] . '<br><span>' . $c3['bold_heading'] . '</span>' . '</h3>';
                            echo '<div class="line"></div>';
                        endif; ?>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>