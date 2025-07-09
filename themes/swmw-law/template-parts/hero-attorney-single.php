<?php
/**
 * Template part for displaying the custom hero for single attorneys.
 *
 * @package SWMW_Law
 */

// Get the attorney's data
$attorney_name  = get_the_title();
$attorney_positions = get_the_terms( get_the_ID(), 'attorney_position' ); // Use the new taxonomy
$attorney_title = ($attorney_positions && !is_wp_error($attorney_positions)) ? $attorney_positions[0]->name : '';
$portrait_url   = get_the_post_thumbnail_url( get_the_ID(), 'large' ); // Use a larger image size
$states         = get_the_terms( get_the_ID(), 'attorney_license' ); // Corrected Custom Taxonomy 'attorney_license'

// Fallback for portrait
if ( ! $portrait_url ) {
	$portrait_url = get_template_directory_uri() . '/assets/images/avatar-placeholder.svg';
}

// Background image - updated with the correct file name.
$background_image_url = get_template_directory_uri() . '/assets/images/Attorney Detail Hero Image (1).png';

?>

<div class="hero-attorney-single" style="background-image: url('<?php echo esc_url( $background_image_url ); ?>');">
	<div class="container-lg">
		<div class="hero-attorney-single__grid">

			<div class="hero-attorney-single__portrait">
				<img src="<?php echo esc_url( $portrait_url ); ?>" alt="<?php echo esc_attr( $attorney_name ); ?> portrait">
			</div>

			<div class="hero-attorney-single__info-card">
				<h1 class="hero-attorney-single__name"><?php echo esc_html( $attorney_name ); ?></h1>
				<?php if ( $attorney_title ) : ?>
					<p class="hero-attorney-single__title"><?php echo esc_html( $attorney_title ); ?></p>
				<?php endif; ?>

				<hr class="hero-attorney-single__divider">

				<?php if ( ! empty( $states ) && ! is_wp_error( $states ) ) : ?>
					<div class="hero-attorney-single__states">
						<p class="states-title"><?php esc_html_e( 'Licenses', 'swmw-law' ); ?></p>
						<ul class="states-list">
							<?php foreach ( $states as $state ) : ?>
								<li><?php echo esc_html( $state->name ); ?></li>
							<?php endforeach; ?>
						</ul>
					</div>
				<?php endif; ?>
			</div>

		</div>
	</div>
</div> 
