<?php
/**
 *
 * Image CTA Block Template.
 *
 */
$id = substr($block['id'], -2);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}

$h = get_field('heading');
$desc = get_field('description');
$btn = get_field('cta-btn');
?>

<?php if ($h): ?>
<div class="text-cta">
    <div class="content-wrap">
        <?php if ($h): ?>
            <h2><?php if($h) { echo $h; } ?></h2>
            <p><?php if($desc) { echo $desc; } ?></p>
        <?php endif; ?>
        <?php if ($btn['text']) { ?>
            <a class="txt-btn" href="<?php echo esc_url($btn['btn-url']); ?>" title="<?php echo $btn['text']; ?>"><?php echo $btn['text']; ?></a>
        <?php } ?>
    </div>
</div>
<?php endif; ?>