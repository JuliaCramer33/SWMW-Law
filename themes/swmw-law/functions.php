<?php
/**
 * SWMW Law functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Find an existing Result post by its title using the post_name (slug).
 * Uses WP_Query (preferred over deprecated get_page_by_title).
 *
 * @param string $title
 * @return \WP_Post|null
 */
function swmw_law_find_result_by_title( $title ) {
	$slug = sanitize_title( (string) $title );
	if ( $slug === '' ) {
		return null;
	}
	$q = new \WP_Query( array(
		'post_type'      => 'swmw_result',
		'name'           => $slug,
		'post_status'    => 'any',
		'fields'         => 'ids',
		'posts_per_page' => 1,
		'no_found_rows'  => true,
	) );
	if ( $q->have_posts() ) {
		$id = isset( $q->posts[0] ) ? (int) $q->posts[0] : 0;
		return $id > 0 ? get_post( $id ) : null;
	}
	return null;
}

// Define theme constants.
if ( ! defined( 'SWMW_LAW_VERSION' ) ) {
	define( 'SWMW_LAW_VERSION', '1.0.0' );
}

if ( ! defined( 'SWMW_LAW_DIR' ) ) {
	define( 'SWMW_LAW_DIR', trailingslashit( get_template_directory() ) );
}

if ( ! defined( 'SWMW_LAW_URI' ) ) {
	define( 'SWMW_LAW_URI', get_stylesheet_directory_uri() . '/' );
}

// Add support for post thumbnails on posts and pages.
add_action( 'after_setup_theme', function() {
    add_theme_support( 'post-thumbnails', array( 'post', 'page' ) );
    // Also explicitly add support for individual post types
    add_post_type_support( 'post', 'thumbnail' );
    add_post_type_support( 'page', 'thumbnail' );
    
    // Add custom image size for attorney cards
    // This will create a 400x480 image (5:6 aspect ratio) with hard crop from center-top
    add_image_size( 'attorney-card', 400, 480, array( 'center', 'top' ) );
}, 1 );



// Core theme setup.
require_once SWMW_LAW_DIR . 'includes/setup.php';

// Autoloader for theme classes
spl_autoload_register( function( $class ) {
    $prefix = 'SWMW_Law\\';
    $base_dir = SWMW_LAW_DIR . '/includes/';

    $len = strlen( $prefix );
    if ( strncmp( $prefix, $class, $len ) !== 0 ) {
        return;
    }

    $relative_class = substr( $class, $len );
    $file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

    if ( file_exists( $file ) ) {
        require $file;
    }
});

// Include required files
$includes = [
    'scripts',
    'template-tags',
    'customizer',
    'ACF/acf',
    'ACF/breadcrumbs',
    'button-icons',
    'blocks/blocks',
];

foreach ( $includes as $file_slug ) {
    $file_path = SWMW_LAW_DIR . 'includes/' . $file_slug . '.php';
    if ( file_exists( $file_path ) ) {
        require_once $file_path;
    }
}

// Load Custom Post Type definitions from the includes/post-types/ directory
$cpt_dir = SWMW_LAW_DIR . 'includes/post-types/';
if ( is_dir( $cpt_dir ) ) {
    $cpt_files = glob( $cpt_dir . '*.php' );
    if ( $cpt_files ) {
        foreach ( $cpt_files as $cpt_file ) {
            if ( file_exists( $cpt_file ) ) {
                require_once $cpt_file;
            }
        }
    }
}

// Load Block Pattern definitions from the /patterns/ directory
$patterns_dir = SWMW_LAW_DIR . 'patterns/';
if ( is_dir( $patterns_dir ) ) {
    $pattern_files = glob( $patterns_dir . '*.php' );
    if ( $pattern_files ) {
        foreach ( $pattern_files as $pattern_file ) {
            if ( file_exists( $pattern_file ) ) {
                require_once $pattern_file;
            }
        }
    }
}

// Add ACF Options Page
function swmw_law_acf_options_page() {
	if ( function_exists( 'acf_add_options_page' ) ) {
		acf_add_options_page([
			'page_title'    => 'Theme General Settings',
			'menu_title'    => 'Theme Settings',
			'menu_slug'     => 'theme-general-settings',
			'capability'    => 'edit_posts',
			'redirect'      => false
		]);
	}
}
add_action( 'acf/init', __NAMESPACE__ . '\swmw_law_acf_options_page' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function swmw_law_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'swmw_law_content_width', 960 );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\swmw_law_content_width', 0 );

/**
 * Modify the main query for attorney archives.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function swmw_law_attorney_archive_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'attorney' ) ) {
        $query->set( 'posts_per_page', 16 ); // Display 16 attorneys initially
    }
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\swmw_law_attorney_archive_posts_per_page' );

/**
 * Sorts the attorney archive page by position priority, then alphabetically.
 * This is now handled by the attorney post type registration in includes/post-types/attorneys.php
 * which uses meta_key '_attorney_position_priority' for sorting.
 */

/**
 * Modify the main query for the blog (news) archive.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function swmw_law_news_archive_posts_per_page( $query ) {
    // Check if it's the main query, on the frontend, and the blog posts index page (home.php).
    if ( ! is_admin() && $query->is_main_query() && $query->is_home() ) {
        $query->set( 'posts_per_page', 21 ); // Display 21 posts per page for the news archive
    }
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\swmw_law_news_archive_posts_per_page' );

/**
 * Modify the main query for category archives to only show standard posts.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function swmw_law_category_archive_posts( $query ) {
    if ( ! is_admin() && $query->is_main_query() && is_category() ) {
        $query->set( 'post_type', 'post' );
		$query->set( 'posts_per_page', 21 );
    }
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\swmw_law_category_archive_posts' );

/**
 * Modify the main query for the results archive to exclude featured results.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function swmw_law_non_featured_results_archive_query( $query ) {
	if ( ! is_admin() && $query->is_main_query() && is_post_type_archive( 'swmw_result' ) ) {
		// Exclude featured from the main archive list (below the featured section)
		$tax_query = $query->get( 'tax_query' );
		if ( ! is_array( $tax_query ) ) {
			$tax_query = array();
		}
		$tax_query[] = array(
			'taxonomy' => 'swmw_result_status',
			'field'    => 'slug',
			'terms'    => array( 'featured' ),
			'operator' => 'NOT IN',
		);
		$tax_query['relation'] = isset( $tax_query['relation'] ) ? $tax_query['relation'] : 'AND';
		$query->set( 'tax_query', $tax_query );
		$query->set( 'posts_per_page', -1 ); // Show all results
		// Custom ordering: amount desc (handled in posts_clauses)
		$query->set( 'results_custom_order', true );
	}
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\swmw_law_non_featured_results_archive_query' );

/**
 * Custom ORDER BY for Results (archive and case-type taxonomy):
 * - Featured are excluded by main query (handled elsewhere)
 * - Order strictly by highest numeric amount first using 'result_amount_num'
 * - Posts without an amount appear after, falling back to most recent first
 */
function swmw_law_results_ordering_clauses( $clauses, $query ) {
	global $wpdb;
	if ( is_admin() ) {
		return $clauses;
	}
	$apply = false;
	if ( $query->get( 'results_custom_order' ) ) {
		$apply = true;
	}
	// Apply on taxonomy archives for result categories as well
	if ( ! $apply && $query->is_tax( 'swmw_result_category' ) && $query->is_main_query() ) {
		$apply = true;
		// Also show all on taxonomy archive to mirror main archive behavior
		$query->set( 'posts_per_page', -1 );
	}
	if ( ! $apply ) {
		return $clauses;
	}
	// Left join postmeta for amount without filtering
	$amt_join = " LEFT JOIN {$wpdb->postmeta} pm_amt ON (pm_amt.post_id = {$wpdb->posts}.ID AND pm_amt.meta_key = 'result_amount_num') ";
	if ( strpos( $clauses['join'], 'pm_amt.meta_key = \'result_amount_num\'' ) === false ) {
		$clauses['join'] .= $amt_join;
	}
	// Ensure unique rows if other joins exist
	$clauses['groupby'] = "{$wpdb->posts}.ID";
	// Order: posts with numeric amount first, highest to lowest, then recent posts
	$orderby  = "CASE WHEN pm_amt.meta_value IS NULL OR pm_amt.meta_value = '' THEN 1 ELSE 0 END ASC, ";
	$orderby .= "CAST(pm_amt.meta_value AS UNSIGNED) DESC, {$wpdb->posts}.post_date DESC, {$wpdb->posts}.ID DESC";
	$clauses['orderby'] = $orderby;
	return $clauses;
}
add_filter( 'posts_clauses', __NAMESPACE__ . '\swmw_law_results_ordering_clauses', 10, 2 );

/**
 * Ensure taxonomy archives for Result Categories behave like the Results archive:
 * - Use swmw_result post type explicitly
 * - Show all posts
 * - Apply custom ordering flag
 */
function swmw_law_result_category_archive_query( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( $query->is_tax( 'swmw_result_category' ) ) {
		$query->set( 'post_type', 'swmw_result' );
		$query->set( 'posts_per_page', -1 );
		$query->set( 'results_custom_order', true );
		// Exclude featured from taxonomy archives as well
		$tax_query = $query->get( 'tax_query' );
		if ( ! is_array( $tax_query ) ) {
			$tax_query = array();
		}
		$tax_query[] = array(
			'taxonomy' => 'swmw_result_status',
			'field'    => 'slug',
			'terms'    => array( 'featured' ),
			'operator' => 'NOT IN',
		);
		$tax_query['relation'] = isset( $tax_query['relation'] ) ? $tax_query['relation'] : 'AND';
		$query->set( 'tax_query', $tax_query );
	}
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\swmw_law_result_category_archive_query' );

/**
 * Parse a human-entered money string into a numeric dollar amount.
 *
 * Examples:
 * - "$500,000" -> 500000
 * - "$7 Million" / "7m" / "7.5 million" -> 7000000 / 7500000
 * - "$3 Billion" / "3b" -> 3000000000
 * - "$120k" / "120 thousand" -> 120000
 *
 * @param string $raw Raw amount string.
 * @return float Parsed numeric amount in dollars. Returns 0 if unparseable.
 */
function swmw_law_parse_amount_to_number( $raw ) {
	if ( ! is_string( $raw ) || $raw === '' ) {
		return 0.0;
	}
	$s = strtolower( trim( $raw ) );
	// Strip common symbols/words that interfere with parsing but keep units
	$s = str_replace( [ '$', ',', 'usd', '+' ], '', $s );
	$s = trim( $s );

	$multiplier = 1.0;
	// Billion indicators: billion, bn, bill, bln, or trailing 'b'
	if (
		preg_match( '/\b(billion|bn|bill|bln)\b/', $s ) ||
		preg_match( '/\d+(?:\.\d+)?\s*b(?![a-z])/', $s ) ||
		preg_match( '/\d+(?:\.\d+)?b(?![a-z])/', $s )
	) {
		$multiplier = 1000000000.0;
	// Million indicators: million, mm, mil, mn, mln, or trailing 'm'
	} elseif (
		preg_match( '/\b(million|mm|mil|mn|mln)\b/', $s ) ||
		preg_match( '/\d+(?:\.\d+)?\s*m(?![a-z])/', $s ) ||
		preg_match( '/\d+(?:\.\d+)?m(?![a-z])/', $s )
	) {
		$multiplier = 1000000.0;
	// Thousand indicators: thousand, k, or trailing 'k'
	} elseif (
		preg_match( '/\b(thousand|k)\b/', $s ) ||
		preg_match( '/\d+(?:\.\d+)?\s*k(?![a-z])/', $s ) ||
		preg_match( '/\d+(?:\.\d+)?k(?![a-z])/', $s )
	) {
		$multiplier = 1000.0;
	}

	if ( preg_match( '/(\d+(?:\.\d+)?)/', $s, $m ) ) {
		$base = (float) $m[1];
		return $base * $multiplier;
	}
	return 0.0;
}

/**
 * On save of a Result, compute and store numeric amount for ordering.
 *
 * @param int $post_id
 */
function swmw_law_save_result_amount_numeric( $post_id ) {
	// Only for our CPT.
	if ( get_post_type( $post_id ) !== 'swmw_result' ) {
		return;
	}
	// Avoid autosave/revisions.
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( wp_is_post_revision( $post_id ) ) {
		return;
	}
	// Capability check.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$raw     = function_exists( 'get_field' ) ? get_field( 'result_amount', $post_id ) : get_post_meta( $post_id, 'result_amount', true );
	$numeric = swmw_law_parse_amount_to_number( (string) $raw );
	update_post_meta( $post_id, 'result_amount_num', $numeric );
}
add_action( 'save_post', __NAMESPACE__ . '\swmw_law_save_result_amount_numeric' );

/**
 * Order the Results archive by numeric amount, descending (largest first),
 * while including posts without the numeric meta (fallback order by date).
 *
 * @param \WP_Query $query
 */
function swmw_law_order_results_by_amount( $query ) {
	if ( is_admin() || ! $query->is_main_query() ) {
		return;
	}
	if ( is_post_type_archive( 'swmw_result' ) ) {
		// Leave archive ordering as default (date DESC) and featured exclusion only (set elsewhere).
		return;
	}
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\swmw_law_order_results_by_amount' );

/**
 * One-time backfill: compute and store numeric amounts for existing Results.
 * Runs once for an admin user on next admin page load.
 */
function swmw_law_maybe_backfill_result_amounts() {
	if ( ! is_admin() ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	// Allow an on-demand backfill by visiting any admin page with ?swmw_backfill_results=1
	$force_backfill = isset( $_GET['swmw_backfill_results'] ) && $_GET['swmw_backfill_results'] === '1'; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$already_done   = get_option( 'swmw_law_results_backfilled_amounts', '' );
	if ( ! $force_backfill && $already_done === 'done' ) {
		return;
	}
	$results = get_posts( [
		'post_type'      => 'swmw_result',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'fields'         => 'ids',
		'no_found_rows'  => true,
	] );
	foreach ( $results as $post_id ) {
		$raw = function_exists( 'get_field' ) ? get_field( 'result_amount', $post_id ) : get_post_meta( $post_id, 'result_amount', true );
		$numeric_existing = get_post_meta( $post_id, 'result_amount_num', true );
		// Only backfill when missing or zero, but we do have a display value.
		if ( ( $numeric_existing === '' || floatval( $numeric_existing ) <= 0 ) && ( is_string( $raw ) && $raw !== '' ) ) {
			$numeric = swmw_law_parse_amount_to_number( (string) $raw );
			update_post_meta( $post_id, 'result_amount_num', $numeric );
		}
	}
	update_option( 'swmw_law_results_backfilled_amounts', 'done', false );
}
add_action( 'admin_init', __NAMESPACE__ . '\swmw_law_maybe_backfill_result_amounts' );

// Load More Attorneys AJAX removed; archive shows all attorneys without AJAX

/**
 * AJAX handler for loading more results (swmw_result).
 */
function swmw_law_load_more_results_handler() {
    // Verify nonce for security
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'swmw_law_load_more_results_nonce' ) ) {
        wp_send_json_error( ['message' => 'Invalid security token'], 403 );
        wp_die();
    }

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = 12; // Load 12 results per AJAX request (divisible by 3)

    $args = [
        'post_type'      => 'swmw_result',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
        'results_custom_order' => true,
		// Preserve archive AJAX ordering as original (date DESC)
        'tax_query'      => [
            [
                'taxonomy' => 'swmw_result_status',
                'field'    => 'slug',
                'terms'    => 'featured',
                'operator' => 'NOT IN',
            ],
        ],
    ];

    $results_query = new \WP_Query( $args );

    if ( $results_query->have_posts() ) :
        ob_start();
        while ( $results_query->have_posts() ) :
            $results_query->the_post();
            // Use the same markup as in your grid:
            $case_types     = get_the_terms( get_the_ID(), 'swmw_result_category' );
            $case_type_name = ! empty( $case_types ) && ! is_wp_error( $case_types ) ? $case_types[0]->name : '';
            ?>
            <div class="result-item-inner">
                <?php if ( $case_type_name ) : ?>
                    <span class="result-category"><?php echo esc_html( $case_type_name ); ?></span>
                <?php endif; ?>
                <h3 class="result-amount"><?php echo esc_html( \SWMW_Law\swmw_law_get_formatted_amount() ); ?></h3>
                <?php $heading_occ = \SWMW_Law\swmw_law_format_result_heading_occupation(); ?>
                <?php if ( $heading_occ ) : ?>
                    <h4 class="result-title"><?php echo esc_html( $heading_occ ); ?></h4>
                <?php endif; ?>
                <?php $subtext = \SWMW_Law\swmw_law_format_result_subtext(); ?>
                <?php if ( $subtext ) : ?>
                    <p class="result-subtext"><?php echo esc_html( $subtext ); ?></p>
                <?php endif; ?>
            </div>
            <?php
        endwhile;
        $html = ob_get_clean();
        wp_send_json_success( [
            'html'        => $html,
            'max_pages'   => $results_query->max_num_pages,
            'current_page'=> $page
        ] );
    else :
        wp_send_json_success( [
            'html'        => '',
            'max_pages'   => $results_query->max_num_pages,
            'current_page'=> $page
        ] );
    endif;

    wp_reset_postdata();
    wp_die();
}
// Load More Results AJAX removed; showing all results without AJAX

/**
 * Render the icon on the front-end for the core/button block.
 *
 * @param string $block_content The block content.
 * @param array  $block         The full block, including name and attributes.
 *
 * @return string Modified block content.
 */
// Moved to includes/button-icons.php

/**
 * Add SVG support to the media library.
 *
 * @param array $mimes The allowed mime types.
 * @return array The modified mime types.
 */
function swmw_law_add_svg_to_upload_mimes( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';
	return $mimes;
}
add_filter( 'upload_mimes', __NAMESPACE__ . '\swmw_law_add_svg_to_upload_mimes', 10, 1 );

/**
 * Display SVGs in the media library.
 *
 * @param array $response The attachment response.
 * @param WP_Post $attachment The attachment object.
 * @return array The modified attachment response.
 */
function swmw_law_show_svgs_in_media_library( $response, $attachment ) {
	if ( 'image/svg+xml' === $response['mime'] ) {
		$response['sizes'] = [
			'full' => [
				'url' => $response['url'],
				'width' => 200,
				'height' => 200,
			],
		];
	}
	return $response;
}
add_filter( 'wp_prepare_attachment_for_js', __NAMESPACE__ . '\swmw_law_show_svgs_in_media_library', 10, 2 );

/**
 * Add animation data attributes to Gutenberg blocks if block style is enabled, without modifying classes.
 */
function swmw_law_add_block_animation_attributes( $block_content, $block ) {
    // Skip if we're in the admin or if content is empty
    if ( is_admin() || empty( $block_content ) ) {
        return $block_content;
    }

    if ( isset( $block['attrs']['className'] ) ) {
        $className = $block['attrs']['className'];
        $attr = '';
        if ( strpos( $className, 'is-style-animate-fade-in' ) !== false ) {
            $attr = ' data-animate="fade-in"';
        } elseif ( strpos( $className, 'is-style-animate-slide-up' ) !== false ) {
            $attr = ' data-animate="slide-up"';
        } elseif ( strpos( $className, 'is-style-animate-slide-in-right' ) !== false ) {
            $attr = ' data-animate="slide-in-right"';
        } elseif ( strpos( $className, 'is-style-animate-slide-in-left' ) !== false ) {
            $attr = ' data-animate="slide-in-left"';
        }
        if ( $attr && strpos( $block_content, 'data-animate=' ) === false ) {
            // Add the data-animate attribute after the first class="..."
            $block_content = preg_replace('/(class="[^"]*")/i', '$1' . $attr, $block_content, 1);
        }
    }
    return $block_content;
}
add_filter( 'render_block', __NAMESPACE__ . '\swmw_law_add_block_animation_attributes', 10, 2 );

/**
 * Register query vars.
 *
 * @param array $vars The array of query variables.
 * @return array
 */
function swmw_law_register_query_vars( $vars ) {
    $vars[] = 'expandable';
    return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\swmw_law_register_query_vars' );

/**
 * Output structured data (JSON-LD) for Results archives and Result Category taxonomy pages.
 * - CollectionPage with ItemList of Results
 * - BreadcrumbList
 */
function swmw_law_output_results_schema() {
	if ( is_admin() ) {
		return;
	}
	if ( ! ( is_post_type_archive( 'swmw_result' ) || is_tax( 'swmw_result_category' ) ) ) {
		return;
	}

	global $wp_query;
	if ( ! $wp_query || empty( $wp_query->posts ) ) {
		return;
	}

	// Build page context
	$current_url = home_url( add_query_arg( array(), $_SERVER['REQUEST_URI'] ?? '' ) );
	$page_name   = 'Results';
	if ( is_tax( 'swmw_result_category' ) ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) && isset( $term->name ) ) {
			$page_name = 'Results: ' . $term->name;
		}
	}

	// Build ItemList from current posts
	$item_list_elements = array();
	$position = 1;
	foreach ( $wp_query->posts as $post_obj ) {
		$post_id = isset( $post_obj->ID ) ? (int) $post_obj->ID : 0;
		if ( $post_id <= 0 ) {
			continue;
		}
		$item = array(
			'@type' => 'ListItem',
			'position' => $position++,
			'url' => get_permalink( $post_id ),
			'item' => array(
				'@type' => 'Thing',
				'name' => get_the_title( $post_id ),
			),
		);
		$desc = \SWMW_Law\swmw_law_format_result_sentence( $post_id );
		if ( $desc ) {
			$item['item']['description'] = $desc;
		}
		$amount = \SWMW_Law\swmw_law_get_formatted_amount( $post_id );
		if ( $amount ) {
			$item['item']['additionalProperty'] = array(
				array(
					'@type' => 'PropertyValue',
					'name'  => 'Result Amount',
					'value' => $amount,
				),
			);
		}
		$thumb = get_the_post_thumbnail_url( $post_id, 'large' );
		if ( $thumb ) {
			$item['item']['image'] = esc_url( $thumb );
		}
		$item_list_elements[] = $item;
	}

	$collection_ld = array(
		'@context' => 'https://schema.org',
		'@type'    => 'CollectionPage',
		'name'     => $page_name,
		'url'      => esc_url( $current_url ),
		'mainEntity' => array(
			'@type' => 'ItemList',
			'itemListElement' => $item_list_elements,
		),
	);

	// Build breadcrumbs
	$breadcrumb_items = array(
		array(
			'@type' => 'ListItem',
			'position' => 1,
			'name' => 'Home',
			'item' => home_url( '/' ),
		),
		array(
			'@type' => 'ListItem',
			'position' => 2,
			'name' => 'Results',
			'item' => get_post_type_archive_link( 'swmw_result' ),
		),
	);
	if ( is_tax( 'swmw_result_category' ) ) {
		$term = get_queried_object();
		if ( $term && ! is_wp_error( $term ) ) {
			$breadcrumb_items[] = array(
				'@type' => 'ListItem',
				'position' => 3,
				'name' => $term->name,
				'item' => get_term_link( $term ),
			);
		}
	}
	$breadcrumbs_ld = array(
		'@context' => 'https://schema.org',
		'@type'    => 'BreadcrumbList',
		'itemListElement' => $breadcrumb_items,
	);

	// Output JSON-LD
	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $collection_ld ) . '</script>' . "\n";
	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( $breadcrumbs_ld ) . '</script>' . "\n";
}
add_action( 'wp_head', __NAMESPACE__ . '\swmw_law_output_results_schema', 20 );

/**
 * Format Result amount for display with smart rounding:
 * - >= $1,000,000: round to nearest $100k and show as "$1.2 Million"
 * - $1,000–$999,999: round to nearest thousand, show full number (e.g., "$851,000")
 * - <$1,000: round to whole dollars
 */
function swmw_law_get_formatted_amount( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	// Pull fields
	$display_override = function_exists( 'get_field' ) ? (string) get_field( 'result_amount_display', $post_id ) : (string) get_post_meta( $post_id, 'result_amount_display', true );
	$raw_text         = function_exists( 'get_field' ) ? (string) get_field( 'result_amount', $post_id ) : (string) get_post_meta( $post_id, 'result_amount', true );
	$num_meta = get_post_meta( $post_id, 'result_amount_num', true );
	$val      = 0.0;
	if ( $num_meta !== '' && is_numeric( $num_meta ) ) {
		$val = (float) $num_meta;
	} elseif ( $raw_text !== '' && function_exists( __NAMESPACE__ . '\swmw_law_parse_amount_to_number' ) ) {
		$val = swmw_law_parse_amount_to_number( $raw_text );
	}
	if ( $val > 0 ) {
		// Millions: round to nearest 100k and display as "$X.X Million"
		if ( $val >= 1000000 ) {
			$millions     = round( $val / 1000000, 1 ); // 0.1 million = 100k
			$millions_str = number_format( $millions, 1, '.', '' );
			// Strip trailing .0 (e.g., 1.0 -> 1)
			$millions_str = preg_replace( '/\\.0$/', '', $millions_str );
			return '$' . $millions_str . ' Million';
		}
		// Thousands: round to nearest 1,000 and show full number
		if ( $val >= 1000 ) {
			$rounded_thousands = round( $val / 1000 ) * 1000;
			return '$' . number_format( (float) $rounded_thousands, 0, '.', ',' );
		}
		// Under $1,000: whole dollars
		return '$' . number_format( (float) round( $val ), 0, '.', ',' );
	}
	// If we couldn't derive a numeric value, fall back to display override if present
	if ( $display_override !== '' ) {
		$txt = ltrim( trim( $display_override ), '$' );
		return '$' . $txt;
	}
	// Fallback: ensure a $ prefix and strip decimals if present.
	if ( $raw_text !== '' ) {
		$txt = trim( $raw_text );
		// Remove any existing $ to avoid $$, then re-prefix.
		$txt = ltrim( $txt, '$' );
		// If it's a plain number with decimals, round.
		if ( is_numeric( str_replace( ',', '', $txt ) ) ) {
			$n = (float) str_replace( ',', '', $txt );
			return '$' . number_format( (float) round( $n ), 0, '.', ',' );
		}
		return '$' . $txt;
	}
	return '';
}

/**
 * Build headline sentence: "Occupation Diagnosed With Liability in State"
 * Uses structured fields when available; state rendered as full name.
 */
function swmw_law_format_result_sentence( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	$occupation = function_exists( 'get_field' ) ? (string) get_field( 'result_occupation', $post_id ) : (string) get_post_meta( $post_id, 'result_occupation', true );
	$liability  = '';
	// Prefer explicit liability text if present
	if ( function_exists( 'get_field' ) ) {
		$liability = (string) get_field( 'result_liability_text', $post_id );
		if ( $liability === '' ) {
			$liability = (string) get_field( 'result_liability', $post_id ); // legacy taxonomy field, if exists
		}
	}
	if ( $liability === '' ) {
		$terms = get_the_terms( $post_id, 'swmw_result_category' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$liability = $terms[0]->name;
		}
	}
	$state = function_exists( 'get_field' ) ? (string) get_field( 'result_state', $post_id ) : (string) get_post_meta( $post_id, 'result_state', true );
	$state_full = '';
	if ( $state !== '' ) {
		$code = strtoupper( trim( $state ) );
		$states = array(
			'AL' => 'Alabama','AK' => 'Alaska','AZ' => 'Arizona','AR' => 'Arkansas','CA' => 'California','CO' => 'Colorado','CT' => 'Connecticut','DE' => 'Delaware','FL' => 'Florida','GA' => 'Georgia',
			'HI' => 'Hawaii','ID' => 'Idaho','IL' => 'Illinois','IN' => 'Indiana','IA' => 'Iowa','KS' => 'Kansas','KY' => 'Kentucky','LA' => 'Louisiana','ME' => 'Maine','MD' => 'Maryland',
			'MA' => 'Massachusetts','MI' => 'Michigan','MN' => 'Minnesota','MS' => 'Mississippi','MO' => 'Missouri','MT' => 'Montana','NE' => 'Nebraska','NV' => 'Nevada','NH' => 'New Hampshire','NJ' => 'New Jersey',
			'NM' => 'New Mexico','NY' => 'New York','NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio','OK' => 'Oklahoma','OR' => 'Oregon','PA' => 'Pennsylvania','RI' => 'Rhode Island','SC' => 'South Carolina',
			'SD' => 'South Dakota','TN' => 'Tennessee','TX' => 'Texas','UT' => 'Utah','VT' => 'Vermont','VA' => 'Virginia','WA' => 'Washington','WV' => 'West Virginia','WI' => 'Wisconsin','WY' => 'Wyoming',
			'DC' => 'District of Columbia',
		);
		$state_full = isset( $states[ $code ] ) ? $states[ $code ] : $state;
	}

	$parts = array();
	if ( $occupation !== '' ) {
		$parts[] = $occupation;
	}
	if ( $liability !== '' ) {
		$parts[] = 'Diagnosed With ' . $liability;
	}
	$out = '';
	if ( ! empty( $parts ) ) {
		$out = implode( ' ', $parts );
		if ( $state_full !== '' ) {
			$out .= ' in ' . $state_full;
		}
	}
	return $out;
}

/**
 * Build heading HTML: "<strong>Occupation</strong> in Full State — Type"
 * - Occupation comes from result_occupation
 * - State uses full state name (from result_state)
 * - Type comes from the first result category term that is not a generic status
 */
function swmw_law_format_result_heading_html( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	$occupation = function_exists( 'get_field' ) ? (string) get_field( 'result_occupation', $post_id ) : (string) get_post_meta( $post_id, 'result_occupation', true );
	$state      = function_exists( 'get_field' ) ? (string) get_field( 'result_state', $post_id ) : (string) get_post_meta( $post_id, 'result_state', true );
	// Full state
	$state_full = '';
	if ( $state !== '' ) {
		$code = strtoupper( trim( $state ) );
		$states = array(
			'AL' => 'Alabama','AK' => 'Alaska','AZ' => 'Arizona','AR' => 'Arkansas','CA' => 'California','CO' => 'Colorado','CT' => 'Connecticut','DE' => 'Delaware','FL' => 'Florida','GA' => 'Georgia',
			'HI' => 'Hawaii','ID' => 'Idaho','IL' => 'Illinois','IN' => 'Indiana','IA' => 'Iowa','KS' => 'Kansas','KY' => 'Kentucky','LA' => 'Louisiana','ME' => 'Maine','MD' => 'Maryland',
			'MA' => 'Massachusetts','MI' => 'Michigan','MN' => 'Minnesota','MS' => 'Mississippi','MO' => 'Missouri','MT' => 'Montana','NE' => 'Nebraska','NV' => 'Nevada','NH' => 'New Hampshire','NJ' => 'New Jersey',
			'NM' => 'New Mexico','NY' => 'New York','NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio','OK' => 'Oklahoma','OR' => 'Oregon','PA' => 'Pennsylvania','RI' => 'Rhode Island','SC' => 'South Carolina',
			'SD' => 'South Dakota','TN' => 'Tennessee','TX' => 'Texas','UT' => 'Utah','VT' => 'Vermont','VA' => 'Virginia','WA' => 'Washington','WV' => 'West Virginia','WI' => 'Wisconsin','WY' => 'Wyoming',
			'DC' => 'District of Columbia',
		);
		$state_full = isset( $states[ $code ] ) ? $states[ $code ] : $state;
	}
	// Type from category terms, excluding generic ones
	$type = '';
	$terms = get_the_terms( $post_id, 'swmw_result_category' );
	if ( $terms && ! is_wp_error( $terms ) ) {
		foreach ( $terms as $t ) {
			$name = isset( $t->name ) ? (string) $t->name : '';
			$slug = isset( $t->slug ) ? (string) $t->slug : '';
			if ( $name === '' ) {
				continue;
			}
			$lower = strtolower( $name );
			// Skip common generic/status terms; keep the first non-generic as "type"
			if ( in_array( $lower, array( 'verdict', 'settlement', 'featured' ), true ) ) {
				continue;
			}
			$type = $name;
			break;
		}
	}
	$html = '';
	if ( $occupation !== '' ) {
		$html .= '<strong>' . esc_html( $occupation ) . '</strong>';
	}
	if ( $state_full !== '' ) {
		$html .= ( $html !== '' ? ' ' : '' ) . 'in ' . esc_html( $state_full );
	}
	if ( $type !== '' ) {
		$html .= ' — ' . esc_html( $type );
	}
	return $html;
}

/**
 * Heading: Occupation only (no state/type)
 */
function swmw_law_format_result_heading_occupation( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	$occupation = function_exists( 'get_field' ) ? (string) get_field( 'result_occupation', $post_id ) : (string) get_post_meta( $post_id, 'result_occupation', true );
	$diagnosis  = function_exists( 'get_field' ) ? (string) get_field( 'result_liability_text', $post_id ) : (string) get_post_meta( $post_id, 'result_liability_text', true );
	// Heading: Occupation with Diagnosis (no state here)
	if ( $occupation !== '' && $diagnosis !== '' ) {
		return $occupation . ' with ' . $diagnosis;
	}
	if ( $occupation !== '' ) {
		return $occupation;
	}
	return $diagnosis;
}

/**
 * Subtext: Full state name only (SEO); author description prints separately by template.
 */
function swmw_law_format_result_subtext( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	$state = function_exists( 'get_field' ) ? (string) get_field( 'result_state', $post_id ) : (string) get_post_meta( $post_id, 'result_state', true );
	if ( $state === '' ) {
		return '';
	}
	$code = strtoupper( trim( $state ) );
	$states = array(
		'AL' => 'Alabama','AK' => 'Alaska','AZ' => 'Arizona','AR' => 'Arkansas','CA' => 'California','CO' => 'Colorado','CT' => 'Connecticut','DE' => 'Delaware','FL' => 'Florida','GA' => 'Georgia',
		'HI' => 'Hawaii','ID' => 'Idaho','IL' => 'Illinois','IN' => 'Indiana','IA' => 'Iowa','KS' => 'Kansas','KY' => 'Kentucky','LA' => 'Louisiana','ME' => 'Maine','MD' => 'Maryland',
		'MA' => 'Massachusetts','MI' => 'Michigan','MN' => 'Minnesota','MS' => 'Mississippi','MO' => 'Missouri','MT' => 'Montana','NE' => 'Nebraska','NV' => 'Nevada','NH' => 'New Hampshire','NJ' => 'New Jersey',
		'NM' => 'New Mexico','NY' => 'New York','NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio','OK' => 'Oklahoma','OR' => 'Oregon','PA' => 'Pennsylvania','RI' => 'Rhode Island','SC' => 'South Carolina',
		'SD' => 'South Dakota','TN' => 'Tennessee','TX' => 'Texas','UT' => 'Utah','VT' => 'Vermont','VA' => 'Virginia','WA' => 'Washington','WV' => 'West Virginia','WI' => 'Wisconsin','WY' => 'Wyoming',
		'DC' => 'District of Columbia',
	);
	return isset( $states[ $code ] ) ? $states[ $code ] : $state;
}

/**
 * Format a Result's context line from fields:
 * Occupation – Liability in STATE
 * Falls back to legacy 'result_secondary_description' if needed.
 */
function swmw_law_format_result_context( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( ! $post_id ) {
		return '';
	}
	$occupation = function_exists( 'get_field' ) ? (string) get_field( 'result_occupation', $post_id ) : (string) get_post_meta( $post_id, 'result_occupation', true );
	$liability  = '';
	if ( function_exists( 'get_field' ) ) {
		$liability = (string) get_field( 'result_liability_text', $post_id );
		if ( $liability === '' ) {
			$liability = (string) get_field( 'result_liability', $post_id ); // legacy taxonomy field name
		}
	}
	if ( $liability === '' ) {
		// Try primary term name if not set in field.
		$terms = get_the_terms( $post_id, 'swmw_result_category' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$liability = $terms[0]->name;
		}
	}
	$state = function_exists( 'get_field' ) ? (string) get_field( 'result_state', $post_id ) : (string) get_post_meta( $post_id, 'result_state', true );
	$state_full = '';
	if ( $state !== '' ) {
		$code = strtoupper( trim( $state ) );
		$states = array(
			'AL' => 'Alabama','AK' => 'Alaska','AZ' => 'Arizona','AR' => 'Arkansas','CA' => 'California','CO' => 'Colorado','CT' => 'Connecticut','DE' => 'Delaware','FL' => 'Florida','GA' => 'Georgia',
			'HI' => 'Hawaii','ID' => 'Idaho','IL' => 'Illinois','IN' => 'Indiana','IA' => 'Iowa','KS' => 'Kansas','KY' => 'Kentucky','LA' => 'Louisiana','ME' => 'Maine','MD' => 'Maryland',
			'MA' => 'Massachusetts','MI' => 'Michigan','MN' => 'Minnesota','MS' => 'Mississippi','MO' => 'Missouri','MT' => 'Montana','NE' => 'Nebraska','NV' => 'Nevada','NH' => 'New Hampshire','NJ' => 'New Jersey',
			'NM' => 'New Mexico','NY' => 'New York','NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio','OK' => 'Oklahoma','OR' => 'Oregon','PA' => 'Pennsylvania','RI' => 'Rhode Island','SC' => 'South Carolina',
			'SD' => 'South Dakota','TN' => 'Tennessee','TX' => 'Texas','UT' => 'Utah','VT' => 'Vermont','VA' => 'Virginia','WA' => 'Washington','WV' => 'West Virginia','WI' => 'Wisconsin','WY' => 'Wyoming',
			'DC' => 'District of Columbia',
		);
		$state_full = isset( $states[ $code ] ) ? $states[ $code ] : $state;
	}

	$parts = array();
	if ( $occupation !== '' ) {
		$parts[] = $occupation;
	}
	if ( $liability !== '' ) {
		$parts[] = $liability;
	}
	$out = '';
	if ( ! empty( $parts ) ) {
		$out = implode( ' – ', $parts );
		if ( $state_full !== '' ) {
			$out .= ' in ' . $state_full;
		}
	}
	if ( $out === '' ) {
		$out = function_exists( 'get_field' ) ? (string) get_field( 'result_secondary_description', $post_id ) : (string) get_post_meta( $post_id, 'result_secondary_description', true );
	}
	return $out;
}

/**
 * TEMPORARY: Results CSV Importer (remove after use)
 *
 * Adds a simple admin page under Tools to upload a CSV and map/update existing Results.
 * Expected header columns (case-insensitive, any order). Supports two modes:
 * A) Direct fields:
 *    - title (used to find an existing Result post)
 *    - result_amount
 *    - result_secondary_description
 *    - category (taxonomy: swmw_result_category - single value)
 *    - status (taxonomy: swmw_result_status - e.g., "featured")
 * B) CSV like provided (we'll generate a title if missing):
 *    - liability   (mapped to category swmw_result_category)
 *    - total recovered (mapped to result_amount)
 *    - state       (used in secondary description)
 *    - occupation  (used in secondary description)
 *
 * Notes:
 * - Only updates existing Results matched by title.
 * - Does NOT create new posts.
 * - Also updates numeric meta 'result_amount_num' using the parser.
 */
function swmw_law_add_results_csv_importer_menu() {
	add_management_page(
		'Results CSV Importer (Temp)',
		'Results CSV Importer (Temp)',
		'manage_options',
		'swmw-results-csv-importer',
		__NAMESPACE__ . '\swmw_law_render_results_csv_importer_page'
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\swmw_law_add_results_csv_importer_menu' );

function swmw_law_render_results_csv_importer_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'swmw-law' ) );
	}

	$did_process = false;
	$summary     = array(
		'rows_total'     => 0,
		'rows_updated'   => 0,
		'rows_skipped'   => 0,
		'not_found'      => 0,
		'errors'         => array(),
	);

	if ( isset( $_POST['swmw_results_csv_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['swmw_results_csv_nonce'] ) ), 'swmw_results_csv' ) ) {
		$allow_create = isset( $_POST['swmw_results_allow_create'] ) && $_POST['swmw_results_allow_create'] === '1';
		$remote_url   = isset( $_POST['swmw_results_csv_url'] ) ? esc_url_raw( wp_unslash( $_POST['swmw_results_csv_url'] ) ) : '';

		// Option A: URL provided (e.g., Google Sheets "Publish to web" CSV link)
		if ( $remote_url ) {
			if ( ! wp_http_validate_url( $remote_url ) ) {
				$summary['errors'][] = 'Invalid CSV URL.';
			} else {
				$response = wp_remote_get(
					$remote_url,
					array(
						'timeout' => 30,
					)
				);
				if ( is_wp_error( $response ) ) {
					$summary['errors'][] = 'Failed fetching CSV URL: ' . $response->get_error_message();
				} else {
					$code = wp_remote_retrieve_response_code( $response );
					$body = wp_remote_retrieve_body( $response );
					if ( (int) $code !== 200 || $body === '' ) {
						$summary['errors'][] = 'CSV URL returned no data (HTTP ' . (int) $code . ').';
					} else {
						// Stream body into a temp handle for fgetcsv
						$fh = fopen( 'php://temp', 'r+' );
						if ( $fh ) {
							fwrite( $fh, $body );
							rewind( $fh );
							// Try to strip BOM.
							$first_bytes = fread( $fh, 3 );
							if ( $first_bytes !== "\xEF\xBB\xBF" ) {
                                rewind( $fh );
                            }
							$header = fgetcsv( $fh );
							if ( is_array( $header ) ) {
								$map = array();
								foreach ( $header as $idx => $col ) {
									$key_raw     = strtolower( trim( (string) $col ) );
									$key_raw     = str_replace( array( ' ', '-', '__' ), array( '_', '_', '_' ), $key_raw );
									$key_raw     = preg_replace( '/[()]/', '', $key_raw ); // remove parentheses
									$key         = $key_raw;
									if ( $key === 'total_recovered' || $key === 'totalrecovered' ) {
										$key = 'result_amount';
									} elseif ( $key === 'total_recovered_listed_on_website' || $key === 'totalrecovered_listed_on_website' ) {
										$key = 'result_amount_display';
									} elseif ( $key === 'liability' ) {
										$key = 'result_liability_text';
									} elseif ( $key === 'type' || $key === 'type_of_case' || $key === 'case_type' ) {
										$key = 'result_category_type'; // future category hookup
									} elseif ( in_array( $key, array( 'disposition', 'verdict_settlement', 'verdict_or_settlement' ), true ) ) {
										$key = 'result_category_disposition'; // map to taxonomy category
									}
									$map[ $key ] = $idx;
								}
								while ( ( $row = fgetcsv( $fh ) ) !== false ) {
									$summary['rows_total']++;
									// Determine or generate title
									$title_raw = '';
									$explicit_title = isset( $map['title'] );
									if ( $explicit_title ) {
										$title_raw = isset( $row[ $map['title'] ] ) ? trim( (string) $row[ $map['title'] ] ) : '';
									}
									$category_from_liability = '';
									if ( isset( $map['category'] ) && isset( $row[ $map['category'] ] ) ) {
										$category_from_liability = trim( (string) $row[ $map['category'] ] );
									}
									$state_val = ( isset( $map['state'] ) && isset( $row[ $map['state'] ] ) ) ? trim( (string) $row[ $map['state'] ] ) : '';
									$occupation_val = ( isset( $map['occupation'] ) && isset( $row[ $map['occupation'] ] ) ) ? trim( (string) $row[ $map['occupation'] ] ) : '';
									if ( $title_raw === '' ) {
										// Prefer: STATE – Amount – Occupation
										$amount_val = ( isset( $map['result_amount'] ) && isset( $row[ $map['result_amount'] ] ) ) ? trim( (string) $row[ $map['result_amount'] ] ) : '';
										$primary_parts = array();
										if ( $state_val !== '' ) {
											$primary_parts[] = strtoupper( $state_val );
										}
										if ( $amount_val !== '' ) {
											$primary_parts[] = $amount_val;
										}
										if ( $occupation_val !== '' ) {
											$primary_parts[] = $occupation_val;
										}
										if ( ! empty( $primary_parts ) ) {
											$title_raw = implode( ' – ', $primary_parts );
										}
										// Fallback: Liability – Occupation (STATE)
										if ( $title_raw === '' ) {
											$parts = array();
											if ( $category_from_liability !== '' ) {
												$parts[] = $category_from_liability;
											}
											if ( $occupation_val !== '' ) {
												$parts[] = $occupation_val;
											}
											if ( ! empty( $parts ) ) {
												$title_raw = implode( ' – ', $parts );
												if ( $state_val !== '' ) {
													$title_raw .= ' (' . strtoupper( $state_val ) . ')';
												}
											}
										}
									}
									if ( $title_raw === '' ) {
										$summary['rows_skipped']++;
										continue;
									}
									$found = null;
									// Try to match an existing post by generated or explicit title (via slug).
									if ( $title_raw !== '' ) {
										$found = swmw_law_find_result_by_title( $title_raw );
									}
									if ( ! $found && $allow_create ) {
										// Ensure uniqueness: if title exists, append liability or a numeric suffix
										$unique_title = $title_raw;
										$attempts = 0;
										while ( swmw_law_find_result_by_title( $unique_title ) && $attempts < 5 ) {
											$suffix = '';
											if ( $category_from_liability !== '' && strpos( $unique_title, $category_from_liability ) === false ) {
												$suffix = ' – ' . $category_from_liability;
											} else {
												$suffix = ' – ' . ( $attempts + 2 );
											}
											$unique_title = $title_raw . $suffix;
											$attempts++;
										}
										$new_id = wp_insert_post(
											array(
												'post_type'   => 'swmw_result',
												'post_title'  => $unique_title,
												'post_status' => 'publish',
											),
											true
										);
										if ( is_wp_error( $new_id ) ) {
											$summary['errors'][] = 'Failed to create: ' . $title_raw . ' - ' . $new_id->get_error_message();
											$summary['rows_skipped']++;
											continue;
										}
										$found = get_post( $new_id );
									}
									if ( ! $found ) {
										$summary['not_found']++;
										continue;
									}
									$did_update = false;
									// Amount
									if ( isset( $map['result_amount'] ) && isset( $row[ $map['result_amount'] ] ) ) {
										$val = trim( (string) $row[ $map['result_amount'] ] );
										if ( $val !== '' ) {
											if ( function_exists( 'update_field' ) ) {
												update_field( 'result_amount', $val, $found->ID );
											} else {
												update_post_meta( $found->ID, 'result_amount', $val );
											}
											if ( function_exists( __NAMESPACE__ . '\swmw_law_parse_amount_to_number' ) ) {
												$num = swmw_law_parse_amount_to_number( $val );
												update_post_meta( $found->ID, 'result_amount_num', $num );
											}
											$did_update = true;
										}
									}
									// Liability text (diagnosis)
									if ( isset( $map['result_liability_text'] ) && isset( $row[ $map['result_liability_text'] ] ) ) {
										$val = trim( (string) $row[ $map['result_liability_text'] ] );
										if ( $val !== '' ) {
											if ( function_exists( 'update_field' ) ) {
												update_field( 'result_liability_text', $val, $found->ID );
											} else {
												update_post_meta( $found->ID, 'result_liability_text', $val );
											}
											$did_update = true;
										}
									}
									// Category hookups via 'type' and 'disposition'
									$categories_to_set = array();
									if ( isset( $map['result_category_type'] ) && isset( $row[ $map['result_category_type'] ] ) ) {
										$val = trim( (string) $row[ $map['result_category_type'] ] );
										if ( $val !== '' ) {
											$categories_to_set[] = $val;
										}
									}
									if ( isset( $map['result_category_disposition'] ) && isset( $row[ $map['result_category_disposition'] ] ) ) {
										$val = trim( (string) $row[ $map['result_category_disposition'] ] );
										if ( $val !== '' ) {
											$categories_to_set[] = $val;
										}
									}
									if ( ! empty( $categories_to_set ) ) {
										// Ensure terms exist; create if missing.
										$final_terms = array();
										foreach ( $categories_to_set as $cat_name ) {
											$term = term_exists( $cat_name, 'swmw_result_category' );
											if ( 0 === $term || null === $term ) {
												$created = wp_insert_term( $cat_name, 'swmw_result_category' );
												if ( ! is_wp_error( $created ) && isset( $created['term_id'] ) ) {
													$final_terms[] = (int) $created['term_id'];
												}
											} elseif ( is_array( $term ) && isset( $term['term_id'] ) ) {
												$final_terms[] = (int) $term['term_id'];
											}
										}
										if ( ! empty( $final_terms ) ) {
											wp_set_object_terms( $found->ID, $final_terms, 'swmw_result_category', false );
											$did_update = true;
										}
									}
									// State/Occupation structured fields
									if ( $state_val !== '' ) {
										if ( function_exists( 'update_field' ) ) {
											update_field( 'result_state', $state_val, $found->ID );
										} else {
											update_post_meta( $found->ID, 'result_state', $state_val );
										}
										$did_update = true;
									}
									if ( $occupation_val !== '' ) {
										if ( function_exists( 'update_field' ) ) {
											update_field( 'result_occupation', $occupation_val, $found->ID );
										} else {
											update_post_meta( $found->ID, 'result_occupation', $occupation_val );
										}
										$did_update = true;
									}
									// No auto-generation for secondary description; optional author-provided only.
									if ( $did_update ) {
										$summary['rows_updated']++;
									} else {
										$summary['rows_skipped']++;
									}
								}
								$did_process = true;
							} else {
								$summary['errors'][] = 'Could not read header row from URL.';
							}
							fclose( $fh );
						} else {
							$summary['errors'][] = 'Unable to open temp stream.';
						}
					}
				}
			}
		// Option B: Local file upload
		} elseif ( ! empty( $_FILES['swmw_results_csv_file']['tmp_name'] ) && is_uploaded_file( $_FILES['swmw_results_csv_file']['tmp_name'] ) ) {
			$tmp = $_FILES['swmw_results_csv_file']['tmp_name'];
			$fh  = fopen( $tmp, 'r' );
			if ( $fh !== false ) {
				// Try to strip BOM.
				$first_bytes = fread( $fh, 3 );
				if ( $first_bytes !== "\xEF\xBB\xBF" ) {
					rewind( $fh );
				}
				$header = fgetcsv( $fh );
				if ( is_array( $header ) ) {
					$map = array();
					foreach ( $header as $idx => $col ) {
						$key_raw     = strtolower( trim( (string) $col ) );
						$key_raw     = str_replace( array( ' ', '-', '__' ), array( '_', '_', '_' ), $key_raw );
						$key_raw     = preg_replace( '/[()]/', '', $key_raw ); // remove parentheses
						// Normalize common header names
						$key         = $key_raw;
						if ( $key === 'total_recovered' || $key === 'totalrecovered' ) {
							$key = 'result_amount';
						} elseif ( $key === 'total_recovered_listed_on_website' || $key === 'totalrecovered_listed_on_website' ) {
							$key = 'result_amount_display';
						} elseif ( $key === 'liability' ) {
							$key = 'result_liability_text';
						} elseif ( $key === 'type' || $key === 'type_of_case' || $key === 'case_type' ) {
							$key = 'result_category_type'; // future category hookup
						} elseif ( in_array( $key, array( 'disposition', 'verdict_settlement', 'verdict_or_settlement' ), true ) ) {
							$key = 'result_category_disposition'; // map to taxonomy category
						}
						$map[ $key ] = $idx;
					}
					while ( ( $row = fgetcsv( $fh ) ) !== false ) {
						$summary['rows_total']++;
						// Determine or generate title
						$title_raw = '';
						if ( isset( $map['title'] ) ) {
							$title_raw = isset( $row[ $map['title'] ] ) ? trim( (string) $row[ $map['title'] ] ) : '';
						}
						$category_from_liability = '';
						if ( isset( $map['category'] ) && isset( $row[ $map['category'] ] ) ) {
							$category_from_liability = trim( (string) $row[ $map['category'] ] );
						}
						$state_val = ( isset( $map['state'] ) && isset( $row[ $map['state'] ] ) ) ? trim( (string) $row[ $map['state'] ] ) : '';
						$occupation_val = ( isset( $map['occupation'] ) && isset( $row[ $map['occupation'] ] ) ) ? trim( (string) $row[ $map['occupation'] ] ) : '';
						if ( $title_raw === '' ) {
							// Prefer: Amount – State – Occupation
							$amount_val = ( isset( $map['result_amount'] ) && isset( $row[ $map['result_amount'] ] ) ) ? trim( (string) $row[ $map['result_amount'] ] ) : '';
							// Map state code to full name if possible
							$state_full = '';
							if ( $state_val !== '' ) {
								$code = strtoupper( $state_val );
								$states = array(
									'AL' => 'Alabama','AK' => 'Alaska','AZ' => 'Arizona','AR' => 'Arkansas','CA' => 'California','CO' => 'Colorado','CT' => 'Connecticut','DE' => 'Delaware','FL' => 'Florida','GA' => 'Georgia',
									'HI' => 'Hawaii','ID' => 'Idaho','IL' => 'Illinois','IN' => 'Indiana','IA' => 'Iowa','KS' => 'Kansas','KY' => 'Kentucky','LA' => 'Louisiana','ME' => 'Maine','MD' => 'Maryland',
									'MA' => 'Massachusetts','MI' => 'Michigan','MN' => 'Minnesota','MS' => 'Mississippi','MO' => 'Missouri','MT' => 'Montana','NE' => 'Nebraska','NV' => 'Nevada','NH' => 'New Hampshire','NJ' => 'New Jersey',
									'NM' => 'New Mexico','NY' => 'New York','NC' => 'North Carolina','ND' => 'North Dakota','OH' => 'Ohio','OK' => 'Oklahoma','OR' => 'Oregon','PA' => 'Pennsylvania','RI' => 'Rhode Island','SC' => 'South Carolina',
									'SD' => 'South Dakota','TN' => 'Tennessee','TX' => 'Texas','UT' => 'Utah','VT' => 'Vermont','VA' => 'Virginia','WA' => 'Washington','WV' => 'West Virginia','WI' => 'Wisconsin','WY' => 'Wyoming',
									'DC' => 'District of Columbia',
								);
								$state_full = isset( $states[ $code ] ) ? $states[ $code ] : $state_val;
							}
							$primary_parts = array();
							if ( $amount_val !== '' ) {
								$primary_parts[] = $amount_val;
							}
							if ( $state_full !== '' ) {
								$primary_parts[] = $state_full;
							}
							if ( $occupation_val !== '' ) {
								$primary_parts[] = $occupation_val;
							}
							if ( ! empty( $primary_parts ) ) {
								$title_raw = implode( ' – ', $primary_parts );
							}
							// Fallback: Liability – Occupation (STATE)
							if ( $title_raw === '' ) {
								$parts = array();
								if ( $category_from_liability !== '' ) {
									$parts[] = $category_from_liability;
								}
								if ( $occupation_val !== '' ) {
									$parts[] = $occupation_val;
								}
								if ( ! empty( $parts ) ) {
									$title_raw = implode( ' – ', $parts );
									if ( $state_val !== '' ) {
										$title_raw .= ' (' . strtoupper( $state_val ) . ')';
									}
								}
							}
						}
						// If still no title, skip
						if ( $title_raw === '' ) {
							$summary['rows_skipped']++;
							continue;
						}

						// Find existing Result by exact title only when an explicit title column was provided.
						$found = null;
						$explicit_title = isset( $map['title'] );
						if ( $title_raw !== '' ) {
							$found = swmw_law_find_result_by_title( $title_raw );
						}
						// Create if missing and allowed
						if ( ! $found && $allow_create ) {
							// Ensure uniqueness: if title exists, append liability or a numeric suffix
							$unique_title = $title_raw;
							$attempts = 0;
							while ( swmw_law_find_result_by_title( $unique_title ) && $attempts < 5 ) {
								$suffix = '';
								if ( $category_from_liability !== '' && strpos( $unique_title, $category_from_liability ) === false ) {
									$suffix = ' – ' . $category_from_liability;
								} else {
									$suffix = ' – ' . ( $attempts + 2 );
								}
								$unique_title = $title_raw . $suffix;
								$attempts++;
							}
							$new_id = wp_insert_post(
								array(
									'post_type'   => 'swmw_result',
									'post_title'  => $unique_title,
									'post_status' => 'publish',
								),
								true
							);
							if ( is_wp_error( $new_id ) ) {
								$summary['errors'][] = 'Failed to create: ' . $title_raw . ' - ' . $new_id->get_error_message();
								$summary['rows_skipped']++;
								continue;
							}
							$found = get_post( $new_id );
						}
						if ( ! $found ) {
							$summary['not_found']++;
							continue;
						}

						$did_update = false;

						// Amount
						if ( isset( $map['result_amount'] ) && isset( $row[ $map['result_amount'] ] ) ) {
							$val = trim( (string) $row[ $map['result_amount'] ] );
							if ( $val !== '' ) {
								if ( function_exists( 'update_field' ) ) {
									update_field( 'result_amount', $val, $found->ID );
								} else {
									update_post_meta( $found->ID, 'result_amount', $val );
								}
								// Update numeric meta for ordering
								if ( function_exists( __NAMESPACE__ . '\swmw_law_parse_amount_to_number' ) ) {
									$num = swmw_law_parse_amount_to_number( $val );
									update_post_meta( $found->ID, 'result_amount_num', $num );
								}
								$did_update = true;
							}
						}

						// Display override amount
						if ( isset( $map['result_amount_display'] ) && isset( $row[ $map['result_amount_display'] ] ) ) {
							$val = trim( (string) $row[ $map['result_amount_display'] ] );
							if ( $val !== '' ) {
								if ( function_exists( 'update_field' ) ) {
									update_field( 'result_amount_display', $val, $found->ID );
								} else {
									update_post_meta( $found->ID, 'result_amount_display', $val );
								}
								$did_update = true;
							}
						}

						// Secondary Description
						if ( isset( $map['result_secondary_description'] ) && isset( $row[ $map['result_secondary_description'] ] ) ) {
							$val = trim( (string) $row[ $map['result_secondary_description'] ] );
							if ( $val !== '' ) {
								if ( function_exists( 'update_field' ) ) {
									update_field( 'result_secondary_description', $val, $found->ID );
								} else {
									update_post_meta( $found->ID, 'result_secondary_description', $val );
								}
								$did_update = true;
							}
						}

						// Liability text (diagnosis) from 'liability' column
						if ( isset( $map['result_liability_text'] ) && isset( $row[ $map['result_liability_text'] ] ) ) {
							$val = trim( (string) $row[ $map['result_liability_text'] ] );
							if ( $val !== '' ) {
								if ( function_exists( 'update_field' ) ) {
									update_field( 'result_liability_text', $val, $found->ID );
								} else {
									update_post_meta( $found->ID, 'result_liability_text', $val );
								}
								$did_update = true;
							}
						}

						// Category hookups via 'type' and 'disposition'
						$categories_to_set = array();
						if ( isset( $map['result_category_type'] ) && isset( $row[ $map['result_category_type'] ] ) ) {
							$val = trim( (string) $row[ $map['result_category_type'] ] );
							if ( $val !== '' ) {
								$categories_to_set[] = $val;
							}
						}
						if ( isset( $map['result_category_disposition'] ) && isset( $row[ $map['result_category_disposition'] ] ) ) {
							$val = trim( (string) $row[ $map['result_category_disposition'] ] );
							if ( $val !== '' ) {
								$categories_to_set[] = $val;
							}
						}
						if ( ! empty( $categories_to_set ) ) {
							// Ensure terms exist; create if missing.
							$final_terms = array();
							foreach ( $categories_to_set as $cat_name ) {
								$term = term_exists( $cat_name, 'swmw_result_category' );
								if ( 0 === $term || null === $term ) {
									$created = wp_insert_term( $cat_name, 'swmw_result_category' );
									if ( ! is_wp_error( $created ) && isset( $created['term_id'] ) ) {
										$final_terms[] = (int) $created['term_id'];
									}
								} elseif ( is_array( $term ) && isset( $term['term_id'] ) ) {
									$final_terms[] = (int) $term['term_id'];
								}
							}
							if ( ! empty( $final_terms ) ) {
								wp_set_object_terms( $found->ID, $final_terms, 'swmw_result_category', false );
								$did_update = true;
							}
						}

						// Persist state/occupation fields (new structured fields)
						if ( $state_val !== '' ) {
							if ( function_exists( 'update_field' ) ) {
								update_field( 'result_state', $state_val, $found->ID );
							} else {
								update_post_meta( $found->ID, 'result_state', $state_val );
							}
							$did_update = true;
						}
						if ( $occupation_val !== '' ) {
							if ( function_exists( 'update_field' ) ) {
								update_field( 'result_occupation', $occupation_val, $found->ID );
							} else {
								update_post_meta( $found->ID, 'result_occupation', $occupation_val );
							}
							$did_update = true;
						}

						// No auto-generation for secondary description; optional author-provided only.

						// Status taxonomy (e.g., featured)
						if ( isset( $map['status'] ) && isset( $row[ $map['status'] ] ) ) {
							$val = sanitize_title( trim( (string) $row[ $map['status'] ] ) );
							if ( $val !== '' ) {
								wp_set_object_terms( $found->ID, array( $val ), 'swmw_result_status', false );
								$did_update = true;
							}
						}

						if ( $did_update ) {
							$summary['rows_updated']++;
						} else {
							$summary['rows_skipped']++;
						}
					}
					$did_process = true;
				} else {
					$summary['errors'][] = 'Could not read header row.';
				}
				fclose( $fh );
			} else {
				$summary['errors'][] = 'Unable to open uploaded file.';
			}
		} else {
			$summary['errors'][] = 'No file uploaded or invalid upload.';
		}
	}

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Results CSV Importer (Temporary)', 'swmw-law' ); ?></h1>
		<p><?php esc_html_e( 'Upload a CSV to update/create Results. Recognized columns (any order):', 'swmw-law' ); ?></p>
		<ul style="list-style: disc; padding-left: 1.25rem;">
			<li><?php esc_html_e( 'Direct: title, result_amount, result_secondary_description, category, status', 'swmw-law' ); ?></li>
			<li><?php esc_html_e( 'Or CSV-style: liability (category), total recovered (result_amount), state, occupation', 'swmw-law' ); ?></li>
		</ul>
		<form method="post" enctype="multipart/form-data">
			<?php wp_nonce_field( 'swmw_results_csv', 'swmw_results_csv_nonce' ); ?>
			<table class="form-table" role="presentation">
				<tr>
					<th scope="row"><label for="swmw_results_csv_file"><?php esc_html_e( 'CSV File', 'swmw-law' ); ?></label></th>
					<td><input type="file" id="swmw_results_csv_file" name="swmw_results_csv_file" accept=".csv,text/csv" required /></td>
				</tr>
				<tr>
					<th scope="row"><label for="swmw_results_allow_create"><?php esc_html_e( 'Create posts if not found', 'swmw-law' ); ?></label></th>
					<td>
						<label>
							<input type="checkbox" id="swmw_results_allow_create" name="swmw_results_allow_create" value="1" checked />
							<?php esc_html_e( 'If a Result with the generated/matched title does not exist, create it.', 'swmw-law' ); ?>
						</label>
					</td>
				</tr>
			</table>
			<?php submit_button( __( 'Upload and Import', 'swmw-law' ) ); ?>
		</form>

		<?php if ( $did_process ) : ?>
			<hr />
			<h2><?php esc_html_e( 'Import Summary', 'swmw-law' ); ?></h2>
			<ul>
				<li><?php echo esc_html( 'Rows total: ' . (int) $summary['rows_total'] ); ?></li>
				<li><?php echo esc_html( 'Rows updated: ' . (int) $summary['rows_updated'] ); ?></li>
				<li><?php echo esc_html( 'Rows skipped: ' . (int) $summary['rows_skipped'] ); ?></li>
				<li><?php echo esc_html( 'Not found (by title): ' . (int) $summary['not_found'] ); ?></li>
			</ul>
			<?php if ( ! empty( $summary['errors'] ) ) : ?>
				<div class="notice notice-error">
					<p><strong><?php esc_html_e( 'Errors:', 'swmw-law' ); ?></strong></p>
					<ul>
						<?php foreach ( $summary['errors'] as $err ) : ?>
							<li><?php echo esc_html( $err ); ?></li>
						<?php endforeach; ?>
					</ul>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
	<?php
}
