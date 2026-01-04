<?php
// Register the field group
add_action('acf/init', 'register_page_header_fields');

function register_page_header_fields() {
    if (function_exists('acf_add_local_field_group')):

        acf_add_local_field_group([
            'key' => 'group_page_header',
            'title' => 'Page Header Settings',
            'fields' => [
                [
                    'key' => 'field_header_style',
                    'label' => 'Header Style',
                    'name' => 'header_style',
                    'type' => 'select',
                    'instructions' => 'Select the header style for this page',
                    'required' => 1,
                    'choices' => [
                        'standard' => 'Standard Header',
                        'with_menu' => 'Header with Auto-Navigation',
                        'with_grid' => 'Header with Image Grid',
                        'results' => 'Results Header',
                        'testimonials' => 'Testimonials Header',
                        'consultation' => 'Consultation Header',
                        'attorneys' => 'Attorneys Header'
                    ],
                    'default_value' => 'standard',
                ],
                [
                    'key' => 'field_header_title',
                    'label' => 'Header Title',
                    'name' => 'header_title',
                    'type' => 'text',
                    'instructions' => 'Enter the main title for the header',
                    'required' => 1,
                ],
                [
                    'key' => 'field_header_subtitle',
                    'label' => 'Header Subtitle',
                    'name' => 'header_subtitle',
                    'type' => 'text',
                    'instructions' => 'Enter the subtitle (if applicable)',
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_header_style',
                                'operator' => '!=',
                                'value' => 'results'
                            ]
                        ]
                    ]
                ],
                [
                    'key' => 'field_header_description',
                    'label' => 'Header Description',
                    'name' => 'header_description',
                    'type' => 'wysiwyg',
                    'instructions' => 'Enter the description text',
                    'tabs' => 'visual',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                ],
                [
                    'key' => 'field_auto_nav_menu',
                    'label' => 'Enable Auto-Navigation Menu',
                    'name' => 'auto_nav_menu',
                    'type' => 'true_false',
                    'ui' => 1,
                    'default_value' => 0,
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_header_style',
                                'operator' => '==',
                                'value' => 'with_menu'
                            ]
                        ]
                    ]
                ],
                [
                    'key' => 'field_header_images',
                    'label' => 'Header Images',
                    'name' => 'header_images',
                    'type' => 'gallery',
                    'instructions' => 'Select images for the header grid',
                    'min' => 1,
                    'max' => 8,
                    'insert' => 'append',
                    'library' => 'all',
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_header_style',
                                'operator' => '==',
                                'value' => 'with_grid'
                            ]
                        ]
                    ]
                ],
                [
                    'key' => 'field_cta_button',
                    'label' => 'Call to Action Button',
                    'name' => 'cta_button',
                    'type' => 'group',
                    'layout' => 'block',
                    'sub_fields' => [
                        [
                            'key' => 'field_cta_text',
                            'label' => 'Button Text',
                            'name' => 'text',
                            'type' => 'text',
                            'default_value' => 'Free Consultation'
                        ],
                        [
                            'key' => 'field_cta_link',
                            'label' => 'Button Link',
                            'name' => 'link',
                            'type' => 'link',
                        ]
                    ],
                    'conditional_logic' => [
                        [
                            [
                                'field' => 'field_header_style',
                                'operator' => '!=',
                                'value' => 'results'
                            ]
                        ]
                    ]
                ]
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'page',
                    ]
                ]
            ],
            'menu_order' => 0,
            'position' => 'acf_after_title',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen' => '',
            'active' => true,
            'description' => '',
        ]);

    endif;
} 
