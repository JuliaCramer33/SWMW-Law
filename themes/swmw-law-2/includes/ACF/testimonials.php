<?php
/**
 * ACF Field Group for Testimonials
 *
 * @package SWMW_Law
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function swmw_law_register_testimonial_fields() {
	if ( function_exists( 'acf_add_local_field_group' ) ) :

		acf_add_local_field_group( array(
			'key' => 'group_testimonial_details',
			'title' => 'Testimonial Details',
			'fields' => array(
				array(
					'key' => 'field_testimonial_location',
					'label' => 'Location',
					'name' => 'testimonial_location',
					'type' => 'text',
					'instructions' => 'Enter the location (city, state) of the person.',
					'required' => 0,
					'wrapper' => array(
						'width' => '50',
						'class' => '',
						'id' => '',
					),
				),
				array(
					'key' => 'field_testimonial_content',
					'label' => 'Testimonial Content',
					'name' => 'testimonial_content',
					'type' => 'textarea',
					'instructions' => 'Enter the full text of the testimonial.',
					'required' => 1,
					'rows' => 4,
					'new_lines' => 'wpautop', // Automatically add <p> tags
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
				array(
					'key' => 'field_testimonial_featured_image',
					'label' => 'Author Image',
					'name' => 'testimonial_featured_image',
					'type' => 'image',
					'instructions' => 'Upload or select an image of the testimonial author (optional).',
					'required' => 0,
					'return_format' => 'id',
					'preview_size' => 'thumbnail',
					'library' => 'all',
					'wrapper' => array(
						'width' => '',
						'class' => '',
						'id' => '',
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'swmw_testimonial',
					),
				),
			),
			'menu_order' => 0,
			'position' => 'acf_after_title',
			'style' => 'default',
			'label_placement' => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen' => array(
				'the_content', // Hide the default content editor since we're using ACF fields
			),
			'active' => true,
			'description' => 'Fields for managing testimonial content and author information.',
		) );

	endif;
}
add_action( 'acf/init', 'swmw_law_register_testimonial_fields' );
