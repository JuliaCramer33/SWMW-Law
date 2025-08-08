<?php
/**
 * The template for displaying the footer
 *
 * @package SWMW_Law
 */

?>

		</main><!-- #main -->

		<?php
		// Conditionally display the accreditations bar before the footer.
		$display_bar = false;
		$logos       = get_field( 'accreditations_logos', 'option' );

        if ( ! empty( $logos ) ) {
            if ( is_singular( 'post' ) ) { // Single blog posts default to ON unless explicitly disabled.
                $show_setting = get_field( 'show_accreditations_bar' );
                $display_bar  = ( false !== $show_setting );
            } elseif ( is_home() ) { // Blog posts index.
				$display_bar = get_field( 'show_on_posts_page', 'option' );
			} elseif ( is_post_type_archive( 'attorney' ) ) { // Attorney archive.
				$display_bar = get_field( 'show_on_attorney_archive', 'option' );
			} elseif ( is_post_type_archive( 'swmw_result' ) ) { // Results archive.
				$display_bar = get_field( 'show_on_results_archive', 'option' );
			} elseif ( is_singular() || is_front_page() ) { // Any single post/page or static front page.
				$show_setting = get_field( 'show_accreditations_bar' );
				// 'allow_null' is on, so null means default behavior. Default is ON.
				if ( null === $show_setting || true === $show_setting ) {
					$display_bar = true;
				}
			}
		}

		if ( $display_bar ) {
			get_template_part( 'template-parts/accreditations-bar' );
		}
		?>

		<footer id="colophon" class="site-footer" role="contentinfo">
			<div class="container-lg">
				<div class="footer-main">
					<div class="footer-column footer-brand">
						<?php
						if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
							the_custom_logo();
						} elseif ( get_bloginfo( 'name' ) ) {
							echo '<h1 class="site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_html( get_bloginfo( 'name' ) ) . '</a></h1>';
							echo '<p class="site-description">' . esc_html( get_bloginfo( 'description', 'display' ) ) . '</p>';
						}
						?>
						<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/branded-separator.svg' ); ?>" alt="" class="footer-branded-separator" style="margin-top: 1rem; margin-bottom: 1rem;" />
						<div class="footer-tagline">
							<?php
							$footer_tagline_content = get_field( 'footer_display_tagline', 'option' );

							if ( $footer_tagline_content ) {
								echo wp_kses_post( $footer_tagline_content );
							}
							// Fallback can be added here if needed, similar to before
							?>
						</div>
					</div>

					<div class="footer-link-wrapper">
						<div class="footer-column footer-offices">
							<h2 class="widget-title">
								<span class="widget-title-text"><?php esc_html_e( 'Our Offices', 'swmw-law' ); ?></span>
								<span class="widget-title-line"></span>
							</h2>
							<div class="office-locations d-flex flex-wrap row-gutter-2">
								<?php
								if ( have_rows( 'footer_offices', 'option' ) ) :
									while ( have_rows( 'footer_offices', 'option' ) ) : the_row();
										$office_name     = get_sub_field( 'office_name' );
										$office_address  = get_sub_field( 'office_address' );
										$office_map_link = get_sub_field( 'office_map_link' );
										?>
										<div class="office-location col-6">
											<?php if ( $office_name ) : ?>
												<h3><?php echo esc_html( $office_name ); ?></h3>
											<?php endif; ?>
											<?php if ( $office_address ) : ?>
												<p><?php echo wp_kses_post( $office_address ); ?></p>
											<?php endif; ?>
											<?php 
											if ( $office_map_link && !empty( $office_map_link['url'] ) ) :
												$link_url   = $office_map_link['url'];
												$link_title = !empty( $office_map_link['title'] ) ? $office_map_link['title'] : __( 'Map & Directions', 'swmw-law' );
												$link_target = !empty( $office_map_link['target'] ) ? $office_map_link['target'] : '';
												?>
												<a href="<?php echo esc_url( $link_url ); ?>" class="map-directions-link"<?php if ( ! empty( $link_target ) ) { echo ' target="' . esc_attr( $link_target ) . '"'; } ?>><?php echo esc_html( $link_title ); ?></a>
											<?php endif; ?>
										</div>
										<?php 
									endwhile;
								else :
									// No rows found
								endif;
								?>
							</div>
						</div>

						<div class="footer-column footer-links">
							<h2 class="widget-title">
								<span class="widget-title-text"><?php esc_html_e( 'Helpful Links', 'swmw-law' ); ?></span>
								<span class="widget-title-line"></span>
							</h2>
							<?php if ( has_nav_menu( 'footer' ) ) : ?>
								<nav class="footer-navigation" aria-label="<?php esc_attr_e( 'Footer Menu', 'swmw-law' ); ?>">
									<?php
									wp_nav_menu(
										array(
											'theme_location' => 'footer',
											'menu_class'     => 'footer-nav-list',
											'depth'          => 1, // Assuming flat list based on comp
										)
									);
									?>
								</nav>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="footer-bottom">
				<div class="container-lg">
					<div class="footer-bottom-inner">
						<div class="footer-copyright">
							<p class="footer-disclaimer">
								<?php
								$disclaimer_text = get_theme_mod(
									'swmw_law_footer_disclaimer_text',
									__( 'The choice of a lawyer is an important decision and should not be based solely upon advertisements. Results obtained depend upon the facts of each case. Past results afford no guarantee of future results or similar outcomes. Every case is different and must be judged on its own merits. The information on this website is for general information purposes only. Nothing on this site should be taken as legal advice for any individual case or situation. This information is not intended to create, and receipt or viewing does not constitute, an attorney-client relationship.', 'swmw-law' )
								);
								echo wp_kses_post( $disclaimer_text );
								?>
							</p>
              <p>
								<?php
								$copyright_text = get_theme_mod(
									'swmw_law_footer_copyright_text',
									sprintf( 
										__( '&copy; [current_year] All Rights Reserved. %1$s | %2$s', 'swmw-law' ),
										'<a href="' . esc_url( home_url( '/site-map/' ) ) . '">' . __( 'Site Map', 'swmw-law' ) . '</a>',
										'<a href="' . esc_url( home_url( '/privacy-policy/' ) ) . '">' . __( 'Privacy Policy', 'swmw-law' ) . '</a>'
									)
								);
								$copyright_text = str_replace( '[current_year]', date_i18n( 'Y' ), $copyright_text );
								echo wp_kses_post( $copyright_text );
								?>
							</p>
						</div>

						<div class="footer-social">
							<?php // Placeholder for social media icons ?>
							<ul class="social-media-list">
								<li><a href="#" aria-label="<?php esc_attr_e( 'Facebook', 'swmw-law' ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/facebook-icon.svg' ); ?>" alt="<?php esc_attr_e( 'Facebook', 'swmw-law' ); ?>" class="social-icon" /></a></li>
								<li><a href="#" aria-label="<?php esc_attr_e( 'Instagram', 'swmw-law' ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/instagram-icon.svg' ); ?>" alt="<?php esc_attr_e( 'Instagram', 'swmw-law' ); ?>" class="social-icon" /></a></li>
								<li><a href="#" aria-label="<?php esc_attr_e( 'BBB', 'swmw-law' ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/bbb-icon.svg' ); ?>" alt="<?php esc_attr_e( 'BBB', 'swmw-law' ); ?>" class="social-icon bbb-icon" /></a></li>
								<li><a href="#" aria-label="<?php esc_attr_e( 'LinkedIn', 'swmw-law' ); ?>"><img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/linkedin-icon.svg' ); ?>" alt="<?php esc_attr_e( 'LinkedIn', 'swmw-law' ); ?>" class="social-icon" /></a></li>
							</ul>
						</div>
					</div>
				</div>
			</div><!-- .footer-bottom -->
		</footer><!-- #colophon -->

	</div><!-- .site -->

	<?php wp_footer(); ?>
	</body>
</html> 
