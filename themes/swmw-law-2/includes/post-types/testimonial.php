<?php
/**
 * Custom Post Type for Testimonials.
 *
 * @package SWMW_Law
 */

namespace SWMW_Law\Post_Types\Testimonial;

/**
 * Registers the Testimonial custom post type.
 */
function register_testimonial_cpt() {

    $labels = [
        'name'                  => _x( 'Testimonials', 'Post Type General Name', 'swmw-law-theme' ),
        'singular_name'         => _x( 'Testimonial', 'Post Type Singular Name', 'swmw-law-theme' ),
        'menu_name'             => __( 'Testimonials', 'swmw-law-theme' ),
        'name_admin_bar'        => __( 'Testimonial', 'swmw-law-theme' ),
        'archives'              => __( 'Testimonial Archives', 'swmw-law-theme' ),
        'attributes'            => __( 'Testimonial Attributes', 'swmw-law-theme' ),
        'parent_item_colon'     => __( 'Parent Testimonial:', 'swmw-law-theme' ),
        'all_items'             => __( 'All Testimonials', 'swmw-law-theme' ),
        'add_new_item'          => __( 'Add New Testimonial', 'swmw-law-theme' ),
        'add_new'               => __( 'Add New', 'swmw-law-theme' ),
        'new_item'              => __( 'New Testimonial', 'swmw-law-theme' ),
        'edit_item'             => __( 'Edit Testimonial', 'swmw-law-theme' ),
        'update_item'           => __( 'Update Testimonial', 'swmw-law-theme' ),
        'view_item'             => __( 'View Testimonial', 'swmw-law-theme' ),
        'view_items'            => __( 'View Testimonials', 'swmw-law-theme' ),
        'search_items'          => __( 'Search Testimonial', 'swmw-law-theme' ),
        'not_found'             => __( 'Not found', 'swmw-law-theme' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'swmw-law-theme' ),
        'featured_image'        => __( 'Author Photo', 'swmw-law-theme' ),
        'set_featured_image'    => __( 'Set author photo', 'swmw-law-theme' ),
        'remove_featured_image' => __( 'Remove author photo', 'swmw-law-theme' ),
        'use_featured_image'    => __( 'Use as author photo', 'swmw-law-theme' ),
        'insert_into_item'      => __( 'Insert into testimonial', 'swmw-law-theme' ),
        'uploaded_to_this_item' => __( 'Uploaded to this testimonial', 'swmw-law-theme' ),
        'items_list'            => __( 'Testimonials list', 'swmw-law-theme' ),
        'items_list_navigation' => __( 'Testimonials list navigation', 'swmw-law-theme' ),
        'filter_items_list'     => __( 'Filter testimonials list', 'swmw-law-theme' ),
    ];
    $args   = [
        'label'               => __( 'Testimonial', 'swmw-law-theme' ),
        'description'         => __( 'Post type for client testimonials.', 'swmw-law-theme' ),
        'labels'              => $labels,
        'supports'            => [ 'title', 'thumbnail' ],
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-format-quote',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => false,
        'can_export'          => true,
        'has_archive'         => false,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'rewrite'             => false,
        'show_in_rest'        => true,
    ];
    register_post_type( 'swmw_testimonial', $args );

}
add_action( 'init', __NAMESPACE__ . '\register_testimonial_cpt', 0 ); 
