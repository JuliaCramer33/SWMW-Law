<?php
/**
 * Image Split Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */

// Block specific ID
$block_id = 'image-split-' . $block['id'];
if ( ! empty( $block['anchor'] ) ) {
    $block_id = $block['anchor'];
}

// Custom class names
$class_name = 'image-split-block';
if ( ! empty( $block['className'] ) ) {
    $class_name .= ' ' . $block['className'];
}

// ACF Fields
$image_1 = get_field('image_1'); // Expects image array
$image_2 = get_field('image_2'); // Expects image array
$image_alignment = get_field('image_alignment') ?: 'left'; // Default to left

// Combine all classes for the wrapper
$all_wrapper_classes = [ 'wp-block', 'image-split-block', 'align-' . $image_alignment ];
if ( ! empty( $block['className'] ) ) {
    $all_wrapper_classes[] = $block['className'];
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode( ' ', $all_wrapper_classes ) ] );

$container_classes = ['image-split-container'];

?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="image-split-container">
		<div class="image-split-column image-side">
			<?php if ( $image_1 || $image_2 ) : ?>
				<div class="image-stack">
					<?php if ( $image_1 && !empty($image_1['url']) ) : ?>
						<img src="<?php echo esc_url( $image_1['url'] ); ?>" alt="<?php echo esc_attr( $image_1['alt'] ); ?>" class="image-1" />
					<?php endif; ?>
					<?php if ( $image_2 && !empty($image_2['url']) ) : ?>
						<img src="<?php echo esc_url( $image_2['url'] ); ?>" alt="<?php echo esc_attr( $image_2['alt'] ); ?>" class="image-2" />
					<?php endif; ?>
				</div>
			<?php else : ?>
				<?php if ( $is_preview ) : ?>
					<p><em>Please select images in the block settings.</em></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
		<div class="image-split-column content-side">
			<InnerBlocks />
		</div>
	</div>
</div> 
 