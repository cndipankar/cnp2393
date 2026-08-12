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

<?php otm_custom_js_code_body(); ?>

<header id="header">
  <div class="container">
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