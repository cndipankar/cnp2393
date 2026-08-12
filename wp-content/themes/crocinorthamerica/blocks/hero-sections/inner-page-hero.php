<?php
/**
 *
 * Inner Page Hero - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -1);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}

$t = get_field('type');
$l = get_field('large');
$s = get_field('small');
?>

<section class="inner-hero <?php echo esc_attr($classes); ?>">
    <div class="container">
        <?php if ($t == 'large'): ?>
            <div class="large">
                <div class="img-wrapper">
                    <?php if ($l['img']): ?>
                        <picture>
                        <?php if ($l['webp-img']): ?>
                            <source srcset="<?php echo $l['webp-img']['url'] ?>" type="image/webp">
                        <?php endif; ?>
                        <source srcset="<?php echo $l['img']["url"] ?>" type="<?php echo $l['img']['mime_type'] ?>">
                        <?php
                            echo '<img alt="'. $l['img']['alt'] .'" title="'. $l['img']['title'] .'" src="'. $l['img']["url"] .'">';
                        ?>
                        </picture>
                    <?php endif; ?>
                </div>
                <div class="hero-content">
                    <?php 
                        if($l['tag'] == 'h1'):
                            if($l['heading']): 
                            echo '<h1 class="title"><span class="marker"></span>' . $l['heading'] . '</h1>';
                            endif;
                        elseif($l['tag'] == 'h2'):
                            if($l['heading']): 
                            echo '<h2 class="title"><span class="marker"></span>' . $l['heading'] . '</h2>';
                            endif;
                        elseif($l['tag'] == 'h3'):
                            if($l['heading']): 
                            echo '<h3 class="title"><span class="marker"></span>' . $l['heading'] . '</h3>';
                            endif;
                        endif;
                        if($l['description']): 
                            echo '<p>' . $l['description'] . '</p>';
                        endif;
                    ?>
                    <?php
                    if($l['enable_rm'] == '1'): ?>
                        <div id="hero-<?php echo $id; ?>" class="rm collapse"><?php echo '<p>' . $l['rm_content'] . '</p>'; ?></div>
                        <a role="button" class="rm-btn collapsed mt-1" data-toggle="collapse" data-target="#hero-<?php echo $id; ?>"
                        href="#hero-<?php echo $id; ?>" aria-expanded="false" aria-controls="hero-<?php $id; ?>"></a>
                    <?php endif; ?>
                </div>
            </div>
        <?php elseif ($t == 'small'): ?>
            <div class="small">
                <div class="img-wrapper">
                    <?php if ($s['img']): ?>
                        <picture>
                        <?php if ($s['webp-img']): ?>
                            <source srcset="<?php echo $s['webp-img']['url'] ?>" type="image/webp">
                        <?php endif; ?>
                        <source srcset="<?php echo $s['img']["url"] ?>" type="<?php echo $s['img']['mime_type'] ?>">
                        <?php
                            echo '<img alt="'. $s['img']['alt'] .'" title="'. $s['img']['title'] .'" src="'. $s['img']["url"] .'">';
                        ?>
                        </picture>
                    <?php endif; ?>
                </div>
                <div class="hero-content">
                    <?php 
                        if ($s['sub-heading']):
                            echo '<p class="sub-heading">' . $s['sub-heading'] . '</p>';
                        endif;
                        if($s['tag'] == 'h1'):
                            if($s['heading']): 
                            echo '<h1>' . $s['heading'] . '</h1>';
                            endif;
                        elseif($s['tag'] == 'h2'):
                            if($s['heading']): 
                            echo '<h2>' . $s['heading'] . '</h2>';
                            endif;
                        elseif($s['tag'] == 'h3'):
                            if($s['heading']): 
                            echo '<h3>' . $s['heading'] . '</h3>';
                            endif;
                        endif;
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>