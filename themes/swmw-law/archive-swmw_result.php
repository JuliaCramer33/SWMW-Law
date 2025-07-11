<?php
/**
 * The template for displaying the Results archive page.
 *
 * @package SWMW_Law
 */

get_header();

// Display the archive hero section, if it exists.
get_template_part( 'template-parts/breadcrumbs' );
get_template_part( 'template-parts/hero-archive' );

?>

<main id="primary" class="site-main swmw-archive-page">
	<?php
	// Secondary query for featured results.
	$featured_args = array(
		'post_type'      => 'swmw_result',
		'posts_per_page' => -1, // Show all featured
		'tax_query'      => array(
			array(
				'taxonomy' => 'swmw_result_status',
				'field'    => 'slug',
				'terms'    => 'featured',
			),
		),
	);
	$featured_query = new WP_Query( $featured_args );
	?>
	<?php if ( $featured_query->have_posts() ) : ?>
		<section class="swmw-featured-cases">
			<div class="container-lg">
				<h2 class="section-title">FEATURED CASES</h2>
				<div class="swmw-featured-cases-grid">
					<?php
					while ( $featured_query->have_posts() ) :
						$featured_query->the_post();
						$case_types     = get_the_terms( get_the_ID(), 'swmw_result_category' );
						$case_type_name = ! empty( $case_types ) && ! is_wp_error( $case_types ) ? $case_types[0]->name : '';
						?>
						<div class="result-item-inner">
							<?php if ( $case_type_name ) : ?>
								<span class="result-category"><?php echo esc_html( $case_type_name ); ?></span>
							<?php endif; ?>
							<h3 class="result-amount"><?php echo esc_html( get_field( 'result_amount' ) ); ?></h3>
							<h4 class="result-title"><?php the_title(); ?></h4>
							<div class="result-description">
								<?php the_excerpt(); ?>
							</div>
						</div>
						<?php
					endwhile;
					wp_reset_postdata();
					?>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<hr />

	<section class="swmw-all-results">
		<div class="container">
			<?php if ( have_posts() ) : ?>
				<div class="swmw-results-grid">
					<?php
					while ( have_posts() ) :
						the_post();
						$case_types     = get_the_terms( get_the_ID(), 'swmw_result_category' );
						$case_type_name = ! empty( $case_types ) && ! is_wp_error( $case_types ) ? $case_types[0]->name : '';
						?>
						<div class="result-item-inner">
							<?php if ( $case_type_name ) : ?>
								<span class="result-category"><?php echo esc_html( $case_type_name ); ?></span>
							<?php endif; ?>
							<h3 class="result-amount"><?php echo esc_html( get_field( 'result_amount' ) ); ?></h3>
							<h4 class="result-title"><?php the_title(); ?></h4>
							<div class="result-description">
								<?php the_excerpt(); ?>
							</div>
						</div>
						<?php
					endwhile;
					?>
				</div>

				<?php
				// Pagination for the main query.
				// the_posts_pagination(
				//     [
				//         'prev_text'          => esc_html__( '< Prev', 'swmw-law' ),
				//         'next_text'          => esc_html__( 'Next >', 'swmw-law' ),
				//         'screen_reader_text' => esc_html__( 'Results navigation', 'swmw-law' ),
				//         'aria_label'         => esc_html__( 'Results', 'swmw-law' ),
				//         'class'              => 'swmw-pagination',
				//     ]
				// );
				?>
				<?php
				global $wp_query;
				if ( $wp_query->max_num_pages > 1 ) :
					echo '<div class="load-more-results-wrapper text-center"><button id="load-more-results" class="button">Load More Results</button></div>';
				endif;
				?>
			<?php else : ?>
				<p><?php esc_html_e( 'No other results found.', 'swmw-law' ); ?></p>
			<?php endif; ?>
		</div>
	</section>

</main><!-- #main -->

<?php
get_footer(); 
