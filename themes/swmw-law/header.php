<?php
/**
 * The template for displaying the header.
 *
 * @package SWMW_Law
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<?php wp_body_open(); ?>

		<a href="#main" class="skip-link sr-only"><?php esc_html_e( 'Skip to main content', 'swmw-law' ); ?></a>

		<div class="site">
			<header class="site-header" role="banner">
				<div class="inner container-lg">
					<div class="site-branding">
						<?php if ( has_custom_logo() ) : ?>
							<div class="site-logo">
								<?php the_custom_logo(); ?>
							</div>
						<?php endif; ?>
					</div>
          <div class="nav-wrapper">
            <button class="nav-toggle" aria-controls="main-navigation" aria-expanded="false">
              <span class="screen-reader-text"><?php esc_html_e( 'Menu', 'swmw-law' ); ?></span>
              <span></span>
            </button>
            <div class="main-nav-wrapper">
              <nav class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Main Navigation', 'swmw-law' ); ?>">
                <?php wp_nav_menu( array(
                  'theme_location' => 'primary',
                  'menu_class'     => 'main-nav-list',
                  'container'      => false,
                  'menu_item_class' => 'main-nav-item',
                  'link_class' => 'main-nav-link',
                  'walker'         => new SWMW_Law\SWMW_Law_Mega_Menu_Walker(),
                ) ); ?>
              </nav>
            </div>
            <div class="utility-nav-wrapper">
              <nav class="utility-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Utility Navigation', 'swmw-law' ); ?>">
                <?php wp_nav_menu( array(
                  'theme_location' => 'utility_header',
                  'menu_class'     => 'utility-nav-list',
                  'menu_item_class' => 'utility-nav-item',
                  'link_class' => 'utility-nav-link'
                ) ); ?>
              </nav>
              <?php
              $phone_number = get_theme_mod( 'swmw_law_phone' );
              if ( ! empty( $phone_number ) ) :
              ?>
                <div class="header-phone">
                  <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/phone-icon.svg' ); ?>" alt="<?php esc_attr_e( 'Phone Icon', 'swmw-law' ); ?>" class="phone-icon" />
                  <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]', '', $phone_number ) ); ?>"><?php echo esc_html( $phone_number ); ?></a>
                </div>
              <?php endif; ?>
            </div>
          </div>
				</div>
			</header>

			<div id="mobile-navigation-panel" class="mobile-navigation-panel" aria-hidden="true" tabindex="-1">
				<div class="mobile-navigation-panel-body">
					<nav class="mobile-primary-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Primary Navigation', 'swmw-law' ); ?>">
						<?php
						wp_nav_menu( array(
							'theme_location' => 'primary', // Using the same primary menu for mobile
							'menu_class'     => 'mobile-primary-nav-list',
							'container'      => false, // No extra div wrapper around the ul
							// 'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						) );
						?>
					</nav>
					<ul class="mobile-bottom-links">
						<?php
						$phone_number = get_theme_mod( 'swmw_law_phone' );
						if ( ! empty( $phone_number ) ) :
						?>
							<li class="button mobile-phone-button">
								<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_number ) ); ?>">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/phone-icon.svg' ); ?>" alt="<?php esc_attr_e( 'Phone Icon', 'swmw-law' ); ?>" class="phone-icon" />
									<span><?php echo esc_html( $phone_number ); ?></span>
								</a>
							</li>
						<?php endif; ?>
					</ul>
					<?php
					// If you also want the utility menu in the mobile panel:
					/*
					if ( has_nav_menu( 'utility_header') ) : ?>
						<nav class="mobile-utility-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Utility Navigation', 'swmw-law' ); ?>">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'utility_header',
								'menu_class'     => 'mobile-utility-nav-list',
								'container'      => false,
							) );
							?>
						</nav>
					<?php endif; */
					?>
				</div>
			</div>

			<main id="main" class="site-main" role="main" tabindex="-1">
				<?php
				// Only show the page header on singular pages (not archives, search, etc.) 
				// AND not on single attorney pages (they have their own hero)
				// AND not on single blog posts (they have their own hero/title structure).
				if ( is_singular() && ! is_singular( 'attorney' ) && ! is_single() && apply_filters( 'swmw_law_show_page_header', true ) ) {
					get_template_part( 'template-parts/header' );
				}
				?>
