<?php
/**
 * ACF Fields for Image Split Block
 *
 * @package SWMW_Law
 */

namespace SWMW_Law\Blocks\Image_Split;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
        'key' => 'group_image_split_block',
        'title' => 'Image Split Block Settings',
        'fields' => array(
            array(
                'key' => 'field_image_split_overlap_position',
                'label' => 'Overlap Position',
                'name' => 'overlap_position',
                'type' => 'radio',
                'instructions' => 'Select where the smaller image should overlap.',
                'choices' => array(
                    'right' => 'Overlap on Right',
                    'left' => 'Overlap on Left',
                ),
                'default_value' => 'right',
                'layout' => 'vertical',
                'return_format' => 'value',
            ),
            array(
                'key' => 'field_image_split_image_1',
                'label' => 'Image 1 (Top/Primary)',
                'name' => 'image_1',
                'type' => 'image',
                'instructions' => 'Select the first image (usually the larger or top one).',
                'return_format' => 'array', // Or 'id' or 'url'
                'preview_size' => 'medium',
                'library' => 'all',
                'required' => 1,
            ),
            array(
                'key' => 'field_image_split_image_2',
                'label' => 'Image 2 (Bottom/Secondary)',
                'name' => 'image_2',
                'type' => 'image',
                'instructions' => 'Select the second image (usually the smaller or bottom one).',
                'return_format' => 'array',
                'preview_size' => 'medium',
                'library' => 'all',
                'required' => 1,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/image-split',
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
        'description' => 'Fields for the Image Split block.',
    ) );
} 
