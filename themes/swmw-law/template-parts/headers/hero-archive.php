<?php
/**
 * Template part for displaying the archive page hero section.
 *
 * @package SWMW_Law
 */

// Default values
$hero_background_image = '';
$hero_title            = get_the_archive_title();
$hero_content          = get_the_archive_description();
$hero_button           = '';

// Check for custom hero fields based on the archive type
if ( is_post_type_archive( 'attorney' ) ) {
	$hero_background_image = get_field( 'attorney_archive_background_image', 'option' );
	$custom_title          = get_field( 'attorney_archive_title', 'option' );
	$hero_content          = get_field( 'attorney_archive_content', 'option' );
	$hero_button           = get_field( 'attorney_archive_button', 'option' );
	if ( ! empty( $custom_title ) ) {
		$hero_title = $custom_title;
	}
} elseif ( is_home() || is_post_type_archive( 'post' ) ) { // For the main blog/news archive
	$hero_background_image = get_field( 'news_archive_background_image', 'option' );
	$custom_title          = get_field( 'news_archive_title', 'option' );
	$hero_content          = get_field( 'news_archive_content', 'option' );
	$hero_button           = get_field( 'news_archive_button', 'option' );
	if ( ! empty( $custom_title ) ) {
		$hero_title = $custom_title;
	}
} elseif ( is_post_type_archive( 'swmw_result' )
	|| ( function_exists( '\SWMW_Law\swmw_law_is_results_landing_page' ) && \SWMW_Law\swmw_law_is_results_landing_page() ) ) {
	$hero_background_image = get_field( 'results_archive_background_image', 'option' );
	$custom_title          = get_field( 'results_archive_title', 'option' );
	$hero_content          = get_field( 'results_archive_content', 'option' );
	$hero_button           = get_field( 'results_archive_button', 'option' );
	if ( ! empty( $custom_title ) ) {
		$hero_title = $custom_title;
	}
}

// Prepare background image style attribute.
$hero_style = $hero_background_image ? 'background-image: url(' . esc_url( $hero_background_image ) . ');' : '';

?>

<section class="hero-section swmw-archive-hero" style="<?php echo esc_attr( $hero_style ); ?>">
	<div class="container-lg">
		<?php if ( $hero_title ) : ?>
			<h1 class="page-title hero-title"><?php echo wp_kses_post( $hero_title ); ?></h1>
		<?php endif; ?>

		<?php if ( $hero_content ) : ?>
			<div class="archive-description hero-content">
				<?php echo wp_kses_post( $hero_content ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $hero_button['url'] ) && ! empty( $hero_button['title'] ) ) : ?>
			<div class="hero-button">
				<a href="<?php echo esc_url( $hero_button['url'] ); ?>" class="wp-block-button__link" <?php echo ( ! empty( $hero_button['target'] ) ) ? 'target="' . esc_attr( $hero_button['target'] ) . '"' : ''; ?>>
					<?php echo esc_html( $hero_button['title'] ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</section> 
