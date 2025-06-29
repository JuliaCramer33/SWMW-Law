<?php
/**
 * ACF Field Groups for SWMW Law Theme Options (PHP Export)
 *
 * @package SWMW_Law
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function swmw_law_register_options_fields() {
    if ( function_exists( 'acf_add_local_field_group' ) ) :

    	acf_add_local_field_group( array(
    		'key' => 'group_theme_options_footer', // Unique key for the field group
    		'title' => 'Theme Options - Content',
    		'fields' => array(
    			array(
    				'key' => 'field_footer_settings_accordion',
    				'label' => 'Footer Settings',
    				'name' => '', // Accordions don't need a name if just for organization
    				'type' => 'accordion',
    				'instructions' => '',
    				'required' => 0,
    				'conditional_logic' => 0,
    				'wrapper' => array(
    					'width' => '',
    					'class' => '',
    					'id' => '',
    				),
    				'open' => 0,          // Whether the accordion is open by default
    				'multi_expand' => 0,  // Allows multiple accordions to be open at once
    				'endpoint' => 0,      // Marks the end of an accordion block
    			),
    			array(
    				'key' => 'field_footer_display_tagline',
    				'label' => 'Footer Tagline Display',
    				'name' => 'footer_display_tagline',
    				'type' => 'wysiwyg',
    				'instructions' => 'Enter the tagline as it should appear in the footer. Use the editor to apply formatting (e.g., bold for emphasis) and line breaks (Shift+Enter or use the Text mode for <br>).',
    				'required' => 0,
    				'conditional_logic' => 0,
    				'wrapper' => array(
    					'width' => '',
    					'class' => '',
    					'id' => '',
    				),
    				'default_value' => '',
    				'tabs' => 'visual',
    				'toolbar' => 'teeny',
    				'media_upload' => 0,
    				'delay' => 0,
    			),
    			array(
    				'key' => 'field_footer_offices',
    				'label' => 'Offices',
    				'name' => 'footer_offices',
    				'type' => 'repeater',
    				'instructions' => 'Manage the office locations displayed in the footer.',
    				'required' => 0,
    				'conditional_logic' => 0,
    				'wrapper' => array(
    					'width' => '',
    					'class' => '',
    					'id' => '',
    				),
    				'collapsed' => 'field_office_name', // Collapse repeater rows to show the office name
    				'min' => 0,
    				'max' => 0, // 0 for no limit
    				'layout' => 'table', // or 'block' or 'row'
    				'button_label' => 'Add Office',
    				'sub_fields' => array(
    					array(
    						'key' => 'field_office_name',
    						'label' => 'Office Name',
    						'name' => 'office_name',
    						'type' => 'text',
    						'instructions' => 'e.g., St. Louis Office',
    						'required' => 1,
    						'conditional_logic' => 0,
    						'wrapper' => array(
    							'width' => '30',
    							'class' => '',
    							'id' => '',
    						),
    						'default_value' => '',
    						'placeholder' => '',
    						'prepend' => '',
    						'append' => '',
    						'maxlength' => '',
    					),
    					array(
    						'key' => 'field_office_address',
    						'label' => 'Address',
    						'name' => 'office_address',
    						'type' => 'wysiwyg',
    						'instructions' => 'Enter the full office address. Use Shift+Enter for line breaks within the same paragraph.',
    						'required' => 1,
    						'conditional_logic' => 0,
    						'wrapper' => array(
    							'width' => '40',
    							'class' => '',
    							'id' => '',
    						),
    						'default_value' => '',
    						'tabs' => 'visual', // Or 'all' to allow text and visual tabs
    						'toolbar' => 'teeny', // Controls the WYSIWYG toolbar buttons. 'teeny' is minimal.
    						'media_upload' => 0, // Disable media upload button for addresses
    						'delay' => 0,
    					),
    					array(
    						'key' => 'field_office_map_link',
    						'label' => 'Map & Directions Link',
    						'name' => 'office_map_link',
    						'type' => 'link',
    						'instructions' => 'Enter the URL and link text for the map or directions.',
    						'required' => 0,
    						'conditional_logic' => 0,
    						'wrapper' => array(
    							'width' => '30',
    							'class' => '',
    							'id' => '',
    						),
    						'return_format' => 'array', // Returns an array with url, title, target
    					),
    				),
    			),
    			// You can add another accordion endpoint if you have more sections later
                // array(
                //     'key' => 'field_footer_settings_accordion_endpoint',
                //     'label' => '',
                //     'name' => '',
                //     'type' => 'accordion',
                //     'endpoint' => 1
                // ),
    		),
    		'location' => array(
    			array(
    				array(
    					'param' => 'options_page',
    					'operator' => '==',
    					'value' => 'theme-general-settings', // Slug of your options page
    				),
    			),
    		),
    		'menu_order' => 0,
    		'position' => 'normal',
    		'style' => 'default', // or 'seamless'
    		'label_placement' => 'top',
    		'instruction_placement' => 'label',
    		'hide_on_screen' => '',
    		'active' => true,
    		'description' => 'Fields for managing global theme options, including footer content.',
    	) );

    endif; 
}
add_action( 'acf/init', 'swmw_law_register_options_fields' );
