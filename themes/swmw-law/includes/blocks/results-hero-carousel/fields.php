<?php
/**
 * ACF fields: Results hero carousel block.
 *
 * @package SWMW_Law
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

acf_add_local_field_group(
	array(
		'key'                   => 'group_results_hero_carousel_block',
		'title'                 => 'Block: Results hero carousel',
		'fields'                => array(
			array(
				'key'           => 'field_results_hero_carousel_relationship',
				'label'         => 'Featured results',
				'name'          => 'hero_carousel_results',
				'type'          => 'relationship',
				'instructions'  => 'Choose which case results appear in this carousel (order is preserved).',
				'required'      => 0,
				'post_type'     => array( 'swmw_result' ),
				'filters'       => array( 'search', 'taxonomy' ),
				'elements'      => array( 'featured_image' ),
				'min'           => 0,
				'max'           => 12,
				'return_format' => 'id',
			),
		),
		'location'              => array(
			array(
				array(
					'param'    => 'block',
					'operator' => '==',
					'value'    => 'acf/results-hero-carousel',
				),
			),
		),
		'menu_order'            => 0,
		'position'              => 'normal',
		'style'                 => 'default',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'active'                => true,
	)
);
