<?php
/**
 * Register Testimonials Post Type
 *
 * @package SWMW_Law
 */

namespace SWMW_Law\PostTypes;

// Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Post Type
 */
function register_testimonial_post_type() {
    $labels = array(
        'name'                  => _x('Testimonials', 'Post Type General Name', 'swmw-law'),
        'singular_name'         => _x('Testimonial', 'Post Type Singular Name', 'swmw-law'),
        'menu_name'            => __('Testimonials', 'swmw-law'),
        'name_admin_bar'       => __('Testimonial', 'swmw-law'),
        'archives'             => __('Testimonial Archives', 'swmw-law'),
        'attributes'           => __('Testimonial Attributes', 'swmw-law'),
        'all_items'            => __('All Testimonials', 'swmw-law'),
        'add_new_item'         => __('Add New Testimonial', 'swmw-law'),
        'add_new'              => __('Add New', 'swmw-law'),
        'new_item'             => __('New Testimonial', 'swmw-law'),
        'edit_item'            => __('Edit Testimonial', 'swmw-law'),
        'update_item'          => __('Update Testimonial', 'swmw-law'),
        'view_item'            => __('View Testimonial', 'swmw-law'),
        'view_items'           => __('View Testimonials', 'swmw-law'),
        'search_items'         => __('Search Testimonial', 'swmw-law'),
    );

    $args = array(
        'label'               => __('Testimonial', 'swmw-law'),
        'labels'              => $labels,
        'supports'            => array('title', 'editor', 'thumbnail'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'menu_position'       => 20,
        'menu_icon'           => 'dashicons-format-quote',
        'show_in_admin_bar'   => true,
        'show_in_nav_menus'   => false,
        'can_export'          => true,
        'has_archive'         => 'testimonials',
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
    );

    register_post_type('swmw_testimonial', $args);
}
add_action('init', __NAMESPACE__ . '\register_testimonial_post_type');
