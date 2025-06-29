<?php
/**
 * Homepage Hero Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */

// Block ID
$block_id = 'hero-homepage-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
	$block_id = $block['anchor'];
}

// Block class
$class_name = 'hero-homepage';
if ( ! empty( $block['className'] ) ) {
	$class_name .= ' ' . $block['className'];
}
if ( ! empty( $block['align'] ) ) {
	$class_name .= ' align' . $block['align'];
}

// Fields
$headline         = get_field( 'headline' );
$lead_content     = get_field( 'lead_content' );
$button_left      = get_field( 'button_left' );
$button_right     = get_field( 'button_right' );
$hero_image       = get_field( 'hero_image' );
$subject_image    = get_field( 'subject_image' );
$badge_1_icon     = get_field( 'badge_1_icon' );
$badge_1_stat     = get_field( 'badge_1_stat' );
$badge_1_label    = get_field( 'badge_1_label' );
$badge_2_icon     = get_field( 'badge_2_icon' );
$badge_2_stat     = get_field( 'badge_2_stat' );
$badge_2_label    = get_field( 'badge_2_label' );

$style = '';
if ( $hero_image ) {
	$style = 'style="--hero-bg-image-url: url(' . esc_url( $hero_image['url'] ) . ')"';
}

?>

<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $class_name ); ?>" <?php echo wp_kses_post( $style ); ?>>
	<div class="container-lg hero-homepage-inner">
		<div class="hero-homepage__content">
			<?php if ( $headline ) : ?>
				<h1 class="hero-homepage__headline"><?php echo esc_html( $headline ); ?></h1>
			<?php endif; ?>

			<?php if ( $lead_content ) : ?>
				<div class="hero-homepage__lead">
					<?php echo wp_kses_post( $lead_content ); ?>
				</div>
			<?php endif; ?>

			<div class="hero-homepage__buttons">
				<?php if ( $button_left && ! empty( $button_left['url'] ) && ! empty( $button_left['title'] ) ) : ?>
					<a href="<?php echo esc_url( $button_left['url'] ); ?>" class="button button-ghost" <?php echo ( ! empty( $button_left['target'] ) ? 'target="' . esc_attr( $button_left['target'] ) . '"' : '' ); ?>>
						<?php echo esc_html( $button_left['title'] ); ?>
					</a>
				<?php endif; ?>
				<?php if ( $button_right && ! empty( $button_right['url'] ) && ! empty( $button_right['title'] ) ) : ?>
					<a href="<?php echo esc_url( $button_right['url'] ); ?>" class="button button" <?php echo ( ! empty( $button_right['target'] ) ? 'target="' . esc_attr( $button_right['target'] ) . '"' : '' ); ?>>
						<?php echo esc_html( $button_right['title'] ); ?>
					</a>
				<?php endif; ?>
			</div>
		</div>

		<div class="hero-homepage__image-column">
			<?php if ( $subject_image ) : ?>
				<?php echo wp_get_attachment_image( $subject_image['ID'], 'large', false, array( 'class' => 'hero-homepage__subject-image' ) ); ?>
			<?php endif; ?>

			<?php if ( $badge_1_stat || $badge_1_label ) : ?>
				<div class="hero-homepage__badge hero-homepage__badge--top">
					<?php if ( $badge_1_icon ) : ?>
						<div class="hero-homepage__badge-icon">
							<?php echo wp_get_attachment_image( $badge_1_icon['ID'], 'thumbnail' ); ?>
						</div>
					<?php endif; ?>
					<?php if ( $badge_1_stat ) : ?>
						<span class="hero-homepage__badge-stat"><?php echo esc_html( $badge_1_stat ); ?></span>
					<?php endif; ?>
					<?php if ( $badge_1_label ) : ?>
						<span class="hero-homepage__badge-label"><?php echo esc_html( $badge_1_label ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $badge_2_stat || $badge_2_label ) : ?>
				<div class="hero-homepage__badge hero-homepage__badge--bottom">
					<?php if ( $badge_2_icon ) : ?>
						<div class="hero-homepage__badge-icon">
							<?php echo wp_get_attachment_image( $badge_2_icon['ID'], 'thumbnail' ); ?>
						</div>
					<?php endif; ?>
					<?php if ( $badge_2_stat ) : ?>
						<span class="hero-homepage__badge-stat"><?php echo esc_html( $badge_2_stat ); ?></span>
					<?php endif; ?>
					<?php if ( $badge_2_label ) : ?>
						<span class="hero-homepage__badge-label"><?php echo esc_html( $badge_2_label ); ?></span>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
