<footer>
  <div class="footer-top">
    <div class="container">
      <div class="col-one">
        <img class="logo" src="<?php echo get_template_directory_uri(); ?>/assets/img/logo-footer.svg" alt="Croci North America Logo" title="Croci North America">
        <h5>You're Covered With Croci</h5>
      </div>
      <div class="col-two">
        <p class="footer-title">Explore</p>
        <nav class="footer-nav">
          <?php
              wp_nav_menu([
                  'theme_location' => 'footer-menu',
                  'container' => false,
                  'menu_class' => '',
                  'items_wrap' => '<ul id="%1$s" class=" %2$s">%3$s</ul>',
                  'depth' => 1,
                  'walker' => new otm_theme_walker_nav_menu(),
              ]);
            ?>
        </nav>
      </div>
      <div class="col-three">
        <p class="footer-title">Croci North America</p>
        <a class="address" href="<?php echo otm_address_link(); ?>" target="_blank" title="Get Directions">
          <div class="address-line"><?php echo otm_address_line1(); ?></div>
          <div class="address-line"><?php echo otm_address_line2(); ?></div>
        </a>
        <p class="footer-title">Email</p>
        <a class="email" href="mailto:<?php echo otm_email_address(); ?>" title="Email Us"><?php echo otm_email_address(); ?></a>
      </div>
      <div class="col-four">
      <p class="footer-title">Phone</p>
        <a class="phone d-block" href="tel:<?php echo str_replace([' ', '(', ')', '-'], "", otm_phone_number()); ?>" title="Call Us"><?php echo otm_phone_number(); ?></a>
        <a class="phone d-block" href="tel:<?php echo str_replace([' ', '(', ')', '-'], "", otm_phone_number_2()); ?>" title="Call Us"><?php echo otm_phone_number_2(); ?></a>
        <p class="footer-title">Fax</p>
        <a class="phone d-block" href="tel:<?php echo str_replace([' ', '(', ')', '-'], "", otm_fax_number()); ?>" title="Call Us"><?php echo otm_fax_number(); ?></a>
      </div>
      <div class="col-five">
      <p class="footer-title">Follow Croci</p>
        <div class="social-profiles">
          <?php get_template_part('includes/social-profiles')?>
        </div>
        <div class="scroll">
          <a role="button" onclick="scrolltoTop()" title="Back to Top">
            <svg width="12" height="13" viewBox="0 0 12 13" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M1.5 6.125L6 1.625L10.5 6.125M6 2.25V11.375" stroke="#B4AFAB" stroke-width="1.5"
                stroke-miterlimit="10" stroke-linecap="square" />
            </svg> Back to Top</a>
        </div>
      </div>
      <div class="col-six"></div>
    </div>
  </div>
  <div class="footer-bottom">
    <div class="container">
      <?php if (is_active_sidebar('footer-widget-area')): ?>
      <?php dynamic_sidebar('footer-widget-area'); ?>
      <hr>
      <?php endif; ?>
      <div class="row">
        <div
          class="col-12 col-md-6 d-flex align-items-center">
          <p class="copyright"><?php echo otm_copyright_text(); ?></p>
        </div>
        <div class="col-12 col-md-6 text-md-right">
          <a href="https://www.onthemap.com/" target="_blank" title="On The Map Marketing">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/otm/otm-logo-black.svg" width="150px"
              alt="Logo of On The Map Inc." title="On The Map Inc."></a>
        </div>
      </div>
    </div>
  </div>
</footer>
<?php wp_footer(); ?>
<?php otm_page_footer_scripts(); ?>
</body>
</html>