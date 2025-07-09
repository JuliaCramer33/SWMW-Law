<?php
/**
 * ACF Field Groups for SWMW Law  Attorneys (PHP Export)
 *
 * @package SWMW_Law
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers ACF Field Groups for Attorneys.
 */
function register_attorney_acf_fields() {
    if ( function_exists( 'acf_add_local_field_group' ) ) {

        acf_add_local_field_group([
            'key' => 'group_attorney_details', // Unique key
            'title' => 'Attorney Details',
            // 'fields' => [
            //     [
            //         'key' => 'field_attorney_title', // Unique key for the field
            //         'label' => 'Title',
            //         'name' => 'attorney_title',
            //         'type' => 'text',
            //         'instructions' => 'Enter the attorney\'s professional title (e.g., Partner, Associate).',
            //         'required' => 1, // Make it required
            //     ],
            // ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'attorney', // Assign to Attorney CPT
                    ],
                ],
            ],
            'menu_order' => 0,
            'position' => 'acf_after_title', // Position it high on the edit screen
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '', 
        ]);

    }
}
add_action( 'acf/init', __NAMESPACE__ . '\register_attorney_acf_fields' ); 
