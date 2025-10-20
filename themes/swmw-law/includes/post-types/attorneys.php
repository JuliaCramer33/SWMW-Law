<?php
/**
 * Custom Post Type for Attorneys and associated Taxonomies.
 *
 * @package SWMW_Law
 */

 namespace SWMW_Law\Post_Types\Attorneys;

 // Exit if accessed directly.
 if ( ! defined( 'ABSPATH' ) ) {
   exit;
 }
 
 /**
  * Registers the Attorney custom post type.
  */
 function register_attorney_cpt() {
 
   $labels = array(
     'name'                  => _x( 'Attorneys', 'Post Type General Name', 'swmw-law-theme' ),
     'singular_name'         => _x( 'Attorney', 'Post Type Singular Name', 'swmw-law-theme' ),
     'menu_name'             => __( 'Attorneys', 'swmw-law-theme' ),
     'name_admin_bar'        => __( 'Attorney', 'swmw-law-theme' ),
     'archives'              => __( 'Attorney Archives', 'swmw-law-theme' ),
     'attributes'            => __( 'Attorney Attributes', 'swmw-law-theme' ),
     'parent_item_colon'     => __( 'Parent Attorney:', 'swmw-law-theme' ),
     'all_items'             => __( 'All Attorneys', 'swmw-law-theme' ),
     'add_new_item'          => __( 'Add New Attorney', 'swmw-law-theme' ),
     'add_new'               => __( 'Add New', 'swmw-law-theme' ),
     'new_item'              => __( 'New Attorney', 'swmw-law-theme' ),
     'edit_item'             => __( 'Edit Attorney', 'swmw-law-theme' ),
     'update_item'           => __( 'Update Attorney', 'swmw-law-theme' ),
     'view_item'             => __( 'View Attorney', 'swmw-law-theme' ),
     'view_items'            => __( 'View Attorneys', 'swmw-law-theme' ),
     'search_items'          => __( 'Search Attorney', 'swmw-law-theme' ),
     'not_found'             => __( 'Not found', 'swmw-law-theme' ),
     'not_found_in_trash'    => __( 'Not found in Trash', 'swmw-law-theme' ),
     'featured_image'        => __( 'Attorney Photo', 'swmw-law-theme' ),
     'set_featured_image'    => __( 'Set attorney photo', 'swmw-law-theme' ),
     'remove_featured_image' => __( 'Remove attorney photo', 'swmw-law-theme' ),
     'use_featured_image'    => __( 'Use as attorney photo', 'swmw-law-theme' ),
     'insert_into_item'      => __( 'Insert into attorney', 'swmw-law-theme' ),
     'uploaded_to_this_item' => __( 'Uploaded to this attorney', 'swmw-law-theme' ),
     'items_list'            => __( 'Attorneys list', 'swmw-law-theme' ),
     'items_list_navigation' => __( 'Attorneys list navigation', 'swmw-law-theme' ),
     'filter_items_list'     => __( 'Filter attorneys list', 'swmw-law-theme' ),
   );
   $args   = array(
     'label'                 => __( 'Attorney', 'swmw-law-theme' ),
     'description'           => __( 'Post type for attorneys.', 'swmw-law-theme' ),
     'labels'                => $labels,
     		'supports'              => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
     'hierarchical'          => false,
     'public'                => true,
     'show_ui'               => true,
     'show_in_menu'          => true,
     'menu_position'         => 20,
     'menu_icon'             => 'dashicons-groups',
     'show_in_admin_bar'     => true,
     'show_in_nav_menus'     => true,
     'can_export'            => true,
     'has_archive'           => 'attorneys',
     'exclude_from_search'   => false,
     'publicly_queryable'    => true,
     'capability_type'       => 'post',
     'rewrite'               => array( 'slug' => 'attorneys', 'with_front' => false ),
     'show_in_rest'          => true,
   );
   register_post_type( 'attorney', $args );
 
 }
 add_action( 'init', __NAMESPACE__ . '\register_attorney_cpt', 0 );
 
/**
 * Registers the Attorney Position custom taxonomy.
 */
function register_attorney_position_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Attorney Positions', 'Taxonomy General Name', 'swmw-law-theme' ),
		'singular_name'              => _x( 'Attorney Position', 'Taxonomy Singular Name', 'swmw-law-theme' ),
		'menu_name'                  => __( 'Attorney Positions', 'swmw-law-theme' ),
		'all_items'                  => __( 'All Attorney Positions', 'swmw-law-theme' ),
		'parent_item'                => __( 'Parent Attorney Position', 'swmw-law-theme' ),
		'parent_item_colon'          => __( 'Parent Attorney Position:', 'swmw-law-theme' ),
		'new_item_name'              => __( 'New Attorney Position Name', 'swmw-law-theme' ),
		'add_new_item'               => __( 'Add New Attorney Position', 'swmw-law-theme' ),
		'edit_item'                  => __( 'Edit Attorney Position', 'swmw-law-theme' ),
		'update_item'                => __( 'Update Attorney Position', 'swmw-law-theme' ),
		'view_item'                  => __( 'View Attorney Position', 'swmw-law-theme' ),
		'separate_items_with_commas' => __( 'Separate attorney positions with commas', 'swmw-law-theme' ),
		'add_or_remove_items'        => __( 'Add or remove attorney positions', 'swmw-law-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'swmw-law-theme' ),
		'popular_items'              => __( 'Popular Attorney Positions', 'swmw-law-theme' ),
		'search_items'               => __( 'Search Attorney Positions', 'swmw-law-theme' ),
		'not_found'                  => __( 'Not Found', 'swmw-law-theme' ),
		'no_terms'                   => __( 'No attorney positions', 'swmw-law-theme' ),
		'items_list'                 => __( 'Attorney positions list', 'swmw-law-theme' ),
		'items_list_navigation'      => __( 'Attorney positions list navigation', 'swmw-law-theme' ),
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
		'rewrite'           => array( 'slug' => 'attorney-position', 'with_front' => false ),
		'show_in_rest'      => true,
	);
	register_taxonomy( 'attorney_position', array( 'attorney' ), $args );

}
add_action( 'init', __NAMESPACE__ . '\register_attorney_position_taxonomy', 0 );

/**
 * Registers the Attorney License custom taxonomy.
 */
function register_attorney_license_taxonomy() {

	$labels = array(
		'name'                       => _x( 'Licenses', 'Taxonomy General Name', 'swmw-law-theme' ),
		'singular_name'              => _x( 'License', 'Taxonomy Singular Name', 'swmw-law-theme' ),
		'menu_name'                  => __( 'Licenses', 'swmw-law-theme' ),
		'all_items'                  => __( 'All Licenses', 'swmw-law-theme' ),
		'parent_item'                => __( 'Parent License', 'swmw-law-theme' ),
		'parent_item_colon'          => __( 'Parent License:', 'swmw-law-theme' ),
		'new_item_name'              => __( 'New License Name', 'swmw-law-theme' ),
		'add_new_item'               => __( 'Add New License', 'swmw-law-theme' ),
		'edit_item'                  => __( 'Edit License', 'swmw-law-theme' ),
		'update_item'                => __( 'Update License', 'swmw-law-theme' ),
		'view_item'                  => __( 'View License', 'swmw-law-theme' ),
		'separate_items_with_commas' => __( 'Separate licenses with commas', 'swmw-law-theme' ),
		'add_or_remove_items'        => __( 'Add or remove licenses', 'swmw-law-theme' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'swmw-law-theme' ),
		'popular_items'              => __( 'Popular Licenses', 'swmw-law-theme' ),
		'search_items'               => __( 'Search Licenses', 'swmw-law-theme' ),
		'not_found'                  => __( 'Not Found', 'swmw-law-theme' ),
		'no_terms'                   => __( 'No licenses', 'swmw-law-theme' ),
		'items_list'                 => __( 'Licenses list', 'swmw-law-theme' ),
		'items_list_navigation'      => __( 'Licenses list navigation', 'swmw-law-theme' ),
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
		'rewrite'           => array( 'slug' => 'attorney-license', 'with_front' => false ),
		'show_in_rest'      => true,
	);
	register_taxonomy( 'attorney_license', array( 'attorney' ), $args );

}
add_action( 'init', __NAMESPACE__ . '\register_attorney_license_taxonomy', 0 );



/**
 * Set default attorney positions on theme activation.
 */
function set_default_attorney_positions() {
	$default_positions = array(
		'Member'    => array( 'description' => 'Senior attorneys who are members of the firm', 'slug' => 'member' ),
		'Partner'   => array( 'description' => 'Partners of the firm', 'slug' => 'partner' ),
		'Associate' => array( 'description' => 'Associate attorneys', 'slug' => 'associate' ),
		'Others'    => array( 'description' => 'Other legal professionals', 'slug' => 'others' ),
	);

	foreach ( $default_positions as $position_name => $position_data ) {
		$existing_term = get_term_by( 'slug', $position_data['slug'], 'attorney_position' );
		
		if ( ! $existing_term ) {
			wp_insert_term(
				$position_name,
				'attorney_position',
				array(
					'description' => $position_data['description'],
					'slug'        => $position_data['slug'],
				)
			);
		}
	}
}

/**
 * Add priority meta field for sorting attorneys by position.
 */
function add_attorney_position_priority() {
	// Get all position terms to create dynamic priorities
	$position_terms = get_terms( array(
		'taxonomy' => 'attorney_position',
		'hide_empty' => false,
	) );

	// Create priority mapping based on existing terms
	$position_priorities = array();
	$priority = 1;
	
	// First, assign priorities to our default positions in order
	$default_positions = array( 'member', 'partner', 'associate' );
	foreach ( $default_positions as $default_position ) {
		$position_priorities[ $default_position ] = $priority++;
	}
	
	// Then assign priorities to any other position terms
	foreach ( $position_terms as $term ) {
		if ( ! isset( $position_priorities[ $term->slug ] ) ) {
			$position_priorities[ $term->slug ] = $priority++;
		}
	}

	// Get all attorneys
	$attorneys = get_posts( array(
		'post_type'      => 'attorney',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
	) );

	foreach ( $attorneys as $attorney ) {
		$position_terms = get_the_terms( $attorney->ID, 'attorney_position' );
		
		if ( $position_terms && ! is_wp_error( $position_terms ) ) {
			$position_slug = $position_terms[0]->slug;
			$priority = isset( $position_priorities[ $position_slug ] ) ? $position_priorities[ $position_slug ] : 999;
			
			update_post_meta( $attorney->ID, '_attorney_position_priority', $priority );
		} else {
			// Default priority for attorneys without position (lowest priority)
			update_post_meta( $attorney->ID, '_attorney_position_priority', 999 );
		}
	}
}

/**
 * Hook to run position priority assignment when needed.
 */
function maybe_assign_attorney_position_priorities() {
	// Only run if we haven't done this before or if explicitly requested
	if ( ! get_option( 'swmw_attorney_positions_initialized' ) ) {
		set_default_attorney_positions();
		add_attorney_position_priority();
		update_option( 'swmw_attorney_positions_initialized', true );
	}
}
add_action( 'init', __NAMESPACE__ . '\maybe_assign_attorney_position_priorities' );

/**
 * Update attorney position priority when attorney is saved.
 */
function update_attorney_position_priority( $post_id ) {
	// Only run for attorney post type
	if ( get_post_type( $post_id ) !== 'attorney' ) {
		return;
	}

	// Get all position terms to create dynamic priorities
	$position_terms = get_terms( array(
		'taxonomy' => 'attorney_position',
		'hide_empty' => false,
	) );

	// Create priority mapping based on existing terms
	$position_priorities = array();
	$priority = 1;
	
	// First, assign priorities to our default positions in order
	$default_positions = array( 'member', 'partner', 'associate' );
	foreach ( $default_positions as $default_position ) {
		$position_priorities[ $default_position ] = $priority++;
	}
	
	// Then assign priorities to any other position terms
	foreach ( $position_terms as $term ) {
		if ( ! isset( $position_priorities[ $term->slug ] ) ) {
			$position_priorities[ $term->slug ] = $priority++;
		}
	}

	$attorney_position_terms = get_the_terms( $post_id, 'attorney_position' );
	
	if ( $attorney_position_terms && ! is_wp_error( $attorney_position_terms ) ) {
		$position_slug = $attorney_position_terms[0]->slug;
		$priority = isset( $position_priorities[ $position_slug ] ) ? $position_priorities[ $position_slug ] : 999;
		
		update_post_meta( $post_id, '_attorney_position_priority', $priority );
	} else {
		// Default priority for attorneys without position (lowest priority)
		update_post_meta( $post_id, '_attorney_position_priority', 999 );
	}
}
add_action( 'save_post', __NAMESPACE__ . '\update_attorney_position_priority' );

/**
 * Custom query modification for attorney archive to sort by position priority.
 */
function modify_attorney_archive_query( $query ) {
	// Only modify the main query on attorney archive pages
	if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'attorney' ) ) {
        // Ensure priorities are populated so ordering works for all posts
        maybe_backfill_attorney_priorities();
        // Flag this query to use custom SQL ordering via posts_clauses filter
        $query->set( 'attorney_custom_order', true );
	}
}

/**
 * Apply custom ordering for attorney archive (and any query with attorney_custom_order).
 * Orders by position priority (ASC), then start date oldest first, then title (ASC).
 * Ensures posts without start date are included but ordered after those with a date within each position.
 *
 * @param array    $clauses Query SQL clauses.
 * @param \WP_Query $query   The current query.
 * @return array
 */
function attorney_ordering_clauses( $clauses, $query ) {
    global $wpdb;

    if ( is_admin() ) {
        return $clauses;
    }

    $apply = false;
    if ( ( $query->is_main_query() && is_post_type_archive( 'attorney' ) ) || $query->get( 'attorney_custom_order' ) ) {
        $apply = true;
    }

    if ( ! $apply ) {
        return $clauses;
    }

    // Left join priority meta and start date meta
    $clauses['join'] .= $wpdb->prepare(
        " LEFT JOIN {$wpdb->postmeta} apm ON (apm.post_id = {$wpdb->posts}.ID AND apm.meta_key = %s)",
        '_attorney_position_priority'
    );
    $clauses['join'] .= $wpdb->prepare(
        " LEFT JOIN {$wpdb->postmeta} adm ON (adm.post_id = {$wpdb->posts}.ID AND adm.meta_key = %s)",
        'attorney_start_date'
    );

    // Ensure unique posts when joining multiple postmeta rows
    $clauses['groupby'] = "{$wpdb->posts}.ID";

    // Build ORDER BY with safe casting
    $orderby = "CAST(apm.meta_value AS UNSIGNED) ASC, ";
    $orderby .= "CASE WHEN adm.meta_value IS NULL OR adm.meta_value = '' THEN 1 ELSE 0 END ASC, ";
    $orderby .= "CAST(adm.meta_value AS UNSIGNED) ASC, ";
    $orderby .= "{$wpdb->posts}.post_title ASC, ";
    $orderby .= "{$wpdb->posts}.ID ASC";

    $clauses['orderby'] = $orderby;

    return $clauses;
}
add_filter( 'posts_clauses', __NAMESPACE__ . '\\attorney_ordering_clauses', 10, 2 );
add_action( 'pre_get_posts', __NAMESPACE__ . '\modify_attorney_archive_query' );



/**
 * Ensure all attorneys have a position priority meta set so ordering works universally.
 * Runs at most once per hour to avoid heavy processing.
 */
function maybe_backfill_attorney_priorities() {
    // Only run on frontend init, throttle with a transient
    if ( is_admin() || get_transient( 'swmw_attorney_priorities_backfill' ) ) {
        return;
    }

    $missing = new \WP_Query( array(
        'post_type'      => 'attorney',
        'posts_per_page' => 1,
        'post_status'    => 'any',
        'meta_query'     => array(
            array(
                'key'     => '_attorney_position_priority',
                'compare' => 'NOT EXISTS',
            ),
        ),
        'fields'         => 'ids',
        'no_found_rows'  => true,
    ) );

    if ( $missing->have_posts() ) {
        // This will compute and set priorities for all attorneys
        add_attorney_position_priority();
    }

    set_transient( 'swmw_attorney_priorities_backfill', 1, HOUR_IN_SECONDS );
}
add_action( 'init', __NAMESPACE__ . '\maybe_backfill_attorney_priorities', 20 );

