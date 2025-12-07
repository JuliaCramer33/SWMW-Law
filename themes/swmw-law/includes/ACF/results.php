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
                    'key' => 'field_result_amount_display',
                    'label' => 'Amount (Display Override)',
                    'name' => 'result_amount_display',
                    'type' => 'text',
                    'instructions' => 'Optional: override formatted display (e.g., $1,234,567). If empty, the numeric amount is formatted automatically.',
                    'required' => 0,
                    'conditional_logic' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                    'default_value' => '',
                    'placeholder' => '$1,234,567',
                    'prepend' => '',
                    'append' => '',
                    'maxlength' => '',
                ),
                array(
                    'key' => 'field_result_liability_text',
                    'label' => 'Diagnosis (Liability) Text',
                    'name' => 'result_liability_text',
                    'type' => 'text',
                    'instructions' => 'Enter diagnosis/liability (e.g., Mesothelioma, Lung Cancer).',
                    'required' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_result_state',
                    'label' => 'State',
                    'name' => 'result_state',
                    'type' => 'select',
                    'instructions' => 'US State (2-letter).',
                    'required' => 0,
                    'choices' => array(
                        'AL' => 'AL','AK' => 'AK','AZ' => 'AZ','AR' => 'AR','CA' => 'CA','CO' => 'CO','CT' => 'CT','DE' => 'DE','FL' => 'FL','GA' => 'GA',
                        'HI' => 'HI','ID' => 'ID','IL' => 'IL','IN' => 'IN','IA' => 'IA','KS' => 'KS','KY' => 'KY','LA' => 'LA','ME' => 'ME','MD' => 'MD',
                        'MA' => 'MA','MI' => 'MI','MN' => 'MN','MS' => 'MS','MO' => 'MO','MT' => 'MT','NE' => 'NE','NV' => 'NV','NH' => 'NH','NJ' => 'NJ',
                        'NM' => 'NM','NY' => 'NY','NC' => 'NC','ND' => 'ND','OH' => 'OH','OK' => 'OK','OR' => 'OR','PA' => 'PA','RI' => 'RI','SC' => 'SC',
                        'SD' => 'SD','TN' => 'TN','TX' => 'TX','UT' => 'UT','VT' => 'VT','VA' => 'VA','WA' => 'WA','WV' => 'WV','WI' => 'WI','WY' => 'WY',
                        'DC' => 'DC',
                    ),
                    'allow_null' => 1,
                    'multiple' => 0,
                    'ui' => 1,
                    'return_format' => 'value',
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
                ),
                array(
                    'key' => 'field_result_occupation',
                    'label' => 'Occupation',
                    'name' => 'result_occupation',
                    'type' => 'select',
                    'instructions' => 'Select the occupation. You can add more choices later.',
                    'required' => 0,
                    'choices' => array(
                        'Laborer' => 'Laborer',
                        'Mechanic' => 'Mechanic',
                        'Pipefitter' => 'Pipefitter',
                        'Plumber/Pipefitter' => 'Plumber/Pipefitter',
                        'Sheet metal worker' => 'Sheet metal worker',
                        'Steamfitter' => 'Steamfitter',
                        'Welder' => 'Welder',
                        'Welder/Pipefitter' => 'Welder/Pipefitter',
                        'Electrician' => 'Electrician',
                        'Machinist' => 'Machinist',
                        'Boilermaker' => 'Boilermaker',
                        'Insulator' => 'Insulator',
                        'Navy' => 'Navy',
                        'Army/Laborer' => 'Army/Laborer',
                        'Navy/laborer' => 'Navy/laborer',
                        'Navy machinist' => 'Navy machinist',
                        'Navy shipfitter' => 'Navy shipfitter',
                        'Crane Operator' => 'Crane Operator',
                        'Iron worker' => 'Iron worker',
                        'Electrical engineer' => 'Electrical engineer',
                        'Railroad' => 'Railroad',
                        'Carpenter' => 'Carpenter',
                        'Drywaller' => 'Drywaller',
                        'Millwright' => 'Millwright',
                        'Miner' => 'Miner',
                        'Painter' => 'Painter',
                        'Machine Operator' => 'Machine Operator',
                    ),
                    'allow_null' => 1,
                    'multiple' => 0,
                    'ui' => 1,
                    'return_format' => 'value',
                    'ajax' => 0,
                    'wrapper' => array(
                        'width' => '',
                        'class' => '',
                        'id' => '',
                    ),
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
