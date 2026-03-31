<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie8"> <![endif]-->
<!--[if gt IE 8]>
<html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 9]> <!-->
<html class="no-js"> <!--><![endif]-->
<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="format-detection" content="telephone=no"/>
  <meta name="facebook-domain-verification" content="sl59pmfm8nmwrpelawlly4dhrk4rwp"/>
  <link rel='icon' href='<?php echo get_stylesheet_directory_uri(); ?>/images/favicon.png' type='image/x-icon'
  / >
  <?php wp_head(); ?>
  <?php if ($_SERVER['SERVER_NAME'] == 'museumlab.org'): ?>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-19246493-2"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag() {
        dataLayer.push(arguments);
      }
      gtag('js', new Date());
      gtag('set', 'developer_id.dZjQwMz', true);
      gtag('config', 'UA-19246493-2');
    </script>
  <?php endif; ?>

</head>

<body <?php body_class(); ?>>

<!--[if lt IE 9]>
<div class="browser-notice">
  <p>You are using an outdated browser. Please <a href="http://browsehappy.com/" target="_blank">update your browser</a>
    to improve your experience. </p>
</div>
<![endif]-->

<div class="site-wrapper">

  <header class="header">

    <div class="container">

      <nav class="navbar " id="site-navigation">
        <div class="header__logo">
         
          <span class="site-logo site-logo--header">
          
          <a href="<?php bloginfo('url') ?>" class="navbar-brand ">
            <img
              src="<?php bloginfo('template_url'); ?>/images/logo.png" class="navbar__logo"
              alt="<?php bloginfo('name') ?>" width="250" height="83">
          </a>
         </span>
        </div>

        <div class="header__menu ">
          <div class="d-flex">
            <a href="https://shop.pittsburghkids.org/e-commerce/" target="_blank" class="btn get-tickets">get
              tickets</a>
            <input type="checkbox" id="openmenu" class="hamburger-checkbox">
            <div class="hamburger-icon">
              <label for="openmenu" class="hamburger-label" id="hamburger-label">
                <span></span>
                <span></span>
                <span></span>
              </label>
            </div>
          </div>


        </div>

      </nav>
    </div> <!-- container -->
    <div class="menu-pane">
      <?php
      $args = array(
        'theme_location' => 'main_nav',
        'menu' => 'main_nav',
        'container' => '',
        'menu_class' => 'navbar-nav',
        'echo' => true,
        'items_wrap' => '<ul id = "%1$s" class = "%2$s">%3$s</ul>',
      );
      wp_nav_menu($args);
      ?>
    </div>
  </header>

  <div class="content-wrapper">
