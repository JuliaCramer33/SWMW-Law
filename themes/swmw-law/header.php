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
                  <a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_number ) ); ?>">
									<svg class="phone-icon" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true">
										<path d="M0 0h24v24H0V0z" fill="none"/>
										<path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
									</svg>
									<span><?php echo esc_html( $phone_number ); ?></span>
								</a>
                </div>
              <?php endif; ?>
            </div>
          </div>
				</div>
			</header>

			<div class="mobile-menu-overlay" aria-hidden="true"></div>

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
					
					<?php if ( has_nav_menu( 'utility_header' ) ) : ?>
						<nav class="mobile-utility-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Mobile Utility Navigation', 'swmw-law' ); ?>">
							<?php
							wp_nav_menu( array(
								'theme_location' => 'utility_header',
								'menu_class'     => 'mobile-utility-nav-list',
								'container'      => false,
							) );
							?>
						</nav>
					<?php endif; ?>
					
					<ul class="mobile-bottom-links">
						<?php
						// Add CTA button (you can customize this)
						$cta_text = get_theme_mod( 'swmw_law_cta_text', 'Free Consultation' );
						$cta_link = get_theme_mod( 'swmw_law_cta_link', '/contact' );
						if ( ! empty( $cta_text ) ) :
						?>
							<li class="button mobile-cta-button">
								<a href="<?php echo esc_url( $cta_link ); ?>">
									<span><?php echo esc_html( $cta_text ); ?></span>
								</a>
							</li>
						<?php endif; ?>
						
						<?php
						$phone_number = get_theme_mod( 'swmw_law_phone' );
						if ( ! empty( $phone_number ) ) :
						?>
							<li class="button mobile-phone-button">
								<a href="tel:<?php echo esc_attr( preg_replace( '/[^0-9+]/', '', $phone_number ) ); ?>">
									<svg class="phone-icon" viewBox="0 0 24 24" fill="currentColor" width="16" height="16" aria-hidden="true">
										<path d="M0 0h24v24H0V0z" fill="none"/>
										<path d="M6.62 10.79c1.44 2.83 3.76 5.14 6.59 6.59l2.2-2.2c.27-.27.67-.36 1.02-.24 1.12.37 2.33.57 3.57.57.55 0 1 .45 1 1V20c0 .55-.45 1-1 1-9.39 0-17-7.61-17-17 0-.55.45-1 1-1h3.5c.55 0 1 .45 1 1 0 1.25.2 2.45.57 3.57.11.35.03.74-.25 1.02l-2.2 2.2z"/>
									</svg>
									<span><?php echo esc_html( $phone_number ); ?></span>
								</a>
							</li>
						<?php endif; ?>
					</ul>
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
