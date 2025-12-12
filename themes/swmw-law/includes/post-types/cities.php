<?php
/**
 * Cities Custom Post Type
 *
 * @package SWMW_Law
 */

namespace SWMW_Law\PostTypes;

/**
 * Register the Cities post type
 */
function register_cities_post_type() {
    $labels = array(
        'name'                => _x( 'Cities', 'Post type general name', 'swmw-law' ),
        'singular_name'      => _x( 'City', 'Post type singular name', 'swmw-law' ),
        'menu_name'             => _x( 'Cities', 'Admin Menu text', 'swmw-law' ),
        'name_admin_bar'        => _x( 'City', 'Add New on Toolbar', 'swmw-law' ),
        'add_new'             => __( 'Add New', 'swmw-law' ),
        'add_new_item'          => __( 'Add New City', 'swmw-law' ),
        'new_item'              => __( 'New City', 'swmw-law' ),
        'edit_item'             => __( 'Edit City', 'swmw-law' ),
        'view_item'             => __( 'View City', 'swmw-law' ),
        'all_items'             => __( 'All Cities', 'swmw-law' ),
        'search_items'          => __( 'Search Cities', 'swmw-law' ),
        'parent_item_colon'     => __( 'Parent Cities:', 'swmw-law' ),
        'not_found'             => __( 'No cities found.', 'swmw-law' ),
        'not_found_in_trash'    => __( 'No cities found in Trash.', 'swmw-law' ),
        'featured_image'        => _x( 'City Cover Image', 'Overrides the "Featured Image" phrase for this post type.', 'swmw-law' ),
        'set_featured_image'    => _x( 'Set cover image', 'Overrides the "Set featured image" phrase for this post type.', 'swmw-law' ),
        'remove_featured_image' => _x( 'Remove cover image', 'Overrides the "Remove featured image" phrase for this post type.', 'swmw-law' ),
        'use_featured_image'    => _x( 'Use as cover image', 'Overrides the "Use as featured image" phrase for this post type.', 'swmw-law' ),
        'archives'              => _x( 'City archives', 'The post type archive label used in nav menus.', 'swmw-law' ),
        'insert_into_item'      => _x( 'Insert into city', 'Overrides the Insert into post" phrase (used when inserting media into a post).', 'swmw-law' ),
        'uploaded_to_this_item' => _x( 'Uploaded to this city', 'Overrides theUploaded to this post" phrase (used when viewing media attached to a post).', 'swmw-law' ),
        'filter_items_list'     => _x( 'Filter cities list', 'Screen reader text for the filter links.', 'swmw-law' ),
        'items_list_navigation' => _x( 'Cities list navigation', 'Screen reader text for the pagination.', 'swmw-law' ),
        'items_list'            => _x( 'Cities list', 'Screen reader text for the items list.', 'swmw-law' ),
    );

    $args = array(
        'labels'                => $labels,
        'public'                => true,   // Keep usable in queries/UI
        'publicly_queryable'   => false,  // Disable front-end routing for singles/archives
        'show_ui'               => true,
        'show_in_menu'          => true,
        'query_var'             => true,
        'rewrite'               => false,  // No pretty permalinks for City CPT
        'capability_type'       => 'post',
        'has_archive'           => false,  // Disable /city/ archive
        'hierarchical'          => false,
        'menu_position'         => 20,
        'menu_icon'             => 'dashicons-location',
        'supports'              => array( 'title', 'editor', 'thumbnail' ),
        'show_in_rest'          => true,
    );

    register_post_type( 'city', $args );
}
add_action( 'init', __NAMESPACE__ . '\register_cities_post_type' );

/**
 * Register State taxonomy
 */
function register_state_taxonomy() {
    $labels = array(
        'name'              => _x( 'States', 'taxonomy general name', 'swmw-law' ),
        'singular_name'     => _x( 'State', 'taxonomy singular name', 'swmw-law' ),
        'search_items'      => __( 'Search States', 'swmw-law' ),
        'all_items'       => __( 'All States', 'swmw-law' ),
        'parent_item'       => __( 'Parent State', 'swmw-law' ),
        'parent_item_colon' => __( 'Parent State:', 'swmw-law' ),
        'edit_item'      => __( 'Edit State', 'swmw-law' ),
        'update_item'       => __( 'Update State', 'swmw-law' ),
        'add_new_item'   => __( 'Add New State', 'swmw-law' ),
        'new_item_name'     => __( 'New State Name', 'swmw-law' ),
        'menu_name'    => __( 'States', 'swmw-law' ),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'public'            => false,     // Hide from front-end queries/archives
        'show_ui'           => true,      // Keep editable in admin
        'show_admin_column' => true,
        'query_var'         => false,     // Disable ?state= routing
        'rewrite'           => false,     // No term archive URLs
        'show_in_rest'      => true,
    );

    register_taxonomy( 'state', array( 'city' ), $args );
}
add_action( 'init', __NAMESPACE__ . '\register_state_taxonomy' );

/**
 * Add custom columns to cities admin
 */
function add_cities_admin_columns( $columns ) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['state'] = __( 'State', 'swmw-law' );
    $new_columns['jobsites_count'] = __( 'Jobsites', 'swmw-law' );
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter( 'manage_city_posts_columns', __NAMESPACE__ . '\add_cities_admin_columns' );

/**
 * Populate custom columns
 */
function populate_cities_admin_columns( $column, $post_id ) {
    switch ( $column ) {
        case 'state':
            $terms = get_the_terms( $post_id, 'state' );
            if ( $terms && ! is_wp_error( $terms ) ) {
                $state_names = array();
                foreach ( $terms as $term ) {
                    $state_names[] = $term->name;
                }
                echo esc_html( implode( ', ', $state_names ) );
            }
            break;
        case 'jobsites_count':
            $jobsites = get_field( 'jobsites', $post_id );
            if ( $jobsites ) {
                echo count( $jobsites );
            } else {
                echo '0';
            }
            break;
    }
}
add_action( 'manage_city_posts_custom_column', __NAMESPACE__ . '\populate_cities_admin_columns', 10, 2 );

/**
 * Make columns sortable
 */
function make_cities_columns_sortable( $columns ) {
    $columns['state'] = 'state';
    return $columns;
}
add_filter( 'manage_edit-city_sortable_columns', __NAMESPACE__ . '\make_cities_columns_sortable' ); 
