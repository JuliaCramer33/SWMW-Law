<?php
/**
 * Tabs Block Template.
 *
 * @package SWMW_Law
 */

// Allowed blocks and default template.
$allowed_blocks = array( 'acf/tab-panel' );
$template = array(
    array('acf/tab-panel', array(
        'data' => array(
            'tab_title' => 'Tab 1' // Default title for the first panel
        )
    ))
);

?>
<div <?php echo get_block_wrapper_attributes(); ?>>
    <div class="tabs-nav-container">
        <div class="tabs-nav">
            <!-- Navigation items will be dynamically inserted here by JavaScript -->
        </div>
    </div>

    <div class="tab-content">
        <InnerBlocks
            allowedBlocks="<?php echo esc_attr( wp_json_encode( $allowed_blocks ) ); ?>"
            template="<?php echo esc_attr( wp_json_encode( $template ) ); ?>"
        />
    </div>
</div> 
