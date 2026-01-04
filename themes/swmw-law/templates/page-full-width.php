<?php
/**
 * Template Name: Full Width
 * Template Post Type: page
 *
 * @package SWMW_Law
 */

get_header();
?>

<main id="main" class="site-main">

	<div class="container-lg">
		<?php
		while ( have_posts() ) :
			the_post();
			get_template_part( 'template-parts/content', 'page' );
		endwhile;
		?>
	</div>
</main>

<?php
get_footer(); 
