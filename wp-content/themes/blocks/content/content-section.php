<?php
/**
 *
 * Content Section - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -1);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}

$content = get_field('content');
$breakpoint = get_field('breakpoint');
$nw = get_field('narrow_width');
$rpt = get_field('rp_top');
$rpb = get_field('rp_bottom');
$rpi = get_field('inline_padding');
$rtp = get_field('reduce_top_padding');
$hide = get_field('hide_to_public');
?>

<section <?php 'id="' . 'c' . $id . '"'; ?> class="content-section <?php if ($breakpoint): echo ' ' . $breakpoint; endif; if ($rpt == "1"): echo ' rpt' ; endif; if ($rpb == "1"): echo ' rpb' ; endif; if ($rtp == "1"): echo ' rtp' ; endif; if ($hide == "1"): echo ' htp' ; endif; ?><?php echo esc_attr($classes); ?>">
  <div class="container<?php if ($nw == "1"): echo ' narrow' ; endif; if ($rpi == "1"): echo ' px-0' ; endif; ?>">
      <div class="inner-content">
        <?php
          if ($content):
            echo $content;
          endif;
        ?>
        <div class="inner-block">
          <InnerBlocks  />
        </div>
      </div>
  </div>
</section>