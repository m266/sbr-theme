<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Twenty_Sixteen
 * @since Twenty Sixteen 1.0
 */

?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site">
	<div class="site-inner">
		<!-- <a class="skip-link screen-reader-text" href="#content"><?php /* _e( 'Skip to content', 'twentysixteen' ); */ ?></a> -->

		<!-- Mobile Header Wrapper -->
		<div class="mobile-header-wrapper">
			<div class="site-branding">
				<?php twentysixteen_the_custom_logo(); ?>
				<?php if ( is_front_page() && is_home() ) : ?>
	                <!-- SBR-Titel und -Untertitel in einer Zeile -->
					<h1 class="site-title"><a title="Zur Startseite" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
				<?php else : ?>
					<p class="site-title"><a title="Zur Startseite" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
	            <?php endif;

				$description = get_bloginfo( 'description', 'display' );
				if ( $description || is_customize_preview() ) : ?>
					<p class="site-description"><a title="Zur Startseite" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo $description; ?></a></p>
	            <?php endif; ?>
			</div><!-- .site-branding -->


			<!-- BeW-Logo mobile-->
	        <div class="bew-logo-mobile">
	            <a href="https://www.betreuungswerk.de/" title="Betreuungswerk Post Postbank Telekom" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/bew-logo.png" alt="Betreuungswerk Post Postbank Telekom" width="238" height="82" /></a>
	        </div><!-- .bew-logo -->
        </div> <!-- .mobile-header-wrapper -->

		<div class="header-wrapper">
		<header id="masthead" class="site-header" role="banner" style="background-image: url(<?php echo get_stylesheet_directory_uri() . '/images/header-overlay.png'; ?>), url(
			<?php if (has_header_image()) { /* Wenn ein eigenes Headerimage aktiviert ist, dann wird dieses benutzt. Wenn nicht, dann wird auf das default-image zurueckgegriffen */
					header_image();
				}
				else {
					echo get_stylesheet_directory_uri() . '/images/sbr-header-image.jpg'; 
				}
			?>);">
                    <!-- BeW-Logo -->
                    <div class="bew-logo">
                    	<a href="https://www.betreuungswerk.de/" title="Betreuungswerk Post Postbank Telekom" target="_blank"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/bew-logo.png" alt="Betreuungswerk Post Postbank Telekom" width="238" height="82" /></a>
                    </div><!-- .bew-logo -->
			<div class="site-header-main">
				<div class="site-branding">
					<?php twentysixteen_the_custom_logo(); ?>
					<?php if ( is_front_page() && is_home() ) : ?>
                        <!-- SBR-Titel und -Untertitel in einer Zeile -->
						<h1 class="site-title"><a title="Zur Startseite" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
					<?php else : ?>
						<p class="site-title"><a title="Zur Startseite" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                    <?php endif;

					$description = get_bloginfo( 'description', 'display' );
					if ( $description || is_customize_preview() ) : ?>
						<p class="site-description"><a title="Zur Startseite" href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo $description; ?></a></p>
                    <?php endif; ?>
				</div><!-- .site-branding -->
				<?php if ( has_nav_menu( 'primary' ) || has_nav_menu( 'social' ) ) : ?>
					<button id="menu-toggle" class="menu-toggle"><?php _e( 'Menu', 'twentysixteen' ); ?></button>

					<div id="site-header-menu" class="site-header-menu">
						<?php if ( has_nav_menu( 'primary' ) ) : ?>
							<nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Primary Menu', 'twentysixteen' ); ?>">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'primary',
										'menu_class'     => 'primary-menu',
									 ) );
								?>
							</nav><!-- .main-navigation -->
						<?php endif; ?>

						<?php if ( has_nav_menu( 'social' ) ) : ?>
							<nav id="social-navigation" class="social-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Social Links Menu', 'twentysixteen' ); ?>">
								<?php
									wp_nav_menu( array(
										'theme_location' => 'social',
										'menu_class'     => 'social-links-menu',
										'depth'          => 1,
										'link_before'    => '<span class="screen-reader-text">',
										'link_after'     => '</span>',
									) );
								?>
							</nav><!-- .social-navigation -->
						<?php endif; ?>
					</div><!-- .site-header-menu -->
				<?php endif; ?>
			</div><!-- .site-header-main -->
		</header><!-- .site-header -->
		</div> <!-- .header-rapper -->

		<div id="content" class="site-content">

			<div class="mobile-button-zur-navi-wrapper">
				<a href="#secondary" class="mobile-button-zur-navi"><i class="fa fa-bars" aria-hidden="true"></i> Menue</a>
			</div>