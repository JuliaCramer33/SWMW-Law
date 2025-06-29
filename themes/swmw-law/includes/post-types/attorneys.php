<?php
/**
 * Attorneys Custom Post Type and Taxonomies
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

/**
 * Registers the Attorney CPT and associated Licenses taxonomy.
 */
function register_attorney_content() {

    // Attorneys CPT Registration
    $attorney_labels = [
        'name'                  => _x( 'Attorneys', 'Post Type General Name', 'swmw-law' ),
        'singular_name'         => _x( 'Attorney', 'Post Type Singular Name', 'swmw-law' ),
        'menu_name'             => __( 'Attorneys', 'swmw-law' ),
        'name_admin_bar'        => __( 'Attorney', 'swmw-law' ),
        'archives'              => __( 'Attorney Archives', 'swmw-law' ),
        'attributes'            => __( 'Attorney Attributes', 'swmw-law' ),
        'parent_item_colon'     => __( 'Parent Attorney:', 'swmw-law' ),
        'all_items'             => __( 'All Attorneys', 'swmw-law' ),
        'add_new_item'          => __( 'Add New Attorney', 'swmw-law' ),
        'add_new'               => __( 'Add New', 'swmw-law' ),
        'new_item'              => __( 'New Attorney', 'swmw-law' ),
        'edit_item'             => __( 'Edit Attorney', 'swmw-law' ),
        'update_item'           => __( 'Update Attorney', 'swmw-law' ),
        'view_item'             => __( 'View Attorney', 'swmw-law' ),
        'view_items'            => __( 'View Attorneys', 'swmw-law' ),
        'search_items'          => __( 'Search Attorney', 'swmw-law' ),
        'not_found'             => __( 'Not found', 'swmw-law' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'swmw-law' ),
        'featured_image'        => __( 'Featured Image', 'swmw-law' ),
        'set_featured_image'    => __( 'Set featured image', 'swmw-law' ),
        'remove_featured_image' => __( 'Remove featured image', 'swmw-law' ),
        'use_featured_image'    => __( 'Use as featured image', 'swmw-law' ),
        'insert_into_item'      => __( 'Insert into attorney', 'swmw-law' ),
        'uploaded_to_this_item' => __( 'Uploaded to this attorney', 'swmw-law' ),
        'items_list'            => __( 'Attorneys list', 'swmw-law' ),
        'items_list_navigation' => __( 'Attorneys list navigation', 'swmw-law' ),
        'filter_items_list'     => __( 'Filter attorneys list', 'swmw-law' ),
    ];
    $attorney_args = [
        'label'                 => __( 'Attorney', 'swmw-law' ),
        'description'           => __( 'Post type for attorneys', 'swmw-law' ),
        'labels'                => $attorney_labels,
        'supports'              => [ 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'revisions' ],
        'taxonomies'            => [ 'attorney_license' ], // Explicitly list associated taxonomies
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-businessman',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => 'attorneys',
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'rewrite'               => [ 'slug' => 'attorneys', 'with_front' => false ],
        'show_in_rest'          => true,
    ];
    register_post_type( 'attorney', $attorney_args );

    // Licenses Taxonomy Registration (for Attorneys)
    $license_labels = [
        'name'                       => _x( 'Licenses', 'Taxonomy General Name', 'swmw-law' ),
        'singular_name'              => _x( 'License', 'Taxonomy Singular Name', 'swmw-law' ),
        'menu_name'                  => __( 'Licenses', 'swmw-law' ),
        'all_items'                  => __( 'All Licenses', 'swmw-law' ),
        'parent_item'                => __( 'Parent License', 'swmw-law' ),
        'parent_item_colon'          => __( 'Parent License:', 'swmw-law' ),
        'new_item_name'              => __( 'New License Name', 'swmw-law' ),
        'add_new_item'               => __( 'Add New License', 'swmw-law' ),
        'edit_item'                  => __( 'Edit License', 'swmw-law' ),
        'update_item'                => __( 'Update License', 'swmw-law' ),
        'view_item'                  => __( 'View License', 'swmw-law' ),
        'separate_items_with_commas' => __( 'Separate licenses with commas', 'swmw-law' ),
        'add_or_remove_items'        => __( 'Add or remove licenses', 'swmw-law' ),
        'choose_from_most_used'      => __( 'Choose from the most used', 'swmw-law' ),
        'popular_items'              => __( 'Popular Licenses', 'swmw-law' ),
        'search_items'               => __( 'Search Licenses', 'swmw-law' ),
        'not_found'                  => __( 'Not Found', 'swmw-law' ),
        'no_terms'                   => __( 'No licenses', 'swmw-law' ),
        'items_list'                 => __( 'Licenses list', 'swmw-law' ),
        'items_list_navigation'      => __( 'Licenses list navigation', 'swmw-law' ),
    ];
    $license_args = [
        'labels'                     => $license_labels,
        'hierarchical'               => false,
        'public'                     => true,
        'show_ui'                    => true,
        'show_admin_column'          => true,
        'show_in_nav_menus'          => true,
        'show_tagcloud'              => true,
        'rewrite'                    => [ 'slug' => 'licenses', 'with_front' => false ],
        'show_in_rest'               => true,
    ];
    register_taxonomy( 'attorney_license', [ 'attorney' ], $license_args );

}
add_action( 'init', __NAMESPACE__ . '\register_attorney_content', 0 );


