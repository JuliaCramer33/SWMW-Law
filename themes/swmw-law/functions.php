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
		$tax_query = array(
			array(
				'taxonomy' => 'swmw_result_status',
				'field'    => 'slug',
				'terms'    => 'featured',
				'operator' => 'NOT IN',
			),
		);
		$query->set( 'tax_query', $tax_query );
		$query->set( 'posts_per_page', 12 ); // Show 12 results per page (divisible by 3)
	}
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\swmw_law_non_featured_results_archive_query' );

/**
 * AJAX handler for loading more attorneys.
 */
function swmw_law_load_more_attorneys_handler() {
    // Verify nonce for security
    if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'swmw_law_load_more_attorneys_nonce' ) ) {
        wp_send_json_error( ['message' => 'Invalid security token'], 403 );
        wp_die();
    }

    $posts_per_page = 16; // Load 16 attorneys per AJAX request
    // Support either offset-based or exclude-based pagination (prefer exclude for stability)
    $exclude_ids = [];
    if ( isset( $_POST['exclude'] ) ) {
        $raw = sanitize_text_field( wp_unslash( $_POST['exclude'] ) );
        if ( $raw !== '' ) {
            $exclude_ids = array_filter( array_map( 'intval', explode( ',', $raw ) ) );
        }
    }
    // Fallback offset (still supported for debugging/logs)
    $offset = isset( $_POST['offset'] ) ? max( 0, intval( $_POST['offset'] ) ) : 0;

    $args = [
        'post_type'             => 'attorney',
        'posts_per_page'        => $posts_per_page,
        'offset'                => 0,
        'post_status'           => 'publish',
        // Use shared ordering: Members first, then Start Date ASC, then title/ID
        'attorney_custom_order' => true,
        'suppress_filters'      => false,
        'no_found_rows'         => true,
        'post__not_in'          => $exclude_ids,
    ];

    $attorneys_query = new \WP_Query( $args );

    if ( $attorneys_query->have_posts() ) :
        ob_start();
        while ( $attorneys_query->have_posts() ) :
            $attorneys_query->the_post();
            get_template_part( 'template-parts/content', 'attorney-card' );
        endwhile;
        $html = ob_get_clean();
        // Compute totals and has_more deterministically
        // Determine if more remain: if we returned a full batch, assume more may exist
        $counts          = wp_count_posts( 'attorney' );
        $total_published = isset( $counts->publish ) ? (int) $counts->publish : 0;
        $max_pages       = $posts_per_page > 0 ? (int) ceil( $total_published / $posts_per_page ) : 1;
        $has_more        = ( $attorneys_query->post_count === $posts_per_page );
        wp_send_json_success( [
            'html'         => $html,
            'max_pages'    => $max_pages,
            'current_page' => $page,
            'has_more'     => $has_more,
        ] );
    else :
        $counts          = wp_count_posts( 'attorney' );
        $total_published = isset( $counts->publish ) ? (int) $counts->publish : 0;
        $max_pages       = $posts_per_page > 0 ? (int) ceil( $total_published / $posts_per_page ) : 1;
        $has_more        = $offset < $total_published;
        wp_send_json_success( [
            'html'         => '',
            'max_pages'    => $max_pages,
            'current_page' => $page,
            'has_more'     => $has_more,
        ] ); 
    endif;

    wp_reset_postdata();
    wp_die(); 
}
add_action( 'wp_ajax_load_more_attorneys', __NAMESPACE__ . '\swmw_law_load_more_attorneys_handler' );
add_action( 'wp_ajax_nopriv_load_more_attorneys', __NAMESPACE__ . '\swmw_law_load_more_attorneys_handler' );

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
                <h3 class="result-amount"><?php echo esc_html( get_field( 'result_amount' ) ); ?></h3>
                <h4 class="result-title"><?php the_title(); ?></h4>
                <div class="result-description">
                    <?php the_excerpt(); ?>
                </div>
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
add_action( 'wp_ajax_load_more_results', __NAMESPACE__ . '\swmw_law_load_more_results_handler' );
add_action( 'wp_ajax_nopriv_load_more_results', __NAMESPACE__ . '\swmw_law_load_more_results_handler' );

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
