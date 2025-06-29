<?php
/**
 * ACF Fields for Tab Panel Block
 *
 * @package SWMW_Law
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

    acf_add_local_field_group( array(
        'key'      => 'group_tab_panel_settings',
        'title'    => 'Block: Tab Panel',
        'fields'   => array(
            array(
                'key'           => 'field_tab_panel_title',
                'label'         => 'Tab Title',
                'name'          => 'tab_title',
                'type'          => 'text',
                'instructions'  => 'Enter the title for this tab. This will appear in the tab navigation.',
                'required'      => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/tab-panel',
                ),
            ),
        ),
        'menu_order'            => 0,
        'position'              => 'normal',
        'style'                 => 'default',
        'label_placement'       => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen'        => '',
        'active'                => true,
        'description'           => 'Settings for the Tab Panel block.',
    ) );

endif; 
