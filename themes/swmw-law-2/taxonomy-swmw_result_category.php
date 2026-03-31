<?php
/**
 * Taxonomy archive for Result Categories (Type of Case).
 * Matches the Results archive card layout, without the featured section.
 *
 * @package SWMW_Law
 */

get_header();

// Breadcrumbs + hero archive for consistency
get_template_part( 'template-parts/breadcrumbs' );
get_template_part( 'template-parts/hero-archive' );
?>

<main id="primary" class="site-main swmw-archive-page">
	<section class="swmw-all-results">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="swmw-results-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						$case_types = get_the_terms( get_the_ID(), 'swmw_result_category' );
						$case_type  = ( ! empty( $case_types ) && ! is_wp_error( $case_types ) ) ? $case_types[0] : null;
						$case_name  = $case_type ? $case_type->name : '';
						$case_link  = $case_type ? get_term_link( $case_type ) : '';
						?>
						<div class="result-item-inner">
							<?php if ( $case_name && ! is_wp_error( $case_link ) && $case_link ) : ?>
								<a class="result-category" href="<?php echo esc_url( $case_link ); ?>"><?php echo esc_html( $case_name ); ?></a>
							<?php elseif ( $case_name ) : ?>
								<span class="result-category"><?php echo esc_html( $case_name ); ?></span>
							<?php endif; ?>

							<h3 class="result-amount"><?php echo esc_html( \SWMW_Law\swmw_law_get_formatted_amount() ); ?></h3>
							<?php $heading_occ = \SWMW_Law\swmw_law_format_result_heading_occupation(); ?>
							<?php if ( $heading_occ ) : ?>
								<h4 class="result-title"><?php echo esc_html( $heading_occ ); ?></h4>
							<?php endif; ?>
							<?php $subtext = \SWMW_Law\swmw_law_format_result_subtext(); ?>
							<?php if ( $subtext ) : ?>
								<p class="result-subtext"><?php echo esc_html( $subtext ); ?></p>
							<?php endif; ?>
							<?php $secondary = get_field( 'result_secondary_description' ); ?>
							<?php if ( $secondary ) : ?>
								<p class="result-description"><?php echo esc_html( $secondary ); ?></p>
							<?php endif; ?>
						</div>
						<?php
					endwhile;
					?>
				</div>
				
				<!-- Back to Top button -->
				<button id="results-back-to-top" class="button" style="position:fixed;right:16px;bottom:16px;z-index:1000;display:flex;align-items:center;gap:8px;opacity:0;transform:translateY(12px);transition:opacity 200ms ease, transform 200ms ease;pointer-events:none;">
					<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 31 31" fill="none" style="transform:rotate(90deg);display:block;">
						<path d="M26.75 15.7427L4.25 15.7427M4.25 15.7427L14.875 5.11767M4.25 15.7427L14.875 26.3677" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
					</svg>
					<?php esc_html_e( 'Top', 'swmw-law' ); ?>
				</button>
				<script>
				(function() {
					const btn = document.getElementById('results-back-to-top');
					if (!btn) return;
					const onScroll = () => {
						if (window.scrollY > 600) {
							btn.classList.add('is-visible');
							btn.style.opacity = '1';
							btn.style.transform = 'translateY(0)';
							btn.style.pointerEvents = 'auto';
						} else {
							btn.classList.remove('is-visible');
							btn.style.opacity = '0';
							btn.style.transform = 'translateY(12px)';
							btn.style.pointerEvents = 'none';
						}
					};
					window.addEventListener('scroll', onScroll, { passive: true });
					btn.addEventListener('click', function() {
						window.scrollTo({ top: 0, behavior: 'smooth' });
					});
					onScroll();
				})();
				</script>
			<?php else : ?>
				<p><?php esc_html_e( 'No results found for this type.', 'swmw-law' ); ?></p>
			<?php endif; ?>
		</div>
	</section>
</main>

<?php
// Include the accreditations bar at the bottom of the taxonomy page.
get_template_part( 'template-parts/accreditations-bar' );
?>

<?php
get_footer();


