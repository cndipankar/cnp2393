<!DOCTYPE html>
<html class="no-js" <?php language_attributes(); ?>>
<head>
  <meta charset="utf-8">
  <title><?php wp_title(); ?></title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="theme-color" content="#333130" media="(prefers-color-scheme: dark)">
  <meta name="theme-color" content="#FFFFFF" media="(prefers-color-scheme: light)">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;1,200;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet">
  <?php wp_head(); ?>
  <?php otm_page_header_scripts(); ?>
  <?php otm_page_faq_schema(); ?>

  <!-- Meta Pixel Code -->
  <script>
  !function(f,b,e,v,n,t,s)
  {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
  n.callMethod.apply(n,arguments):n.queue.push(arguments)};
  if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
  n.queue=[];t=b.createElement(e);t.async=!0;
  t.src=v;s=b.getElementsByTagName(e)[0];
  s.parentNode.insertBefore(t,s)}(window, document,'script',
  'https://connect.facebook.net/en_US/fbevents.js');
  fbq('init', '507249801539371');
  fbq('track', 'PageView');
  </script>
  <noscript><img height="1" width="1" style="display:none"
  src="https://www.facebook.com/tr?id=507249801539371&ev=PageView&noscript=1"
  /></noscript>
  <!-- End Meta Pixel Code -->

</head>

<body <?php body_class(); ?>>

<?php otm_custom_js_code_body(); 

?>

<header id="header">
  <div class="container">
    <div class="top-mobile-menu">
      <a href="#menu" class="sh-ico-menu menu-link"><b></b><span class="icon-nav">+</span></a>
      <a href="" class="site-nav-container-screen menu-link">&nbsp;</a>
    </div>
    <div class="site-nav-container">
      <nav class="site-nav" role="navigation">
        <ul>
          <li class="menu-item-has-children"><a href=""><?php _e('Visitor Type', 'otmtheme'); ?></a>
            <ul class="sn-level-2">
              <li><a href="<?php echo esc_url(home_url('homeowner')); ?>"><?php _e('Homeowner', 'otmtheme'); ?></a></li>
              <li><a href="<?php echo esc_url(home_url('dealer')); ?>"><?php _e('Dealer', 'otmtheme'); ?></a></li>
              <li><a href="<?php echo esc_url(home_url('specifier')); ?>"><?php _e('Specifier', 'otmtheme'); ?></a></li>
            </ul>
          </li>
          <?php 
          if (is_user_logged_in()){ 
            $user = wp_get_current_user(); ?>
            <li><a href="<?php echo esc_url(home_url('downloads')); ?>" ><?php _e('Dashboard', 'otmtheme'); ?> <i class="fa-solid fa-gauge-simple-high"></i></a></li>
            <li><a href="<?php echo esc_url(home_url('wp-login.php?action=logout')); ?>" ><?php _e('Logout', 'otmtheme'); ?> <i class="fa-solid fa-right-from-bracket"></i></a></li>
            <!-- <p>Hi <?php //echo $user->display_name; ?>!</a> -->
            <?php 
          }else{  ?>          
            <li><a href="<?php echo wp_login_url( get_permalink() ); ?>" ><?php _e('Dealer Login', 'otmtheme'); ?> <i class="fa-solid fa-user"></i></a></li>
            <li><a href="<?php echo site_url('/wp-login.php?action=register'); ?>"><?php _e('Dealer Registration', 'otmtheme'); ?> <i class="fa-solid fa-circle-plus"></i></a></li>
          <?php } ?>
        </ul>
      </nav>
    </div>

    <div class="row align-items-center justify-content-between top_navigation_bar">
      <div class="col-5 col-xs-6 col-sm-6 col-lg-6 col-xl-5 top_notification"><!--This is demo notification text this is demo notification text.--></div>
      <div class="col-7 col-xs-6 col-sm-6 col-lg-6 col-xl-7 top_nav">
        <ul>
          <li> <a href=""><?php _e('Visitor Type', 'otmtheme'); ?> <i class="fa-solid fa-angle-down"></i></a>
            <ul>
              <li><a href="<?php echo esc_url(home_url('homeowner')); ?>"><?php _e('Homeowner', 'otmtheme'); ?></a></li>
              <li><a href="<?php echo esc_url(home_url('dealer')); ?>"><?php _e('Dealer', 'otmtheme'); ?></a></li>
              <li><a href="<?php echo esc_url(home_url('specifier')); ?>"><?php _e('Specifier', 'otmtheme'); ?></a></li>
            </ul>
          </li>          
          <?php 
          if (is_user_logged_in()){ 
            $user = wp_get_current_user(); ?>
            <li><a href="<?php echo esc_url(home_url('downloads')); ?>" ><?php _e('Dashboard', 'otmtheme'); ?> <i class="fa-solid fa-gauge-simple-high"></i></a></li>
            <li><a href="<?php echo esc_url(home_url('wp-login.php?action=logout')); ?>" ><?php _e('Logout', 'otmtheme'); ?> <i class="fa-solid fa-right-from-bracket"></i></a></li>
            <!-- <p>Hi <?php //echo $user->display_name; ?>!</a> -->
            <?php 
          }else{  ?>          
            <li><a href="<?php echo wp_login_url( get_permalink() ); ?>" ><?php _e('Dealer Login', 'otmtheme'); ?> <i class="fa-solid fa-user"></i></a> </li>
            <li><a href="<?php echo site_url('/wp-login.php?action=register'); ?>"><?php _e('Dealer Registration', 'otmtheme'); ?> <i class="fa-solid fa-circle-plus"></i></a> </li>
          <?php } ?>
        </ul>
      </div>
    </div>
    <div class="row align-items-center justify-content-between">
      <div class="col-7 col-xs-5 col-sm-4 col-lg-2 col-xl-3 logo-wrapper">
        <?php if (wp_is_mobile()): otm_site_logo('mobile'); else : otm_site_logo('primary'); endif; ?>
      </div>
      <div class="col-5 col-xs-7 col-sm-6 col-lg-10 col-xl-9 nav-wrapper">
        <?php
          wp_nav_menu([
              'theme_location' => 'main-menu',
              'container' => false,
              'menu_class' => '',
              'items_wrap' => '<ul id="%1$s" class="navbar-nav w-100 justify-content-end %2$s">%3$s</ul>',
              'depth' => 3,
              'walker' => new otm_theme_walker_nav_menu(),
          ]);
        ?>
      </div>
    </div>
  </div>
</header>
<div class="header-spacer"></div>