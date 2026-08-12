<?php
/**
 *
 * Heading Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}

$headingTag = get_field('heading-tag');
$align = get_field('alignment');
$heading = get_field('heading');
$red = get_field('red_heading');
?>
<div class="heading">
    <?php if ($headingTag == 'h1'): ?>
        <h1 class="<?php echo esc_attr($classes) . ' ' . $align; ?>"><?php if ($heading): echo $heading; endif; if ($red): echo '<span>' . $red . '</span>'; endif; ; ?></h1>
    <?php elseif ($headingTag == 'h2'): ?>
        <h2 class="<?php echo esc_attr($classes) . ' ' . $align; ?>"><?php if ($heading): echo $heading; endif; if ($red): echo '<span>' . $red . '</span>'; endif; ; ?></h2>
    <?php elseif ($headingTag == 'h3'): ?>
        <h3 class="<?php echo esc_attr($classes) . ' ' . $align; ?>"><?php if ($heading): echo $heading; endif; if ($red): echo '<span>' . $red . '</span>'; endif; ; ?> </h3>
    <?php elseif ($headingTag == 'h4'): ?>
        <h4 class="<?php echo esc_attr($classes) . ' ' . $align; ?>"><?php if ($heading): echo $heading; endif; if ($red): echo '<span>' . $red . '</span>'; endif; ; ?></h4>
    <?php elseif ($headingTag == 'h5'): ?>
        <h5 class="<?php echo esc_attr($classes) . ' ' . $align; ?>"><?php if ($heading): echo $heading; endif; if ($red): echo '<span>' . $red . '</span>'; endif; ; ?></h5>
    <?php elseif ($headingTag == 'h6'): ?>
        <h6 class="<?php echo esc_attr($classes) . ' ' . $align; ?>"><?php if ($heading): echo $heading; endif; if ($red): echo '<span>' . $red . '</span>'; endif; ; ?></h6>
    <?php endif; ?>
</div>