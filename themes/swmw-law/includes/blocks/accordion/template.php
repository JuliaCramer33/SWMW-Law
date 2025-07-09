<?php
/**
 * Accordion Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */

// Allowed blocks
$allowed_blocks = array( 'acf/accordion-panel' );

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( [ 'class' => 'accordion-block' ] ) ); ?>>
    <InnerBlocks allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>" />
</div> 
