<?php
/**
 * ACF Field Groups for Archive Page Heroes.
 *
 * @package SWMW_Law
 */

if ( ! function_exists( 'acf_add_local_field_group' ) ) {
	return;
}

// Attorney Archive Hero Fields
acf_add_local_field_group( array(
	'key'      => 'group_attorney_archive_hero',
	'title'    => 'Settings: Attorney Archive Hero',
	'fields'   => array(
		array(
			'key'        => 'field_attorney_archive_accordion',
			'label'      => 'Attorney Archive Hero',
			'type'       => 'accordion',
			'endpoint'   => 0,
		),
		array(
			'key'           => 'field_attorney_archive_background_image',
			'label'         => 'Background Image',
			'name'          => 'attorney_archive_background_image',
			'type'          => 'image',
			'instructions'  => 'Upload a background image for the Attorney archive hero.',
			'return_format' => 'url',
			'preview_size'  => 'medium',
		),
		array(
			'key'          => 'field_attorney_archive_title',
			'label'        => 'Hero Title',
			'name'         => 'attorney_archive_title',
			'type'         => 'text',
			'instructions' => 'Enter a title. If left blank, the page\'s default archive title (e.g., "Attorneys") will be used.',
		),
		array(
			'key'          => 'field_attorney_archive_content',
			'label'        => 'Hero Content',
			'name'         => 'attorney_archive_content',
			'type'         => 'wysiwyg',
			'tabs'         => 'visual',
			'toolbar'      => 'basic',
			'media_upload' => 0,
		),
		array(
			'key'           => 'field_attorney_archive_button',
			'label'         => 'Hero Button',
			'name'          => 'attorney_archive_button',
			'type'          => 'link',
			'return_format' => 'array',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'theme-general-settings',
			),
		),
	),
	'menu_order' => 10,
) );

// News Archive Hero Fields
acf_add_local_field_group( array(
	'key'      => 'group_news_archive_hero',
	'title'    => 'Settings: News Archive Hero',
	'fields'   => array(
		array(
			'key'      => 'field_news_archive_accordion',
			'label'    => 'News Archive Hero',
			'type'     => 'accordion',
			'endpoint' => 0,
		),
		array(
			'key'           => 'field_news_archive_background_image',
			'label'         => 'Background Image',
			'name'          => 'news_archive_background_image',
			'type'          => 'image',
			'instructions'  => 'Upload a background image for the News archive hero.',
			'return_format' => 'url',
			'preview_size'  => 'medium',
		),
		array(
			'key'          => 'field_news_archive_title',
			'label'        => 'Hero Title',
			'name'         => 'news_archive_title',
			'type'         => 'text',
			'instructions' => 'Enter a title. If left blank, the page\'s default archive title (e.g., "News") will be used.',
		),
		array(
			'key'          => 'field_news_archive_content',
			'label'        => 'Hero Content',
			'name'         => 'news_archive_content',
			'type'         => 'wysiwyg',
			'tabs'         => 'visual',
			'toolbar'      => 'basic',
			'media_upload' => 0,
		),
		array(
			'key'           => 'field_news_archive_button',
			'label'         => 'Hero Button',
			'name'          => 'news_archive_button',
			'type'          => 'link',
			'return_format' => 'array',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'theme-general-settings',
			),
		),
	),
	'menu_order' => 11,
) );

// Result Archive Hero Fields
acf_add_local_field_group( array(
	'key'      => 'group_swmw_results_archive_hero',
	'title'    => 'Settings: Results Archive Hero',
	'fields'   => array(
		array(
			'key'      => 'field_swmw_results_archive_accordion',
			'label'    => 'Results Archive Hero',
			'type'     => 'accordion',
			'endpoint' => 0,
		),
		array(
			'key'           => 'field_swmw_results_archive_background_image',
			'label'         => 'Background Image',
			'name'          => 'results_archive_background_image',
			'type'          => 'image',
			'instructions'  => 'Upload a background image for the Results archive hero.',
			'return_format' => 'url',
			'preview_size'  => 'medium',
		),
		array(
			'key'          => 'field_swmw_results_archive_title',
			'label'        => 'Hero Title',
			'name'         => 'results_archive_title',
			'type'         => 'text',
			'instructions' => 'Enter a title. If left blank, the page\'s default archive title (e.g., "Results") will be used.',
		),
		array(
			'key'          => 'field_swmw_results_archive_content',
			'label'        => 'Hero Content',
			'name'         => 'results_archive_content',
			'type'         => 'wysiwyg',
			'tabs'         => 'visual',
			'toolbar'      => 'basic',
			'media_upload' => 0,
		),
		array(
			'key'           => 'field_swmw_results_archive_button',
			'label'         => 'Hero Button',
			'name'          => 'results_archive_button',
			'type'          => 'link',
			'return_format' => 'array',
		),
	),
	'location' => array(
		array(
			array(
				'param'    => 'options_page',
				'operator' => '==',
				'value'    => 'theme-general-settings',
			),
		),
	),
	'menu_order' => 12,
) ); 
