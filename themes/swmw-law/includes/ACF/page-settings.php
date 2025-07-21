<?php
/**
 * ACF Field Groups for Page Settings and Accreditations Bar
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

	// Field group for individual page settings
	acf_add_local_field_group( array(
		'key'                   => 'group_page_settings',
		'title'                 => 'Page Settings',
		'fields'                => array(
			array(
				'key'           => 'field_show_accreditations_bar',
				'label'         => 'Accreditations Bar',
				'name'          => 'show_accreditations_bar',
				'type'          => 'true_false',
				'instructions'  => 'Override the global display settings for this page.',
				'required'      => 0,
				'conditional_logic' => 0,
				'wrapper' => array(
					'width' => '',
					'class' => '',
					'id' => '',
				),
				'message' => '',
				'default_value' => 0,
				'ui'            => 1,
				'ui_on_text'    => 'Show',
				'ui_off_text'   => 'Hide',
				'allow_null' => 1,
			),
			array(
				'key'           => 'field_accreditations_bar_overlap',
				'label'         => 'Overlap Style',
				'name'          => 'accreditations_bar_overlap',
				'type'          => 'true_false',
				'instructions'  => 'Pulls the bar up to overlap the content above it.',
				'required'      => 0,
				'conditional_logic' => array(
					array(
						array(
							'field' => 'field_show_accreditations_bar',
							'operator' => '==',
							'value' => '1',
						),
					),
				),
				'ui'            => 1,
				'ui_on_text'    => 'Yes',
				'ui_off_text'   => 'No',
				'default_value' => 0,
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'page',
				),
			),
			array(
				array(
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'post',
				),
			),
			array(
				array(
					'param'    => 'page_type',
					'operator' => '==',
					'value'    => 'front_page',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'side',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen'        => '',
		'active'                => true,
	) );

	// Field group for the global Accreditations Bar content
	acf_add_local_field_group( array(
		'key'                   => 'group_accreditations_bar_settings',
		'title'                 => 'Accreditations Bar Settings',
		'fields'                => array(
			array(
				'key'           => 'field_accreditations_title',
				'label'         => 'Title',
				'name'          => 'accreditations_title',
				'type'          => 'wysiwyg',
				'default_value' => 'Accreditations & Associations',
				'tabs'          => 'visual',
				'toolbar'       => 'bold',
				'media_upload'  => 0,
				'delay'         => 1,
			),
			array(
				'key'           => 'field_accreditations_logos',
				'label'         => 'Logos',
				'name'          => 'accreditations_logos',
				'type'          => 'gallery',
				'instructions'  => 'Upload the logos to display in the bar.',
				'return_format' => 'array',
				'preview_size'  => 'medium',
				'library'       => 'all',
			),
            array(
				'key'           => 'field_accreditations_archives_tab',
				'label'         => 'Archive Page Display',
				'type'          => 'tab',
				'placement'     => 'top',
			),
            array(
				'key'           => 'field_show_on_attorney_archive',
				'label'         => 'Show on Attorney Archive?',
				'name'          => 'show_on_attorney_archive',
				'type'          => 'true_false',
				'default_value' => 0,
				'ui'            => 1,
			),
            array(
				'key'           => 'field_show_on_posts_page',
				'label'         => 'Show on News/Blog Archive?',
				'name'          => 'show_on_posts_page',
				'type'          => 'true_false',
				'default_value' => 0,
				'ui'            => 1,
			),
            array(
				'key'           => 'field_show_on_results_archive',
				'label'         => 'Show on Results Archive?',
				'name'          => 'show_on_results_archive',
				'type'          => 'true_false',
				'default_value' => 0,
				'ui'            => 1,
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'options_page',
					'operator' => '==',
					'value'    => 'theme-general-settings',
				),
			),
		),
        'menu_order'            => 10,
	) );
} ); 
