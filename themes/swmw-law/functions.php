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

    $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $posts_per_page = 16; // Load 16 attorneys per AJAX request

    $args = [
        'post_type'      => 'attorney',
        'posts_per_page' => $posts_per_page,
        'paged'          => $page,
        'post_status'    => 'publish',
        'meta_key'       => '_attorney_position_priority',
        'orderby'        => 'meta_value_num title',
        'order'          => 'ASC',
    ];

    $attorneys_query = new \WP_Query( $args );

    if ( $attorneys_query->have_posts() ) :
        ob_start();
        while ( $attorneys_query->have_posts() ) :
            $attorneys_query->the_post();
            get_template_part( 'template-parts/content', 'attorney-card' );
        endwhile;
        $html = ob_get_clean();
        wp_send_json_success( ['html' => $html, 'max_pages' => $attorneys_query->max_num_pages, 'current_page' => $page] );
    else :
        wp_send_json_success( ['html' => '', 'max_pages' => $attorneys_query->max_num_pages, 'current_page' => $page] ); 
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
function swmw_law_render_button_icon( $block_content, $block ) {
    if ( isset( $block['attrs']['iconUrl'] ) && ! empty( $block['attrs']['iconUrl'] ) ) {
        $icon_url = esc_url( $block['attrs']['iconUrl'] );
        $icon_alt = isset( $block['attrs']['iconAlt'] ) ? esc_attr( $block['attrs']['iconAlt'] ) : '';

        // Safely find the closing </a> tag and insert the icon before it.
        $closing_tag_pos = strrpos( $block_content, '</a>' );
        if ( false !== $closing_tag_pos ) {
            $icon_html = sprintf(
                '<img src="%s" alt="%s" class="wp-block-button__icon" style="margin-left: 8px; height: 1em; width: auto;" />',
                $icon_url,
                $icon_alt
            );
            $block_content = substr_replace( $block_content, $icon_html, $closing_tag_pos, 0 );
        }
    }

    return $block_content;
}
add_filter( 'render_block_core/button', __NAMESPACE__ . '\swmw_law_render_button_icon', 10, 2 );

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
 * Add custom rewrite rule for blog/newsfeed permalink.
 */
function swmw_law_add_newsfeed_rewrite_rules() {
    // Add custom query var
    add_rewrite_tag( '%newsfeed%', '([^&]+)' );
    
    // Add rewrite rule for newsfeed archive
    add_rewrite_rule( 'newsfeed/?$', 'index.php?newsfeed=1', 'top' );
    
    // Add rewrite rule for newsfeed pagination
    add_rewrite_rule( 'newsfeed/page/([0-9]+)/?$', 'index.php?newsfeed=1&paged=$matches[1]', 'top' );
    
    // Add rewrite rule for newsfeed category pages
    add_rewrite_rule( 'newsfeed/category/([^/]+)/?$', 'index.php?newsfeed=1&category_name=$matches[1]', 'top' );
    
    // Add rewrite rule for newsfeed category pagination
    add_rewrite_rule( 'newsfeed/category/([^/]+)/page/([0-9]+)/?$', 'index.php?newsfeed=1&category_name=$matches[1]&paged=$matches[2]', 'top' );
}
add_action( 'init', __NAMESPACE__ . '\swmw_law_add_newsfeed_rewrite_rules' );

/**
 * Handle newsfeed template redirect.
 */
function swmw_law_handle_newsfeed_template() {
    if ( get_query_var( 'newsfeed' ) ) {
        // Set up the main query to show blog posts
        global $wp_query;
        
        // Get the posts page ID if one is set
        $posts_page_id = get_option( 'page_for_posts' );
        
        if ( $posts_page_id ) {
            // If there's a posts page set, get its template
            $posts_page = get_post( $posts_page_id );
            $wp_query->queried_object = $posts_page;
            $wp_query->queried_object_id = $posts_page_id;
        }
        
        // Set query flags to indicate this is the blog home
        $wp_query->is_home = true;
        $wp_query->is_front_page = false;
        $wp_query->is_singular = false;
        $wp_query->is_page = false;
        
        // Load the home template
        include( get_home_template() );
        exit;
    }
}
add_action( 'template_redirect', __NAMESPACE__ . '\swmw_law_handle_newsfeed_template' );

/**
 * Register custom query vars.
 */
function swmw_law_add_query_vars( $vars ) {
    $vars[] = 'newsfeed';
    return $vars;
}
add_filter( 'query_vars', __NAMESPACE__ . '\swmw_law_add_query_vars' );

/**
 * Note: After adding these rewrite rules, you need to flush permalinks.
 * Go to Settings > Permalinks in WordPress admin and click "Save Changes" to activate the new rules.
 */

/**
 * Add newsfeed link to WordPress admin bar.
 */
function swmw_law_add_newsfeed_admin_bar_link( $wp_admin_bar ) {
    if ( ! is_admin() ) {
        $wp_admin_bar->add_node( [
            'id'    => 'view-newsfeed',
            'title' => 'View Newsfeed',
            'href'  => home_url( '/newsfeed/' ),
        ] );
    }
}
add_action( 'admin_bar_menu', __NAMESPACE__ . '\swmw_law_add_newsfeed_admin_bar_link', 81 );

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
