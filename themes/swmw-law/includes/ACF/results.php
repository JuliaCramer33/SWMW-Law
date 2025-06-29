<?php
/**
 * ACF Field Group for Results CPT
 *
 * @package SWMW_Law
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function swmw_law_register_result_fields() {
    if ( function_exists( 'acf_add_local_field_group' ) ) :

        acf_add_local_field_group( array(
            'key' => 'group_swmw_result_details',
            'title' => 'Result Details',
            'fields' => array(
                array(
                    'key' => 'field_result_amount',
                    'label' => 'Amount',
                    'name' => 'result_amount',
                    'type' => 'text',
                    'instructions' => 'Enter the monetary amount, e.g., $7 Million, $500,000.',
                    'required' => 1,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '$5 Million',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_result_secondary_description',
                    'label' => 'Secondary Description (e.g., Illness/Case Type & Location)',
                    'name' => 'result_secondary_description',
                    'type' => 'text',
                    'instructions' => "Enter details like 'Mesothelioma (CA)' or 'Asbestos Exposure (IL)'.",
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => 'Mesothelioma (CA)',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'swmw_result',
                    ),
                ),
            ),
            'menu_order' => 0,
            'position' => 'acf_after_title', // Position fields nicely after the title
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => 'Custom fields for Result entries.',
        ) );

    endif; 
}
add_action( 'acf/init', 'swmw_law_register_result_fields' );
