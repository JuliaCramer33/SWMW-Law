<?php
/**
 * ACF Fields for Expandable Card Block
 *
 * @package SWMW_Law
 */

namespace SWMW_Law\Blocks\Expandable_Card;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( function_exists( 'acf_add_local_field_group' ) ) {
    acf_add_local_field_group( array(
        'key' => 'group_expandable_card_block',
        'title' => 'Expandable Card Block Settings',
        'fields' => array(
            array(
                'key' => 'field_expandable_card_icon',
                'label' => 'Icon',
                'name' => 'icon',
                'type' => 'image',
                'instructions' => 'Upload or select an icon for the card (recommended: 60x60px or larger)',
                'required' => 0,
                'return_format' => 'array',
                'preview_size' => 'thumbnail',
                'library' => 'all',
                'mime_types' => 'jpg,jpeg,png,svg',
            ),
            array(
                'key' => 'field_expandable_card_title',
                'label' => 'Title',
                'name' => 'title',
                'type' => 'text',
                'instructions' => 'Enter the main title for the card',
                'required' => 0,
                'maxlength' => 100,
            ),
            array(
                'key' => 'field_expandable_card_content',
                'label' => 'Content',
                'name' => 'content',
                'type' => 'wysiwyg',
                'instructions' => 'Enter the full content. The first 150 characters will show in the collapsed view, the rest will appear when expanded.',
                'required' => 0,
                'tabs' => 'visual',
                'toolbar' => 'full',
                'media_upload' => 1,
                'default_value' => '',
            ),
            array(
                'key' => 'field_expandable_card_word_count',
                'label' => 'Word Count for Excerpt',
                'name' => 'word_count',
                'type' => 'number',
                'instructions' => 'Number of words to show in the collapsed view (default: 25).',
                'required' => 0,
                'default_value' => 25,
                'min' => 10,
                'max' => 100,
                'step' => 5,
            ),
            array(
                'key' => 'field_expandable_card_word_count',
                'label' => 'Word Count for Excerpt',
                'name' => 'word_count',
                'type' => 'number',
                'instructions' => 'Number of words to show in the collapsed view (default: 25).',
                'required' => 0,
                'default_value' => 25,
                'min' => 10,
                'max' => 100,
                'step' => 5,
            ),
            array(
                'key' => 'field_expandable_card_button_text',
                'label' => 'Button Text',
                'name' => 'button_text',
                'type' => 'text',
                'instructions' => 'Text for the expand/collapse button (default: "More")',
                'required' => 0,
                'default_value' => 'More',
                'maxlength' => 20,
            ),
        ),
        'location' => array(
            array(
                array(
                    'param' => 'block',
                    'operator' => '==',
                    'value' => 'acf/expandable-card',
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
        'description' => 'Fields for the Expandable Card block.',
    ) );
} 
