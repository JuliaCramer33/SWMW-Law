<?php
/**
 * ACF fields for Attorneys (CPT-level fields)
 *
 * @package SWMW_Law
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'acf_add_local_field_group' ) ) :

    // Field group for Attorney post type: Start Date
    acf_add_local_field_group( array(
        'key' => 'group_attorney_fields',
        'title' => 'Attorney Fields',
        'fields' => array(
            array(
                'key' => 'field_attorney_start_date',
                'label' => 'Start Date',
                'name' => 'attorney_start_date',
                'type' => 'date_picker',
                'instructions' => 'Date the attorney started at the firm (used for ordering).',
                'required' => 0,
                'display_format' => 'Y-m-d',
                'return_format' => 'Ymd',
                'first_day' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'attorney',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'normal',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => '',
        'show_in_rest' => 0,
    ) );

endif;


