<?php
/**
 * ACF Fields for Attorneys Block
 *
 * @package SWMW_Law
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

	acf_add_local_field_group( array(
		'key' => 'group_attorneys_block',
		'title' => 'Attorneys Block',
		'fields' => array(
			array(
				'key' => 'field_attorneys_selection',
				'label' => 'Select Attorneys',
				'name' => 'selected_attorneys',
				'type' => 'post_object',
				'multiple' => 1,
				'ui' => 1,
				'instructions' => 'Choose the attorneys to display (order irrelevant). Leave empty to show all attorneys.',
				'required' => 0,
				'conditional_logic' => 0,
				'post_type' => array(
					0 => 'attorney',
				),
				'ui' => 1,
				'return_format' => 'id',
			),
		),
		'location' => array(
			array(
				array(
					'param' => 'block',
					'operator' => '==',
					'value' => 'acf/attorneys',
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
