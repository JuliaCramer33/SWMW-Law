<?php
/**
 * Accordion Panel Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 *
 * @package SWMW_Law
 */

// Get field data
$panel_title = get_field('panel_title') ?: 'Accordion Title'; // Fallback for title
$open_by_default = get_field('open_by_default');

$classes = ['accordion-panel'];
if ( $open_by_default ) {
    $classes[] = 'is-open';
}

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => implode(' ', $classes) ] );

?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="accordion-panel-header">
        <h3 class="accordion-panel-title"><?php echo esc_html( $panel_title ); ?></h3>
        <span class="accordion-panel-icon"></span>
    </div>
    <div class="accordion-panel-content">
        <div class="accordion-panel-content-inner">
            <InnerBlocks />
        </div>
    </div>
</div> 
