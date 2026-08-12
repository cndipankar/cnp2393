<?php
/**
 *
 * OTM Button Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -2);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}

// Get ACF Fields
$style = get_field('style');
$text = get_field('text');
$url = get_field('url');
$align = get_field('alignment');
$target = get_field('target');
?>

<?php if ($text): ?>
    <a href="<?php echo $url; ?>" class="<?php echo $style . esc_attr($classes); if ($align): echo ' ' . $align; endif; ?>" <?php if ($target == 1): echo 'target="_blank"'; endif; ?> title="<?php echo $text; ?>" <?php if ($style == 'btn1' || 'btn1 small'): echo 'data-text="' . $text . '"'; endif; ?>>
    <?php echo $text; ?>
    </a>
<?php endif; ?>