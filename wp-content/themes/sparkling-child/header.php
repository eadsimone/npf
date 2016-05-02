<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package sparkling
 */
?>
<!doctype html>
<!--[if !IE]>
<html class="no-js non-ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]>
<html class="no-js ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>
<html class="no-js ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>
<html class="no-js ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<?php if (isset($_SERVER['HTTP_USER_AGENT']) && (strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)) header('X-UA-Compatible: IE=edge,chrome=1'); ?>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css" />
	<link rel="author" href="https://plus.google.com/u/0/114616192538585348803/posts" />

	<script src="<?php bloginfo('template_directory'); ?>/js/jquery-1.10.2.min.js"></script>
	<script src="<?php bloginfo('template_directory'); ?>/js/jquery.slides.min.js"></script>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>

<!-- Google Tag Manager -->
<noscript><iframe src="//www.googletagmanager.com/ns.html?id=GTM-WQNSNR"
				  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
		new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
		j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
		'//www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
	})(window,document,'script','dataLayer','GTM-WQNSNR');</script>
<!-- End Google Tag Manager -->

<a class="sr-only sr-only-focusable" href="#content">Skip to main content</a>
<div id="page" class="hfeed site">

	<header id="masthead" class="site-header" role="banner">
		<nav class="navbar navbar-white">
			<div class="container">
				<div class="row">

					<div class="header-info-top-mobile">
						<div class="contact-no">AUSTRALIA: <span class="number">1300 882 318</span>
						</div>
						<div class="contact-no">USA: <span>(877) 959-4445</span>
						</div>
					</div>

					<?php if( get_header_image() != '' ) : ?>




						<div id="logo">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>"><img src="<?php header_image(); ?>"  height="<?php echo get_custom_header()->height; ?>" width="<?php echo get_custom_header()->width; ?>" alt="<?php bloginfo( 'name' ); ?>"/></a>
						</div><!-- end of #logo -->

					<?php endif; // header image was removed ?>

					<?php if( !get_header_image() ) : ?>

						<div id="logo">
							<?php echo is_home() ?  '<h1 class="site-name">' : '<p class="site-name">'; ?>
							<a class="navbar-brand" href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
							<?php echo is_home() ?  '</h1>' : '</p>'; ?>
						</div><!-- end of #logo -->

					<?php endif; // header image was removed (again) ?>

					<div class="header-get-started">
						<a href="http://pricingplan.npfulfilment.com/">
							<span>GET STARTED</span>
						</a>
					</div>

					<div class="header-info-top">
						<div class="contact-no">USA: <span>(877) 959-4445</span></div>
						<div class="contact-no img-phone">AUSTRALIA: <span class="number">1300 882 318</span></div>
					</div>
				</div>
			</div>
		</nav>
		<nav class="navbar navbar-default <?php if( of_get_option( 'sticky_header' ) ) echo 'navbar-fixed-top'; ?>" role="navigation">
			<div class="container">
				<div class="row">
					<div class="site-navigation-inner col-sm-12">
						<div class="navbar-header">
							<button type="button" class="btn navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
								<span class="sr-only">Toggle navigation</span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
								<span class="icon-bar"></span>
							</button>

						</div>
						<?php sparkling_header_menu(); // main navigation ?>
					</div>
				</div>
			</div>
		</nav><!-- .site-navigation -->
	</header><!-- #masthead -->

	<div id="content" class="site-content">
		<div class="title-top-section">
			<div  class="wrap">
			<?php echo  get_the_title(); ?>
				</div>
		</div>
		<!--<div class="top-section">
			<?php /*sparkling_featured_slider(); */?>
			<a class="vidbtn2" href="#"><img src="<?php /*bloginfo('template_directory'); */?>/images/play-btn.png" /></a>
			<?php /*sparkling_call_for_action(); */?>
		</div>-->

		<?php if (($_SERVER['REQUEST_URI'])=="/"): ?>
			<div class="top-section logoslider">
				<?php echo do_shortcode( '[gs_logo]' ); ?>
			</div>
		<?php endif; ?>


		<div class="start-today-mobile">
			<a href="http://pricingplan.npfulfilment.com/">
				<span>START TODAY!</span>
			</a>
		</div>

		<!--<div class="container main-content-area">
			<?php /*$layout_class = get_layout_class(); */?>
			<div class="row <?php /*echo $layout_class; */?>">
				<div class="main-content-inner <?php /*echo sparkling_main_content_bootstrap_classes(); */?>">
				-->