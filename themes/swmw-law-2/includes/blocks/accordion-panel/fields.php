<?php
/**
 * ACF Fields for Accordion Panel Block
 *
 * @package SWMW_Law
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

    acf_add_local_field_group( array(
        'key'      => 'group_accordion_panel_settings',
        'title'    => 'Block: Accordion Panel',
        'fields'   => array(
            array(
                'key'           => 'field_accordion_panel_title',
                'label'         => 'Panel Title',
                'name'          => 'panel_title',
                'type'          => 'text',
                'instructions'  => 'Enter the title for this accordion panel.',
                'required'      => 1,
            ),
            array(
                'key'           => 'field_accordion_panel_open_by_default',
                'label'         => 'Open by Default',
                'name'          => 'open_by_default',
                'type'          => 'true_false',
                'instructions'  => 'Set this panel to be open when the page loads.',
                'message'       => '',
                'default_value' => 0,
                'ui'            => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/accordion-panel',
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
        'description'           => 'Settings for the Accordion Panel block.',
    ) );

endif; 
