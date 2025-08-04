<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package SWMW_Law
 */

get_header();
?>

<?php $show_breadcrumbs = get_field( 'show_breadcrumbs' );
if ( $show_breadcrumbs ) {
    get_template_part( 'template-parts/breadcrumbs' );
} ?>

<main id="primary" class="site-main">

	<?php
	while ( have_posts() ) :
		the_post();

		get_template_part( 'template-parts/content', 'single' );

	endwhile; // End of the loop.
	?>

</main><!-- #main -->

<?php
get_footer(); 
