<?php
/**
 * The template for displaying Attorney archives
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SWMW_Law
 */

get_header();

$description = get_the_archive_description();
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<div id="primary" class="content-area">
	<main id="main" class="site-main attorney-archive-main">

		<?php
		// Instead of the old, specific hero, we use the new generic one.
		get_template_part( 'template-parts/hero-archive' );
		?>

		<div class="container-lg attorney-archive-container">
			<?php if ( have_posts() ) : ?>
				<div class="attorney-grid">
					<?php
					/* Start the Loop */
					while ( have_posts() ) :
						the_post();
						// We'll create a new template part for the attorney card content
						get_template_part( 'template-parts/content', 'attorney-card' );
					endwhile;
					?>
				</div><!-- .attorney-grid -->

				<?php
				// Placeholder for Load More Button functionality
				// We'll need to check if there are more posts than the initial set (8)
				global $wp_query;
				if ( $wp_query->max_num_pages > 1 ) : // Only show if more than one page of results
					// The button will be added here later with AJAX functionality
					echo '<div class="load-more-attorneys-wrapper text-center"><button id="load-more-attorneys" class="button">Load More Attorneys</button></div>';
				endif;
				?>

			<?php else : ?>
				<?php get_template_part( 'template-parts/content', 'none' ); ?>
			<?php endif; ?>
		</div><!-- .container -->

	</main><!-- #main -->
</div><!-- #primary -->

<?php
get_footer(); 
