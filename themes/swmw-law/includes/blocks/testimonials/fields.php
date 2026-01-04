<?php
/**
 * ACF Fields for Testimonials Block
 *
 * @package SWMW_Law
 */

if ( function_exists( 'acf_add_local_field_group' ) ) : // Ensure ACF is active

    acf_add_local_field_group( array(
        'key'      => 'group_testimonials_block_settings',
        'title'    => 'Block: Testimonials Settings',
        'fields'   => array(
            array(
                'key'           => 'field_testimonials_selected_testimonials',
                'label'         => 'Selected Testimonials',
                'name'          => 'selected_testimonials',
                'type'          => 'relationship',
                'instructions'  => 'Select specific testimonials to display. If none are selected, the latest testimonials will be shown by default.',
                'required'      => 0,
                'conditional_logic' => 0,
                'post_type'     => array(
                    0 => 'swmw_testimonial',
                ),
                'filters'       => array(
                    0 => 'search',
                ),
                'return_format' => 'id',
                'min'           => '',
                'max'           => '',
            ),
        ),
        'location' => array(
            array(
                array(
                    'param'    => 'block',
                    'operator' => '==',
                    'value'    => 'acf/testimonials',
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
        'description'           => 'Settings for the Testimonials Display block.',
    ) );

endif; 
