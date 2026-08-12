<?php
/**
 *
 * Contact Section Block Template.
 *
 */
if( ! empty( $block['className'] ) ) {
    $classes .= ' ' . $block['className'];
  }
$id = substr($block['id'], -2);
if ( ! empty($block['anchor'] ) ) {
    $id = $block['anchor'];
}
$h = get_field('heading');
$subHead = get_field('sub-heading');
$img = get_field('img');
$imgWebp = get_field('webp_img');
?>

<section class="contact-section <?php echo esc_attr($classes); ?>" id="contact-section">
    <div class="container">
        <div class="img-wrapper">
            <?php if ($img): ?>
                <picture>
                <?php if ($imgWebp): ?>
                    <source srcset="<?php echo $imgWebp['url'] ?>" type="image/webp">
                <?php endif; ?>
                <source srcset="<?php echo $img["url"] ?>" type="<?php echo $img['mime_type'] ?>">
                <?php
                    echo '<img alt="'. $img['alt'] .'" title="'. $img['title'] .'" src="'. $img["url"] .'">';
                ?>
                </picture>
            <?php endif; ?>
        </div>
        <div class="contact-form">
            <?php 
                if($h['tag'] == 'h2'):
                    if($h['heading']): 
                    echo '<h2>' . $h['heading'] . '</h2>';
                    endif;
                elseif($h['tag'] == 'h3'):
                    if($h['heading']): 
                    echo '<h3>' . $h['heading'] . '</h3>';
                    endif;
                endif;
            ?>
            <?php if ($subHead): ?>
                <p class="sub-head"><?php echo $subHead; ?></p>
            <?php endif; ?>
            <form class="OTMForm">
                <div class="input-wrapper">
                    <label for="from">Full Name</label>
                    <input class="form-control" type="text" id="from" name="from" placeholder="John Smith" required>
                </div>
                <div class="input-wrapper">
                    <label for="sender">Email</label>
                    <input class="form-control" type="email" id="sender" name="sender" placeholder="smith@example.com" required>
                </div>
                <?php if (is_page(428)): //Homeowners ?>
                    <div class="row mx-0">
                        <div class="input-wrapper col-6 pr-1 pl-0">
                            <label for="phone">Phone</label>
                            <input class="form-control" type="text" id="phone" name="phone" placeholder="123-456-7890" required>
                        </div>
                        <div class="input-wrapper col-6 pr-0 pl-1">
                            <label for="city">City</label>
                            <input class="form-control" type="text" id="city" name="city" placeholder="Name of City" required>
                        </div>
                    </div>
                    <?php elseif(is_page(443) || is_page(445)): //Dealers & Specifiers?>
                        <div class="row mx-0">
                            <div class="input-wrapper col-6 pr-1 pl-0">
                                <label for="phone">Phone</label>
                                <input class="form-control" type="text" id="phone" name="phone" placeholder="123-456-7890" required>
                            </div>
                            <div class="input-wrapper col-6 pr-0 pl-1">
                                <label for="company">Company Name</label>
                                <input class="form-control" type="text" id="company" name="company" placeholder="Name of company" required>
                            </div>                            
                        </div>
                        <div class="row mx-0">
                            <div class="input-wrapper col-6 pr-1 pl-0">
                                <label for="city">City</label>
                                <input class="form-control" type="text" id="city" name="city" placeholder="Name of city" required>
                            </div>
                            <div class="input-wrapper col-6 pr-0 pl-1">
                                <label for="state">State</label>
                                <input class="form-control" type="text" id="state" name="state" placeholder="Name of state" required>
                            </div>
                        </div>
                    <?php else: ?>
                <div class="input-wrapper">
                    <label for="phone">Phone</label>
                    <input class="form-control" type="text" id="phone" name="phone" placeholder="123-456-7890" required>
                </div>
                <?php endif; ?>
                <div class="input-wrapper">
                    <label for="msg">Your Message</label>
                    <textarea class="form-control" id="msg" name="msg" placeholder="Ask away!"></textarea>
                    <input class="d-none" name="your-url" type="text" tabindex="-1" autocomplete="off" />
                    <button class="btn1" type="submit" title="Click to Submit" data-text="Submit message">Submit message</button>
                </div>
                <div class="alert alert-success mt-2 d-none" role="alert">
                    Your message has been successfully sent. Looking forward to speaking with you soon.
                </div>
                <div class="alert alert-danger mt-2 d-none" role="alert">
                    Your Message has not been sent. Please try again later.
                </div>
            </form>
            <div class="socials">
                <?php if ( have_rows('social_profiles') ) : ?>
                    <?php while( have_rows('social_profiles') ) : the_row(); 
                        $name = get_sub_field('name');
                        $url = get_sub_field('url');
                        ?>
                        <a href="<?php if($url): echo $url; endif ?>" class="social"
                            title="Visit us on <?php if($name): echo $name; endif ?>"
                            target="_blank"><?php if($name): echo $name; endif ?>
                        </a>
                    <?php endwhile;
                endif; ?>
            </div>
        </div>
    </div>
</section>