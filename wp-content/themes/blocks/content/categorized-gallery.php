<?php
/**
 *
 * Categorized Gallery - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -2);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
?>

<div class="gallery">
    <ul class="nav nav-tabs" role="tablist">
        <li class="nav-item">
            <a class="btn2 active" data-toggle="tab" href="#rolling-shutters" title="Rolling Shutters">Rolling Shutters</a>
        </li>
        <li class="nav-item">
            <a class="btn2" data-toggle="tab" href="#bahama-and-colonial" title="Bahama & Colonial">Bahama & Colonial</a>
        </li>
        <li class="nav-item">
            <a class="btn2" data-toggle="tab" href="#accordion-shutters" title="Accordion Shutters">Accordion Shutters</a>
        </li>
    </ul>
    <div class="tab-content">
        <?php if ( have_rows('rolling_shutter_photos') ) : ?>
            <div id="rolling-shutters" class="tab-pane fade show active">
                <div class="photos">
                    <?php while( have_rows('rolling_shutter_photos') ) : the_row(); 
                        $img = get_sub_field('img');
                        $webp = get_sub_field('webp_img');
                        $credit = get_sub_field('credit');
                        ?>
                        <div class="img-wrapper">
                            <a href="<?php echo $img['url']; ?>" data-toggle="lightbox"
                                data-footer="<?php if($credit): echo $credit; endif; ?>"
                                data-gallery="gallery-rolling">
                                <picture>
                                    <?php if ($webp): ?>
                                    <source srcset="<?php echo $webp['url'] ?>" type="image/webp">
                                    <?php endif; ?>
                                    <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img["mime_type"] ?>">
                                    <?php echo '<img class="img-fluid" alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">'; ?>
                                </picture>
                            </a>
                            <div class="hover"></div>
                            <svg class="icon" width="40" height="40" viewBox="0 0 40 40" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.9734 8.75C13.8844 8.75 7.63828 12.2742 2.72109 19.3227C2.58074 19.5261 2.50382 19.7665 2.50007 20.0136C2.49631 20.2607 2.56589 20.5034 2.7 20.7109C6.47812 26.625 12.6406 31.25 19.9734 31.25C27.2266 31.25 33.5156 26.6109 37.3008 20.6836C37.4317 20.4801 37.5014 20.2432 37.5014 20.0012C37.5014 19.7592 37.4317 19.5223 37.3008 19.3187C33.507 13.4594 27.1719 8.75 19.9734 8.75Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M20 26.25C23.4518 26.25 26.25 23.4518 26.25 20C26.25 16.5482 23.4518 13.75 20 13.75C16.5482 13.75 13.75 16.5482 13.75 20C13.75 23.4518 16.5482 26.25 20 26.25Z"
                                    stroke="white" stroke-width="1.5" stroke-miterlimit="10" />
                            </svg>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ( have_rows('bahama_and_colonial_photos') ) : ?>
            <div id="bahama-and-colonial" class="tab-pane fade">
                <div class="photos">
                    <?php while( have_rows('bahama_and_colonial_photos') ) : the_row(); 
                        $img2 = get_sub_field('img');
                        $webp2 = get_sub_field('webp_img');
                        $credit2 = get_sub_field('credit');
                        ?>
                        <div class="img-wrapper">
                            <a href="<?php echo $img2['url']; ?>" data-toggle="lightbox"
                                data-footer="<?php if($credit2): echo $credit2; endif; ?>"
                                data-gallery="gallery-bahama">
                                <picture>
                                    <?php if ($webp2): ?>
                                    <source srcset="<?php echo $webp2['url'] ?>" type="image/webp">
                                    <?php endif; ?>
                                    <source srcset="<?php echo $img2['url'] ?>" type="<?php echo $img2["mime_type"] ?>">
                                    <?php echo '<img class="img-fluid" alt="'. $img2['alt'] .'" title="'. $img2['title'] .'" src="'. $img2["url"] .'">'; ?>
                                </picture>
                            </a>
                            <div class="hover"></div>
                            <svg class="icon" width="40" height="40" viewBox="0 0 40 40" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.9734 8.75C13.8844 8.75 7.63828 12.2742 2.72109 19.3227C2.58074 19.5261 2.50382 19.7665 2.50007 20.0136C2.49631 20.2607 2.56589 20.5034 2.7 20.7109C6.47812 26.625 12.6406 31.25 19.9734 31.25C27.2266 31.25 33.5156 26.6109 37.3008 20.6836C37.4317 20.4801 37.5014 20.2432 37.5014 20.0012C37.5014 19.7592 37.4317 19.5223 37.3008 19.3187C33.507 13.4594 27.1719 8.75 19.9734 8.75Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M20 26.25C23.4518 26.25 26.25 23.4518 26.25 20C26.25 16.5482 23.4518 13.75 20 13.75C16.5482 13.75 13.75 16.5482 13.75 20C13.75 23.4518 16.5482 26.25 20 26.25Z"
                                    stroke="white" stroke-width="1.5" stroke-miterlimit="10" />
                            </svg>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
        <?php if ( have_rows('accordion_shutter_photos') ) : ?>
            <div id="accordion-shutters" class="tab-pane fade">
                <div class="photos">
                    <?php while( have_rows('accordion_shutter_photos') ) : the_row(); 
                        $img3 = get_sub_field('img');
                        $webp3 = get_sub_field('webp_img');
                        $credit3 = get_sub_field('credit');
                        ?>
                        <div class="img-wrapper">
                            <a href="<?php echo $img3['url']; ?>" data-toggle="lightbox"
                                data-footer="<?php if($credit3): echo $credit3; endif; ?>"
                                data-gallery="gallery-accordion">
                                <picture>
                                    <?php if ($webp3): ?>
                                    <source srcset="<?php echo $webp3['url'] ?>" type="image/webp">
                                    <?php endif; ?>
                                    <source srcset="<?php echo $img3['url'] ?>" type="<?php echo $img3["mime_type"] ?>">
                                    <?php echo '<img class="img-fluid" alt="'. $img3['alt'] .'" title="'. $img3['title'] .'" src="'. $img3["url"] .'">'; ?>
                                </picture>
                            </a>
                            <div class="hover"></div>
                            <svg class="icon" width="40" height="40" viewBox="0 0 40 40" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M19.9734 8.75C13.8844 8.75 7.63828 12.2742 2.72109 19.3227C2.58074 19.5261 2.50382 19.7665 2.50007 20.0136C2.49631 20.2607 2.56589 20.5034 2.7 20.7109C6.47812 26.625 12.6406 31.25 19.9734 31.25C27.2266 31.25 33.5156 26.6109 37.3008 20.6836C37.4317 20.4801 37.5014 20.2432 37.5014 20.0012C37.5014 19.7592 37.4317 19.5223 37.3008 19.3187C33.507 13.4594 27.1719 8.75 19.9734 8.75Z"
                                    stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M20 26.25C23.4518 26.25 26.25 23.4518 26.25 20C26.25 16.5482 23.4518 13.75 20 13.75C16.5482 13.75 13.75 16.5482 13.75 20C13.75 23.4518 16.5482 26.25 20 26.25Z"
                                    stroke="white" stroke-width="1.5" stroke-miterlimit="10" />
                            </svg>
                        </div>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>