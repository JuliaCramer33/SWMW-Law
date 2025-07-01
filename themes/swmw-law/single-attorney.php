<?php
/**
 * The template for displaying all single Attorney posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package SWMW_Law
 */

get_header();
?>

<div id="primary" class="content-area">
	<main id="main" class="site-main attorney-single-page">
	<?php
	// Display breadcrumbs
	get_template_part( 'template-parts/breadcrumbs' );

	while ( have_posts() ) : // Start the Loop 
		the_post();
	?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php 
			// --- Attorney Specific Hero Section --- 
			// We will create a dedicated template part for this unique hero.
			// This hero will pull data specifically for the current attorney (e.g., name, title, custom bg from ACF).
			get_template_part( 'template-parts/hero', 'attorney-single' );
			?>

			<div class="entry-content attorney-bio-content">
					<?php
					the_content(
						sprintf(
							wp_kses(
								/* translators: %s: Name of current post. Only visible to screen readers */
								__( 'Continue reading<span class="screen-reader-text"> "%s"</span>', 'swmw-law' ),
								[
									'span' => [
										'class' => [],
									],
								]
							),
							get_the_title()
						)
					);

					wp_link_pages([
						'before' => '<div class="page-links">' . esc_html__( 'Pages:', 'swmw-law' ),
						'after'  => '</div>',
					]);
					?>
			</div><!-- .entry-content -->

		</article><!-- #post-<?php the_ID(); ?> -->

	<?php
		// If comments are open or we have at least one comment, load up the comment template.
		if ( comments_open() || get_comments_number() ) :
			comments_template();
		endif;

	endwhile; // End of the loop.
	?>
	</main>
</div><!-- #primary -->

<?php
get_footer(); 
