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
                'instructions' => 'Enter the menu button label (e.g., "Asbestos Menu").',
                'required' => 1,
            ),
            array(
                'key' => 'field_dropdown_menu_items',
                'label' => 'Menu Items',
                'name' => 'menu_items',
                'type' => 'repeater',
                'instructions' => 'Add menu items (link).',
                'required' => 1,
                'min' => 1,
                'layout' => 'table',
                'button_label' => 'Add Menu Item',
                'sub_fields' => array(
                    array(
                        'key' => 'field_dropdown_menu_item_link',
                        'label' => 'Link',
                        'name' => 'link',
                        'type' => 'link',
                        'required' => 1,
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
        'description' => 'Fields for the Dropdown Menu block.',
    ) );
endif; 
