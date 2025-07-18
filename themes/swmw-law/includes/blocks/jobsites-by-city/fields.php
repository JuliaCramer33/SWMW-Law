<?php
/**
 * ACF Fields for Jobsites by City Block
 *
 * @package SWMW_Law
 */

if ( function_exists( 'acf_add_local_field_group' ) ) :

    acf_add_local_field_group( array(
        'key' => 'group_jobsites_by_city_block',
        'title' => 'Block: Jobsites by City Settings',
        'fields' => array(
            array(
                'key' => 'field_jobsites_state_filter',
                'label' => 'Filter by State',
                'name' => 'state_filter',
                'type' => 'taxonomy',
                'instructions' => 'Select a state to show only cities from that state. Leave empty to show all cities.',
                'required' => 0,
                'conditional_logic' => 0,
                'wrapper' => array(
                    'width' => '50',
                    'class' => '',
                    'id' => '',
                ),
                'taxonomy' => 'state',
                'field_type' => 'select',
                'allow_null' => 1,
                'add_term' => 0,
                'save_terms' => 0,
                'load_terms' => 0,
                'return_format' => 'object',
                'multiple' => 0,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/jobsites-by-city',
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
        'description' => 'Settings for the Jobsites by City block.',
    ) );

endif; 
