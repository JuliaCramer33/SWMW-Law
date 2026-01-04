<?php
/**
 * Template part for displaying the hero section on archive pages.
 *
 * This template is designed to be flexible and is used across
 * different archive pages (Attorneys, News, Results). It fetches
 * custom fields from the Theme Options page to populate the hero content.
 *
 * @package SWMW_Law
 */

$prefix = '';
if ( is_post_type_archive( 'attorney' ) ) {
	$prefix = 'attorney_archive_';
} elseif ( is_home() || is_category() || is_tag() ) { // is_home() is for the main blog/news page.
	$prefix = 'news_archive_';
} elseif ( is_post_type_archive( 'swmw_result' ) ) {
	$prefix = 'results_archive_';
} elseif ( is_tax( 'swmw_result_category' ) ) {
	// Use Results archive settings for "Type of Case" taxonomy pages
	$prefix = 'results_archive_';
}

// Fetch fields from the options page using the determined prefix.
$hero_title           = get_field( $prefix . 'title', 'option' );
$hero_content         = get_field( $prefix . 'content', 'option' );
$hero_button          = get_field( $prefix . 'button', 'option' );
$background_image_url = get_field( $prefix . 'background_image', 'option' );

// For news categories, use the category title instead of the generic News title.
if ( is_category() ) {
    $hero_title = single_cat_title( '', false );
}

// For Result Category taxonomy, format as "Results: {Term Name}"
if ( is_tax( 'swmw_result_category' ) ) {
	$term = get_queried_object();
	if ( $term && ! is_wp_error( $term ) ) {
		$hero_title = 'Results: ' . $term->name;
	}
}

// Fallback to the default WordPress archive title if the custom one isn't set.
if ( empty( $hero_title ) ) {
	$hero_title = get_the_archive_title();
}

$hero_styles = '';
$hero_classes = 'hero-archive';
if ( is_post_type_archive( 'swmw_result' ) ) {
	$hero_classes .= ' hero-archive--results';
}
if ( $background_image_url ) {
	$hero_styles = 'style="background-image: url(' . esc_url( $background_image_url ) . ');"';
}
?>

<?php if ( $hero_title ) : // Only render the hero section if there's a title to display. ?>
<div class="<?php echo esc_attr( $hero_classes ); ?>" <?php echo $hero_styles; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Style attribute is intentionally not escaped. ?>>
	<div class="container-lg">
		<div class="hero-archive__grid">
			<div class="hero-archive__left">
				<h1 class="hero-archive__title"><?php echo wp_kses_post( $hero_title ); ?></h1>
				
				<?php if ( $hero_button && ! empty( $hero_button['url'] ) && ! empty( $hero_button['title'] ) ) : ?>
					<a href="<?php echo esc_url( $hero_button['url'] ); ?>" class="button button--secondary" target="<?php echo esc_attr( $hero_button['target'] ? $hero_button['target'] : '_self' ); ?>">
						<?php echo esc_html( $hero_button['title'] ); ?>
					</a>
				<?php endif; ?>
			</div>
			
			<?php if ( $hero_content ) : ?>
				<div class="hero-archive__right">
					<div class="hero-archive__content wysiwyg-content">
						<?php echo wp_kses_post( $hero_content ); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<?php endif; ?>
