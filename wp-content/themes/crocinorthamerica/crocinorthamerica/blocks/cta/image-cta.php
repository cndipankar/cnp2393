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

$img = get_field('cta-img');
$imgWebp = get_field('cta-img-webp');
$heading = get_field('cta-heading');
$highlight1 = get_field('cta-highlighted-heading-1');
$highlight = get_field('cta-highlighted-heading');
$btn = get_field('cta-btn-text');
$url = get_field('cta-button-url');
?>

<?php if ($img && $imgWebp): ?>
  <div class="img-cta">
    <div class="img-wrapper">
      <picture>
        <?php if ($imgWebp): ?>
        <source srcset="<?php echo $imgWebp['url'] ?>" type="image/webp">
        <?php endif; ?>
        <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img["mime_type"] ?>">
        <?php echo '<img alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">'; ?>
      </picture>
    </div>    
    <div class="cta">
      <?php if ($heading): ?>
          <h4><span><?php if ($highlight1): echo $highlight1; endif; ?></span> <?php echo $heading; ?> <span><?php if ($highlight): echo $highlight; endif; ?></span></h4>
      <?php endif; ?>
      <?php if ($btn): ?>
          <a class="btn1 small" data-text="<?php echo $btn; ?>" href="<?php echo($url); ?>" title="<?php echo $btn; ?>"><?php echo $btn; ?></a>
      <?php endif; ?>
    </div>
  </div>
<?php endif; ?>

<!-- <div class="img-cta">
    <div <?php echo 'id="' . 'cta' . $id . '"'; ?> class="cta-col image-wrap"></div>
    <div class="cta-col content-wrap">
        <div class="content">
            <?php if ($heading): ?>
                <h3 class="title"><?php echo $heading; ?></h3>
            <?php endif; ?>
            <p class="description"><?php if ($des): echo $des; endif; ?></p>
            <?php if ($btn): ?>
                <a class="btn1" href="<?php echo($url); ?>" title="<?php echo $btn; ?>"><?php echo $btn; ?></a>
            <?php endif; ?>
        </div>
    </div>
</div> -->