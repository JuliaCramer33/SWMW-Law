<?php
/**
 * ACF Fields for Results Block
 *
 * @package SWMW_Law
 */

if ( function_exists( 'acf_add_local_field_group' ) ) : // Ensure ACF is active

    acf_add_local_field_group( array(
        'key'      => 'group_results_block_settings', // Unique key for the field group
        'title'    => 'Block: Results Settings',
        'fields'   => array(
            array(
                'key'           => 'field_results_selected_results', // Unique key for the field
                'label'         => 'Selected Results',
                'name'          => 'selected_results',
                'type'          => 'relationship',
                'post_type'     => array(
                    0 => 'swmw_result', // Your result CPT slug
                ),
                'taxonomy'      => '',
                'filters'       => array(
                    0 => 'search',
                    1 => 'post_type',
                    2 => 'taxonomy',
                ),
                'elements'      => array(
                    0 => 'featured_image',
                ),
                'min'           => '',
                'max'           => '',
                'return_format' => 'id', // Return Post IDs
                'instructions'  => 'Select specific case results to display. If none are selected, the latest results will be shown by default.',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/results', // Matches the 'name' in block.json
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => true,
        'description'           => 'Settings for the Results Display block.',
    ) );

endif; 
