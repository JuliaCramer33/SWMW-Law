<?php
/**
 * ACF Fields for Dropdown Menu Block
 *
 * @package SWMW_Law
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :
    acf_add_local_field_group( array(
        'key' => 'group_hero_dropdown_menu',
        'title' => 'Block: Dropdown Menu',
        'fields' => array(
            array(
                'key' => 'field_dropdown_menu_title',
                'label' => 'Menu Title',
                'name' => 'menu_title',
                'type' => 'text',
                'instructions' => 'Enter the menu button label (e.g., "Page Navigation").',
                'required' => 1,
                'default_value' => 'Page Navigation',
            ),
            array(
                'key' => 'field_dropdown_menu_include_h3s',
                'label' => 'Include H3 Subheadings',
                'name' => 'include_h3s',
                'type' => 'true_false',
                'instructions' => 'Show H3 headings as submenu items under their parent H2.',
                'default_value' => 1,
                'ui' => 1,
                'ui_on_text' => 'Yes',
                'ui_off_text' => 'No',
            ),
            array(
                'key' => 'field_dropdown_menu_scroll_offset',
                'label' => 'Scroll Offset',
                'name' => 'scroll_offset',
                'type' => 'number',
                'instructions' => 'Offset in pixels for smooth scrolling (to account for fixed headers).',
                'default_value' => 100,
                'min' => 0,
                'max' => 200,
                'step' => 10,
            ),
            array(
                'key' => 'field_dropdown_menu_manual_override',
                'label' => 'Manual Navigation Items',
                'name' => 'manual_items',
                'type' => 'repeater',
                'instructions' => 'Optional: Override auto-generated navigation with custom items.',
                'required' => 0,
                'min' => 0,
                'layout' => 'table',
                'button_label' => 'Add Navigation Item',
                'sub_fields' => array(
                    array(
                        'key' => 'field_dropdown_menu_item_title',
                        'label' => 'Title',
                        'name' => 'title',
                        'type' => 'text',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_dropdown_menu_item_link',
                        'label' => 'Link',
                        'name' => 'link',
                        'type' => 'link',
                        'required' => 1,
                    ),
                    array(
                        'key' => 'field_dropdown_menu_item_is_submenu',
                        'label' => 'Is Submenu Item',
                        'name' => 'is_submenu',
                        'type' => 'true_false',
                        'instructions' => 'Check if this should be indented as a submenu item.',
                        'default_value' => 0,
                        'ui' => 1,
                        'ui_on_text' => 'Yes',
                        'ui_off_text' => 'No',
                    ),
                ),
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/hero-dropdown-menu',
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
        'description' => 'Fields for the Dropdown Menu block - now auto-generates navigation from H2/H3 headings.',
    ) );
endif; 
