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
</head>

<body <?php body_class(); ?>>

<?php otm_custom_js_code_body(); 

?>

<header id="header">
  <div class="container">
    <div class="row align-items-center justify-content-between top_navigation_bar">
      <div class="col-5 col-xs-7 col-sm-6 col-lg-10 col-xl-9 top_notification">This is demo notification text this is demo notification text.</div>
      <div class="col-7 col-xs-5 col-sm-4 col-lg-2 col-xl-3 top_nav">
        <?php 
          if (is_user_logged_in()){ 
            $user = wp_get_current_user(); ?>
            <a href="<?php echo esc_url(home_url('downloads')); ?>" >Dashboard</a>
            <a href="<?php echo esc_url(home_url('wp-login.php?action=logout')); ?>" >Logout</a>
            <!-- <p>Hi <?php //echo $user->display_name; ?>!</a> -->
            <?php 
          }else{  ?>          
            <a href="<?php echo wp_login_url( get_permalink() ); ?>" >Dealer Login</a>
            <a href="<?php echo site_url('/wp-login.php?action=register'); ?>">Dealer Register</a>
          <?php } ?>
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