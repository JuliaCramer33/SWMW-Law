<?php
/**
 * ACF Fields for Breadcrumbs Toggle
 *
 * @package SWMW_Law
 */

namespace SWMW_Law\ACF\Breadcrumbs;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
        'key' => 'group_breadcrumbs_toggle',
        'title' => 'Breadcrumbs Settings',
        'fields' => array(
            array(
                'key' => 'field_show_breadcrumbs',
                'label' => 'Show Breadcrumbs',
                'name' => 'show_breadcrumbs',
                'type' => 'true_false',
                'instructions' => 'Toggle this to show or hide the breadcrumbs on this page.',
                'required' => 0,
                'default_value' => 1,
                'ui' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'page',
                ),
            ),
            array(
                array(
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'post',
                ),
            ),
        ),
        'menu_order' => 0,
        'position' => 'side',
        'style' => 'default',
        'label_placement' => 'top',
        'instruction_placement' => 'label',
        'hide_on_screen' => '',
        'active' => true,
        'description' => 'Settings for displaying breadcrumbs.',
    ) );
}
