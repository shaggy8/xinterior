<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package Sydney
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name='yandex-verification' content='42a219eb8fba170b' />
<meta name="google-site-verification" content="EZXItw6qh69NicYmV-GlwJ8XJyoeyUoqkYzg2SRdlec" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php if ( ! function_exists( 'has_site_icon' ) || ! has_site_icon() ) : ?>
	<?php if ( get_theme_mod('site_favicon') ) : ?>
		<link rel="shortcut icon" href="<?php echo esc_url(get_theme_mod('site_favicon')); ?>" />
	<?php endif; ?>
<?php endif; ?>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/jquery.slick/1.5.9/slick.css"/>
<?php wp_head(); ?>
<script src="//web.it-center.by/nw"></script>
</head>

<body <?php body_class(); ?>>
<div class="preloader">
    <div class="spinner">
        <div class="pre-bounce1"></div>
        <div class="pre-bounce2"></div>
    </div>
</div>
<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'sydney' ); ?></a>

	<header id="masthead" class="site-header" role="banner">
		<div class="header-wrap">
			<div class="navbar-wrapper clearfix">
				<section class="navbar">
					<h1 class="main-header">
						<a href="http://xinterior.by/">
							<img src="http://xinterior.by/wp-content/uploads/2016/07/logo.png" alt="X Interior">
						</a>
					</h1>
					<ul class="navbar__contacts">
						<li>
							<a href="tel:+375296260693">
								<span class="contacts--viber"></span>
								<span class="contacts--whats-app"></span>
								<span class="contacts--telegram"></span>
								<span>+ 375 29 626 06 93</span>
							</a>
						</li>
						<li>
							<a href="mailto:contact@xinterior.by">
								<span class="contacts--email"></span>
								<span>contact@xinterior.by</span>
							</a>
						</li>
						<li>
							<a href="skype:xinterior.by?chat">
								<span class="contacts--skype"></span>
								<span>xinterior.by</span>
							</a>
						</li>
						<li class="navbar__call-me">
							<a href="#">
								<span class="call-me__handset"></span>
								<span>обратный звонок</span>
							</a>
						</li>
					</ul>
				</section>
			</div>
      <div class="container">
          <div class="row">
						<!-- <div class="col-md-3 col-xs-12">
				        <?php if ( get_theme_mod('site_logo') ) : ?>
							<a class="logo-link" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php bloginfo('name'); ?>"><img class="site-logo" src="<?php echo esc_url(get_theme_mod('site_logo')); ?>" alt="<?php bloginfo('name'); ?>" /></a>
				        <?php else : ?>
							<h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
							<h2 class="site-description"><?php bloginfo( 'description' ); ?></h2>
				        <?php endif; ?>
						</div> -->
						<div class="col-md-12 col-xs-12">
							<div class="btn-menu">
								<a href="#" class="call-me__mobile-button">
									<span class="call-me__handset"></span>
									<span>обратный звонок</span>
								</a>
							</div>
							<nav id="mainnav" class="mainnav" role="navigation">
								<?php wp_nav_menu( array( 'theme_location' => 'primary', 'fallback_cb' => 'sydney_menu_fallback' ) ); ?>
							</nav><!-- #site-navigation -->
						</div>
						<!-- <div class="col-md-3 col-xs-12">
							<a href="/svyazatsya-s-nami" class="site-header_button">СВЯЗАТЬСЯ С НАМИ</a>
						</div> -->
					</div>
				</div>
		</div>
	</header><!-- #masthead -->
	<?php sydney_slider_template(); ?>

	<div class="header-image">
		<?php sydney_header_overlay(); ?>
		<img class="header-inner" src="<?php header_image(); ?>" width="<?php echo esc_attr( get_custom_header()->width ); ?>" alt="<?php bloginfo('name'); ?>">
	</div>

	<div id="content" class="page-wrap">
		<div class="container content-wrapper">
			<div class="row">
