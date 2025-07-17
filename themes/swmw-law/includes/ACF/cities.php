<?php
/**
 * ACF Field Group for Cities CPT
 *
 * @package SWMW_Law
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {	exit;
}

function swmw_law_register_city_fields() {
	if ( function_exists( 'acf_add_local_field_group' ) ):

		acf_add_local_field_group( array(
			'key'                   => 'group_city_jobsites',
			'title'                 => 'City Jobsites',
			'fields'                => array(
				array(
					'key'           => 'field_city_state',
					'label'         => 'State',
					'name'          => 'state',
					'type'          => 'taxonomy',
					'instructions'  => 'Select the state this city belongs to.',
					'required'      => 1,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'taxonomy'       => 'state',
					'field_type'     => 'select',
					'allow_null'   => 0,
					'add_term'       => 1,
					'save_terms'  => 1,
					'load_terms' => 1,
					'return_format'  => 'object',
					'multiple'     => 0,
				),
				array(
					'key'           => 'field_city_jobsites',
					'label'         => 'Jobsites',
					'name'          => 'jobsites',
					'type'          => 'repeater',
					'instructions'  => 'Add jobsites/companies with known asbestos usage in this city.',
					'required'      => 0,
					'conditional_logic' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
					'collapsed'     => 'field_jobsite_name',
					'min'            => 0,
					'max'            => 0,
					'layout'         => 'table',
					'button_label'   => 'Add Jobsite',
					'sub_fields'     => array(
						array(
							'key'           => 'field_jobsite_name',
							'label'         => 'Company/Site Name',
							'name'          => 'jobsite_name',
							'type'          => 'text',
							'instructions'  => 'Enter the name of the company or jobsite.',
							'required'      => 1,
							'conditional_logic' => 0,
							'wrapper' => array(
								'width' => '50',
								'class' => '',
								'id' => '',
							),
							'default_value' => '',
							'placeholder'   => 'e.g., FEC Hialeah TT',
							'prepend'       => '',
							'append'        => '',
							'maxlength'     => '',
						),
					),
				),
			),
			'location'              => array(
				array(
					array(
						'param'  => 'post_type',
						'operator' => '==',
						'value'    => 'city',
					),
				),
			),
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'default',
			'label_placement'      => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => true,
			'description'           => 'Fields for managing city jobsites with asbestos exposure information.',
		) );

	endif;
}
add_action('acf/init', 'swmw_law_register_city_fields'); 
