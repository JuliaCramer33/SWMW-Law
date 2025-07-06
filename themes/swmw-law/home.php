<?php
/**
 * The main template file
 *
 * @package SWMW_Law
 */

get_header();

get_template_part( 'template-parts/hero-archive' );
?>

<?php get_template_part( 'template-parts/breadcrumbs' ); ?>

<main id="main" class="site-main container-lg">
			<div class="swmw-news-grid--inner">
		<?php
		if ( have_posts() ) :
			echo '<div class="swmw-news-grid">';
			/* Start the Loop */
			while ( have_posts() ) :
				the_post();

				/*
				 * Include the Post-Type-specific template for the content.
				 * If you want to override this in a child theme, then include a file
				 * called content-___.php (where ___ is the Post Type name) and that will be used instead.
				 */
				get_template_part( 'template-parts/content', 'post-card' );

			endwhile;
			echo '</div><!-- .swmw-news-grid -->';

				the_posts_pagination(
					[
						'prev_text'          => esc_html__( '< Prev', 'swmw-law' ),
						'next_text'          => esc_html__( 'Next >', 'swmw-law' ),
						'screen_reader_text' => esc_html__( 'Posts navigation', 'swmw-law' ),
						'aria_label'         => esc_html__( 'Posts', 'swmw-law' ),
						'class'              => 'swmw-pagination',
					]
				);

			else :

				get_template_part( 'template-parts/content', 'none' );

			endif;
			?>
		</div>
</main><!-- #main -->

<?php
get_sidebar();
get_footer(); 
