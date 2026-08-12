<?php
/**
 * Homepage Hero Block Template.
 */
$id = substr($block['id'], -2);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}

?>

<section <?php echo 'id="' . 'hero' . $id . '"'; ?> class="homepage-hero">
  <div class="container">
    <?php if (have_rows('hero_slider')):
        $i = 1;
        echo '<div class="swiper-container hero-slider">';
        echo '<div class="swiper-wrapper">';
        while (have_rows('hero_slider')) : the_row();
            $img = get_sub_field('img');
            $webpImg = get_sub_field('webp_img');
        ?>
        <div class="swiper-slide">
        <?php if ($img): ?>
          <picture>
            <?php if ($webpImg): ?>
            <source srcset="<?php echo $webpImg['url'] ?>" type="image/webp">
            <?php endif; ?>
            <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img['mime_type'] ?>">
            <?php
              echo '<img alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">';
              ?>
          </picture>
        <?php endif; ?>
        </div>
    <?php $i++;
        endwhile; //End the loop
        echo '</div>';
        echo '</div>';
    else :
        echo '<p>Please add slider images.</p>';
    endif; //End Image Slider ?>
    <div class="hero-content">
    <?php if (have_rows('hero_slider')):
        $i = 1;
        echo '<div class="swiper-container hero-slider">';
        echo '<div class="swiper-wrapper">';
        while (have_rows('hero_slider')) : the_row();
            $cont = get_sub_field('hero_content');
        ?>
      <div class="swiper-slide">
      <?php if($cont['tag'] == 'h1'):
        if($cont['heading']): 
          echo '<h1>' . '<span>' . $cont['f-heading'] . '</span>' . $cont['heading'] . '</h1>';
        endif;
      elseif($cont['tag'] == 'h2'):
        if($cont['heading']): 
          echo '<h2>' . '<span>' . $cont['f-heading'] . '</span>' . $cont['heading'] . '</h2>';
        endif;
      elseif($cont['tag'] == 'h3'):
        if($cont['heading']): 
          echo '<h3>' . '<span>' . $cont['f-heading'] . '</span>' . $cont['heading'] . '</h3>';
        endif;
      endif; ?>
      </div>
      <?php $i++;
        endwhile; //End the loop
        echo '</div>';
        echo '</div>';
      else :
          echo '<p>Please add slider content.</p>';
      endif; //End Content Slider ?>
      <?php
      if($cont['description']): ?>
        <div id="hero-<?php echo $id; ?>" class="rm collapse"><?php echo '<p class="description">' . $cont['description'] . '</p>'; ?></div>
        <a role="button" class="rm-btn collapsed mb-3" data-toggle="collapse" data-target="#hero-<?php echo $id; ?>"
        href="#hero-<?php echo $id; ?>" aria-expanded="false" aria-controls="hero-<?php $id; ?>"></a>
      <?php endif; ?>
      <div class="slider-nav-wrapper">
          <div class="nav-prev slider-nav"><svg width="8" height="13" viewBox="0 0 8 13" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path d="M6.5 2L2 6.5L6.5 11" stroke="#E10915" stroke-width="2" stroke-miterlimit="10"
                stroke-linecap="square" />
            </svg> Previous
          </div>
          <div class="nav-next slider-nav">Next <svg width="8" height="13" viewBox="0 0 8 13" fill="none"
              xmlns="http://www.w3.org/2000/svg">
              <path d="M2 2L6.5 6.5L2 11" stroke="#E10915" stroke-width="2" stroke-miterlimit="10"
                stroke-linecap="square" />
            </svg>
          </div>
      </div>
    </div>
  </div>
</section>