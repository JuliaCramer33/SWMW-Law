<?php
/**
 * Results landing: full layout from the block editor (hero, filters, S&F) + optional theme breadcrumbs.
 * Assign to a Page with slug "results" so the URL stays /results/ (matches single-result URLs under same prefix).
 *
 * Template Name: Results landing
 * Template Post Type: page
 *
 * @package SWMW_Law
 */

get_header();

get_template_part( 'template-parts/breadcrumbs' );
?>

<main id="primary" class="site-main swmw-archive-page swmw-results-page">
	<?php
	while ( have_posts() ) :
		the_post();
		?>
		<div class="swmw-results-page__content entry-content">
			<?php the_content(); ?>
		</div>
		<?php
	endwhile;
	?>
</main>

<?php
get_footer();
