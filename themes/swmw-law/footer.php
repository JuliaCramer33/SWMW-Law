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
								<?php
								$facebook_url = get_theme_mod( 'swmw_law_social_facebook_url' );
								if ( $facebook_url ) : ?>
									<li><a href="<?php echo esc_url( $facebook_url ); ?>" aria-label="<?php esc_attr_e( 'Facebook', 'swmw-law' ); ?>"><svg class="social-icon facebook-icon" viewBox="0 0 320 512" xmlns="http://www.w3.org/2000/svg"><path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"></path></svg></a></li>
								<?php endif; ?>
								
								<?php
								$instagram_url = get_theme_mod( 'swmw_law_social_instagram_url' );
								if ( $instagram_url ) : ?>
									<li><a href="<?php echo esc_url( $instagram_url ); ?>" aria-label="<?php esc_attr_e( 'Instagram', 'swmw-law' ); ?>"><svg class="social-icon instagram-icon" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"></path></svg></a></li>
								<?php endif; ?>

								<?php
								$bbb_url = get_theme_mod( 'swmw_law_social_bbb_url' );
								if ( $bbb_url ) : ?>
									<li><a href="<?php echo esc_url( $bbb_url ); ?>" aria-label="<?php esc_attr_e( 'BBB', 'swmw-law' ); ?>"><svg class="social-icon bbb-icon" viewBox="0 0 36 36" data-use="/cms/svg/site/icon_bbb.36.2408211435545.svg"><path d="M22.265 35.995v-8.044h1.76l2.896 0.028c1.436-0.04 2.526 2.522 0.676 3.91a2.068 2.068 0 0 1-0.4 4.05l-3.148 0.048v0.012Zm1.788-3.334v1.872l2.536 0.012c0.972 0.04 1.036-1.924 0.036-1.936Zm0-1.512h2.184c1.138 0.048 0.788-1.76 0.028-1.76l-2.208-0.048Zm-9.447 4.846v-8.044h1.77l2.9 0.028c1.424-0.04 2.522 2.522 0.676 3.91a2.068 2.068 0 0 1-0.4 4.05l-3.148 0.048v0.012Zm1.8-3.334v1.872l2.536 0.012c0.976 0.04 1.036-1.924 0.028-1.936Zm0-1.512h2.184c1.138 0.048 0.788-1.76 0.028-1.76l-2.208-0.048ZM6.9 35.995v-8.044h1.76l2.908 0.028c1.424-0.04 2.522 2.522 0.676 3.91a2.068 2.068 0 0 1-0.4 4.05l-3.148 0.048v0.012Zm1.8-3.334v1.872l2.536 0.012c0.976 0.04 1.036-1.924 0.028-1.936Zm0-1.512h2.188c1.124 0.048 0.788-1.76 0.022-1.76l-2.208-0.048Zm5.496-6.906h-3.024v-0.012l-0.576-0.012l0.588-1.684l0.012 0.048h13.511v-0.048l0.604 1.684l-0.588 0.012v0.012h-3.046l-0.778 2.25l-5.868 0.022Zm3.836-2.508c0.172-1.36-1.424-2.276-2.664-3.024-5.37-2.872-1.35-5.36-0.548-7.524 0.028-0.11 0.326-0.084 0.25 0.176 0.3 1.876 2.968 2.46 4.094 3.56 2.176 1.95 0.84 4.846-0.922 6.872a0.244 0.244 0 0 1-0.156 0.088C18.017 21.887 17.989 21.831 18.021 21.735Zm3.108-4.872c1.612-3.288-1.534-4.12-3.41-5.518-6.804-4.96-1.282-8.072 0.264-11.279a0.172 0.172 0 0 1 0.188-0.06a0.264 0.264 0 0 1 0.286 0.388a1.8 1.8 0 0 0-0.212 1.272c0.724 1.832 4.392 3.108 6.182 5.044 3.148 3.422-0.588 7.62-2.886 10.289a0.372 0.372 0 0 1-0.286 0.1a0.164 0.164 0 0 1-0.044 0C21.101 17.1 21.039 17.018 21.129 16.874Z"></path></svg></a></li>
								<?php endif; ?>

								<?php
								$linkedin_url = get_theme_mod( 'swmw_law_social_linkedin_url' );
								if ( $linkedin_url ) : ?>
									<li><a href="<?php echo esc_url( $linkedin_url ); ?>" aria-label="<?php esc_attr_e( 'LinkedIn', 'swmw-law' ); ?>"><svg class="social-icon linkedin-icon" viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg"><path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"></path></svg></a></li>
								<?php endif; ?>
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
