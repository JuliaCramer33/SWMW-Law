<?php
/**
 * Tab Panel Block Template.
 *
 * @package SWMW_Law
 */

// Allowed blocks
$allowed_blocks = true; // Allow all blocks inside a panel.

// Get the tab title field.
$tab_title = get_field( 'tab_title' ) ?: 'Tab';

$wrapper_attributes = get_block_wrapper_attributes( [ 'class' => 'wp-block', 'data-tab-title' => esc_attr( $tab_title ) ] );

?>
<div <?php echo $wrapper_attributes; ?>>
    <?php if ( is_admin() ) : ?>
        <p class="tab-panel-editor-label">Tab: <?php echo esc_html( $tab_title ); ?></p>
    <?php endif; ?>
    <div class="tab-panel__inner-content">
        <InnerBlocks
            allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"
        />
    </div>
</div> 
