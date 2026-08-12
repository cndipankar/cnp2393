<?php
/**
 * Block template file: blocks/content/read-more-content.php
 *
 *
 */

$img = get_field('img');
$img_web = get_field('img-webp');
$mob_img = get_field('mob-img');
$mob_img_web = get_field('mob-img-webp');
?>
<?php if ($img && $img_web && $mob_img && $mob_img_web): ?>
<div class="res-img">
    <picture class="full">
    <?php if ($img_web): ?>
        <source srcset="<?php echo $img_web['url'] ?>" type="image/webp">
    <?php endif; ?>
    <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img["mime_type"] ?>">
    <?php
        echo '<img alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">';
    ?>
    </picture>
    <picture class="mob">
    <?php if ($mob_img_web): ?>
        <source srcset="<?php echo $mob_img_web['url'] ?>" type="image/webp">
    <?php endif; ?>
    <source srcset="<?php echo $mob_img['url'] ?>" type="<?php echo $mob_img["mime_type"] ?>">
    <?php
        echo '<img alt="'. $mob_img['alt'] .'" title="'. $mob_img['title'] .'" src="'. $mob_img["url"] .'">';
    ?>
    </picture>
</div>
<?php endif; ?>