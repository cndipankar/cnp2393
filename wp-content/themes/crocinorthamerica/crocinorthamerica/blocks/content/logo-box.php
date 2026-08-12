<?php
/**
 *
 * Logo Box - Block Template.
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
?>

<?php 
    if($h['tag'] == 'h2'):
        if($h['heading']): 
        echo '<h2 class="title"><span class="marker"></span>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h2>';
        endif;
    elseif($h['tag'] == 'h3'):
        if($h['heading']): 
        echo '<h3 class="title"><span class="marker"></span>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h3>';
        endif;
    endif;
?>
<?php
if (have_rows('logos')):
    echo '<div class="container">';
    echo '<div class="logos">';
    while (have_rows('logos')) : the_row();
        $logo = get_sub_field('logo');
        $company = get_sub_field('company');
        $url = get_sub_field('url');
?>
<div class="<?php if ($classes): echo 'logo-box ' . ' ' . $classes; else: echo 'logo-box'; endif; ?>">
<?php if ($logo['logo-img']): ?>
    <picture>
    <?php if ($logo['logo-webp']): ?>
        <source srcset="<?php echo $logo['logo-webp']['url'] ?>" type="image/webp">
    <?php endif; ?>
    <source srcset="<?php echo $logo['logo-img']["url"] ?>" type="<?php echo $logo['logo-img']['mime_type'] ?>">
    <?php
        echo '<img class="logo-img" alt="'. $logo['logo-img']['alt'] .'" title="'. $logo['logo-img']['title'] .'" src="'. $logo['logo-img']["url"] .'">';
    ?>
    </picture>
    <?php else: ?>
    <p>Please add a logo.</p>
<?php endif; ?>
    <div class="hover-content">
        <?php if ($company): ?>
            <p class="title"><?php echo $company; ?></p>
        <?php endif; ?>
        <a href="<?php echo $url; ?>" class="btn1 small" title="Click to visit <?php echo $company; ?>" data-text="Visit" target="_blank">Visit</a>
    </div>
</div>

<?php $i++;
    endwhile;
    echo '</div>';
    echo '</div>';
else :
    echo 'Please add an icon box';
endif;
?>