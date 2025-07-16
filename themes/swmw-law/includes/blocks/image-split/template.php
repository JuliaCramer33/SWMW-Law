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
$overlap_position = get_field('overlap_position') ?: 'right'; // Default to right

// Detect animation style from block className
$animation_style = '';
if ( ! empty( $block['className'] ) ) {
    if ( strpos( $block['className'], 'is-style-animate-slide-in-left' ) !== false ) {
        $animation_style = 'slide-in-left';
    } elseif ( strpos( $block['className'], 'is-style-animate-slide-in-right' ) !== false ) {
        $animation_style = 'slide-in-right';
    } elseif ( strpos( $block['className'], 'is-style-animate-slide-up' ) !== false ) {
        $animation_style = 'slide-up';
    } elseif ( strpos( $block['className'], 'is-style-animate-fade-in' ) !== false ) {
        $animation_style = 'fade-in';
    }
}

// Combine all classes for the wrapper
$all_wrapper_classes = [ 'wp-block', 'image-split-block', 'overlap-' . $overlap_position ];
if ( ! empty( $block['className'] ) ) {
    $all_wrapper_classes[] = $block['className'];
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode( ' ', $all_wrapper_classes ) ] );

?>
<div <?php echo wp_kses_post( $wrapper_attributes ); ?>>
	<div class="image-split-container">
		<div class="image-split-column image-side">
			<?php if ( $image_1 || $image_2 ) : ?>
				<div class="image-stack">
          <?php if ( $image_1 && !empty($image_1['url']) ) : ?>
            <img src="<?php echo esc_url( $image_1['url'] ); ?>" alt="<?php echo esc_attr( $image_1['alt'] ); ?>" class="image-1" <?php echo $animation_style ? 'data-animate="' . esc_attr($animation_style) . '"' : ''; ?> />
          <?php endif; ?>

          <?php if ( $image_2 && !empty($image_2['url']) ) : ?>
            <img src="<?php echo esc_url( $image_2['url'] ); ?>" alt="<?php echo esc_attr( $image_2['alt'] ); ?>" class="image-2" <?php echo $animation_style ? 'data-animate="' . esc_attr($animation_style) . '"' : ''; ?> />
          <?php endif; ?>
        </div>
			<?php else : ?>
				<?php if ( $is_preview ) : ?>
					<p><em>Please select images in the block settings.</em></p>
				<?php endif; ?>
			<?php endif; ?>
		</div>
	</div>
</div> 
 