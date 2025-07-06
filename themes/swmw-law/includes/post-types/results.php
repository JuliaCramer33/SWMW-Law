<?php
/**
 * Custom Post Type for Results and associated Taxonomies.
 *
 * @package SWMW_Law
 */

namespace SWMW_Law\Post_Types\Results;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers the Result custom post type.
 */
function register_result_cpt() {

	$labels = array(
		'name'                  => _x( 'Results', 'Post Type General Name', 'swmw-law-theme' ),
		'singular_name'         => _x( 'Result', 'Post Type Singular Name', 'swmw-law-theme' ),
		'menu_name'             => __( 'Results', 'swmw-law-theme' ),
		'name_admin_bar'        => __( 'Result', 'swmw-law-theme' ),
		'archives'              => __( 'Result Archives', 'swmw-law-theme' ),
		'attributes'            => __( 'Result Attributes', 'swmw-law-theme' ),
		'parent_item_colon'     => __( 'Parent Result:', 'swmw-law-theme' ),
		'all_items'             => __( 'All Results', 'swmw-law-theme' ),
		'add_new_item'          => __( 'Add New Result', 'swmw-law-theme' ),
		'add_new'               => __( 'Add New', 'swmw-law-theme' ),
		'new_item'              => __( 'New Result', 'swmw-law-theme' ),
		'edit_item'             => __( 'Edit Result', 'swmw-law-theme' ),
		'update_item'           => __( 'Update Result', 'swmw-law-theme' ),
		'view_item'             => __( 'View Result', 'swmw-law-theme' ),
		'view_items'            => __( 'View Results', 'swmw-law-theme' ),
		'search_items'          => __( 'Search Result', 'swmw-law-theme' ),
		'not_found'             => __( 'Not found', 'swmw-law-theme' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'swmw-law-theme' ),
		'featured_image'        => __( 'Featured Image', 'swmw-law-theme' ),
		'set_featured_image'    => __( 'Set featured image', 'swmw-law-theme' ),
		'remove_featured_image' => __( 'Remove featured image', 'swmw-law-theme' ),
		'use_featured_image'    => __( 'Use as featured image', 'swmw-law-theme' ),
		'insert_into_item'      => __( 'Insert into result', 'swmw-law-theme' ),
		'uploaded_to_this_item' => __( 'Uploaded to this result', 'swmw-law-theme' ),
		'items_list'            => __( 'Results list', 'swmw-law-theme' ),
		'items_list_navigation' => __( 'Results list navigation', 'swmw-law-theme' ),
		'filter_items_list'     => __( 'Filter results list', 'swmw-law-theme' ),
	);
	$args   = array(
		'label'                 => __( 'Result', 'swmw-law-theme' ),
		'description'           => __( 'Post type for case results.', 'swmw-law-theme' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail', 'excerpt' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 21,
		'menu_icon'             => 'dashicons-awards', // Changed icon
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => 'results',
		'exclude_from_search'   => true,
		'publicly_queryable'    => true,
		'capability_type'       => 'post',
		'rewrite'               => array( 'slug' => 'results', 'with_front' => false ),
		'show_in_rest'          => true,
	);
	register_post_type( 'swmw_result', $args );

}
add_action( 'init', __NAMESPACE__ . '\register_result_cpt', 0 );

/**
 * Registers the Result Category custom taxonomy.
 */
function register_result_category_taxonomy() { // Renamed function

	$labels = array(
		'name'                       => _x( 'Result Categories', 'Taxonomy General Name', 'swmw-law-theme' ), // Renamed
		'singular_name'              => _x( 'Result Category', 'Taxonomy Singular Name', 'swmw-law-theme' ), // Renamed
		'menu_name'                  => __( 'Result Categories', 'swmw-law-theme' ), // Renamed
		'all_items'                  => __( 'All Result Categories', 'swmw-law-theme' ), // Renamed
		'parent_item'                => __( 'Parent Result Category', 'swmw-law-theme' ), // Renamed
		'parent_item_colon'          => __( 'Parent Result Category:', 'swmw-law-theme' ), // Renamed
		'new_item_name'              => __( 'New Result Category Name', 'swmw-law-theme' ), // Renamed
		'add_new_item'               => __( 'Add New Result Category', 'swmw-law-theme' ), // Renamed
		'edit_item'                  => __( 'Edit Result Category', 'swmw-law-theme' ), // Renamed
		'update_item'                => __( 'Update Result Category', 'swmw-law-theme' ), // Renamed
		'view_item'                  => __( 'View Result Category', 'swmw-law-theme' ), // Renamed
		'separate_items_with_commas' => __( 'Separate result categories with commas', 'swmw-law-theme' ), // Renamed
		'add_or_remove_items'        => __( 'Add or remove result categories', 'swmw-law-theme' ), // Renamed
		'choose_from_most_used'      => __( 'Choose from the most used', 'swmw-law-theme' ),
		'popular_items'              => __( 'Popular Result Categories', 'swmw-law-theme' ), // Renamed
		'search_items'               => __( 'Search Result Categories', 'swmw-law-theme' ), // Renamed
		'not_found'                  => __( 'Not Found', 'swmw-law-theme' ),
		'no_terms'                   => __( 'No result categories', 'swmw-law-theme' ), // Renamed
		'items_list'                 => __( 'Result categories list', 'swmw-law-theme' ), // Renamed
		'items_list_navigation'      => __( 'Result categories list navigation', 'swmw-law-theme' ), // Renamed
	);
	$args = array(
		'labels'            => $labels,
		'hierarchical'      => true,
		'public'            => true,
		'show_ui'           => true,
		'show_admin_column' => true,
		'show_in_nav_menus' => true,
		'show_tagcloud'     => false,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'result-category', 'with_front' => false ), // Renamed slug
		'show_in_rest'      => true,
	);
	register_taxonomy( 'swmw_result_category', array( 'swmw_result' ), $args ); // Renamed taxonomy slug

}
add_action( 'init', __NAMESPACE__ . '\register_result_category_taxonomy', 0 ); // Renamed function

/**
 * Registers the Result Status custom taxonomy.
 */
function register_result_status_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Result Statuses', 'Taxonomy General Name', 'swmw-law-theme' ),
		'singular_name'              => _x( 'Result Status', 'Taxonomy Singular Name', 'swmw-law-theme' ),
		'menu_name'                  => __( 'Result Statuses', 'swmw-law-theme' ),
		'all_items'                  => __( 'All Result Statuses', 'swmw-law-theme' ),
		'parent_item'                => null, // Non-hierarchical
		'parent_item_colon'          => null, // Non-hierarchical
		'new_item_name'              => __( 'New Result Status Name', 'swmw-law-theme' ),
		'add_new_item'               => __( 'Add New Result Status', 'swmw-law-theme' ),
		'edit_item'                  => __( 'Edit Result Status', 'swmw-law-theme' ),
		'update_item'                => __( 'Update Result Status', 'swmw-law-theme' ),
		'view_item'                  => __( 'View Result Status', 'swmw-law-theme' ),
		'separate_items_with_commas' => __( 'Separate statuses with commas', 'swmw-law-theme' ),
		'add_or_remove_items'        => __( 'Add or remove statuses', 'swmw-law-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used statuses', 'swmw-law-theme' ),
		'popular_items'              => __( 'Popular Result Statuses', 'swmw-law-theme' ),
		'search_items'               => __( 'Search Result Statuses', 'swmw-law-theme' ),
		'not_found'                  => __( 'Not Found', 'swmw-law-theme' ),
		'no_terms'                   => __( 'No result statuses', 'swmw-law-theme' ),
		'items_list'                 => __( 'Result statuses list', 'swmw-law-theme' ),
		'items_list_navigation'      => __( 'Result statuses list navigation', 'swmw-law-theme' ),
	);
	$args = array(
		'labels'                => $labels,
		'hierarchical'          => true, // Like categories
		'public'                => true,
		'publicly_queryable'    => true,
		'show_ui'               => true,
		'show_admin_column'     => true, // Useful for seeing "Featured" status in list
		'show_in_nav_menus'     => false,
		'show_tagcloud'         => false,
		'query_var'             => true,
		'rewrite'               => array( 'slug' => 'result-status', 'with_front' => false ),
		'show_in_rest'          => true,
	);
	register_taxonomy( 'swmw_result_status', array( 'swmw_result' ), $args );

}
add_action( 'init', __NAMESPACE__ . '\register_result_status_taxonomy', 0 ); 
