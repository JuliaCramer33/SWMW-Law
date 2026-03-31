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
						get_template_part(
							'template-parts/content',
							'result-card',
							array(
								'link_category'  => true,
								'show_secondary' => true,
							)
						);
					endwhile;
					?>
				</div>
				
				<?php get_template_part( 'template-parts/results-back-to-top' ); ?>
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


