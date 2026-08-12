<?php
/**
 *
 * Download Card - Block Template.
 *
 */
if (! empty($block['className'])) {
    $classes .= ' ' . $block['className'];
}
$id = substr($block['id'], -2);
if (! empty($block['anchor'])) {
    $id = $block['anchor'];
}
$user = wp_get_current_user();
$allowed_roles = array( 'editor', 'administrator', 'author', 'subscriber' );
$hide = get_field('hide_to_public');
$h = get_field('heading');
$showLogo = get_field('authority_logo');
$logo = get_field('upload_logo');
?>
<div class="download-section">
    <?php if ($hide == '0'): ?>
        <?php 
            if($h['tag'] == 'h2'):
                if($h['heading']): 
                echo '<h2 class="title text-left"><span class="marker"></span>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h2>';
                endif;
            elseif($h['tag'] == 'h3'):
                if($h['heading']): 
                echo '<h3 class="title text-left"><span class="marker"></span>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h3>';
                endif;
            endif;
        ?>
        <?php if ($showLogo == '1'): ?>
            <picture>
                <?php if ($logo['webp_img']): ?>
                <source srcset="<?php echo $logo['webp_img']['url'] ?>" type="image/webp">
                <?php endif; ?>
                <source srcset="<?php echo $logo['img']['url'] ?>" type="<?php echo $logo['img']["mime_type"] ?>">
                <?php echo '<img class="authority-logo" alt="'. $logo['img']['alt'] .'" title="'. $logo['img']['title'] .'" src="'. $logo['img']["url"] .'">'; ?>
            </picture>
        <?php endif; ?>
        <div class="download-cards">
            <?php if ( have_rows('download_cards') ) : ?>
                <?php while( have_rows('download_cards') ) : the_row(); 
                    $img = get_sub_field('img');
                    $webp = get_sub_field('webp_img');
                    $heading = get_sub_field('heading');
                    $desc = get_sub_field('description');
                    $url = get_sub_field('url');
                ?>
                    <div class="download-card">
                        <div class="img-wrapper">
                            <picture>
                                <?php if ($webp): ?>
                                <source srcset="<?php echo $webp['url'] ?>" type="image/webp">
                                <?php endif; ?>
                                <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img["mime_type"] ?>">
                                <?php echo '<img class="img-fluid" alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">'; ?>
                            </picture>
                        </div>
                        <?php if ($heading): ?>
                            <h5 <?php if(empty($desc)): echo 'class="no-sub"' ; endif; ?>><?php echo $heading; ?></h5>
                        <?php endif; ?>
                        <?php if ($desc): ?>
                            <p><?php echo $desc; ?></p>
                        <?php endif; ?>
                        <a class="txt-btn" href="<?php if($url): echo $url; endif; ?>" title="Click to Download" download>Download</a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
    <?php elseif ($hide == '1'): ?>
        <?php if (array_intersect( $allowed_roles, $user->roles )): ?>
            <?php 
            if($h['tag'] == 'h2'):
                if($h['heading']): 
                echo '<h2 class="title text-left"><span class="marker"></span>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h2>';
                endif;
            elseif($h['tag'] == 'h3'):
                if($h['heading']): 
                echo '<h3 class="title text-left"><span class="marker"></span>' . $h['heading'] . '<span>' . $h['red_heading'] . '</span>' . '</h3>';
                endif;
            endif;
        ?>
        <?php if ($showLogo == '1'): ?>
            <picture>
                <?php if ($logo['webp_img']): ?>
                <source srcset="<?php echo $logo['webp_img']['url'] ?>" type="image/webp">
                <?php endif; ?>
                <source srcset="<?php echo $logo['img']['url'] ?>" type="<?php echo $logo['img']["mime_type"] ?>">
                <?php echo '<img class="authority-logo" alt="'. $logo['img']['alt'] .'" title="'. $logo['img']['title'] .'" src="'. $logo['img']["url"] .'">'; ?>
            </picture>
        <?php endif; ?>
        <div class="download-cards">
            <?php if ( have_rows('download_cards') ) : ?>
                <?php while( have_rows('download_cards') ) : the_row(); 
                    $img = get_sub_field('img');
                    $webp = get_sub_field('webp_img');
                    $heading = get_sub_field('heading');
                    $desc = get_sub_field('description');
                    $url = get_sub_field('url');
                ?>
                    <div class="download-card">
                        <div class="img-wrapper">
                            <picture>
                                <?php if ($webp): ?>
                                <source srcset="<?php echo $webp['url'] ?>" type="image/webp">
                                <?php endif; ?>
                                <source srcset="<?php echo $img['url'] ?>" type="<?php echo $img["mime_type"] ?>">
                                <?php echo '<img class="img-fluid" alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">'; ?>
                            </picture>
                        </div>
                        <?php if ($heading): ?>
                            <h5 <?php if(empty($desc)): echo 'class="no-sub"' ; endif; ?>><?php echo $heading; ?></h5>
                        <?php endif; ?>
                        <?php if ($desc): ?>
                            <p><?php echo $desc; ?></p>
                        <?php endif; ?>
                        <a class="txt-btn" href="<?php if($url): echo $url; endif; ?>" title="Click to Download" download>Download</a>
                    </div>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>