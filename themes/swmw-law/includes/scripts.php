<?php
/**
 * Enqueue scripts and styles.
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

/**
 * Enqueue scripts and styles.
 */
function scripts() {
    // Explicitly Enqueue Google Fonts
    wp_enqueue_style( 
        'swmw-law-google-fonts', 
        'https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400;1,700&display=swap',
        [], 
        null // No version number for Google Fonts URL
    );

    // Enqueue main SCSS stylesheet
    wp_enqueue_style(
        'swmw-law-main',
        SWMW_LAW_URI . 'dist/css/style.css',
        ['swmw-law-google-fonts'], // Make dependent on Google Fonts
        SWMW_LAW_VERSION
    );

    // Enqueue Splide library (globally available)
    wp_enqueue_style(
        'splide-css',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css',
        array(),
        '4.1.4'
    );

    // Enqueue Splide JS from CDN
    wp_enqueue_script(
        'splide-js',
        'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js',
        array(),
        '4.1.4',
        true
    );

    // Enqueue main JavaScript file using asset file
    $main_asset_path = SWMW_LAW_DIR . 'dist/js/main.asset.php';
    if ( file_exists( $main_asset_path ) ) {
        $main_asset = require $main_asset_path;
        wp_enqueue_script(
            'swmw-law-main',
            SWMW_LAW_URI . 'dist/js/main.js',
            array_merge($main_asset['dependencies'], ['jquery', 'splide-js']),
            $main_asset['version'],
            true
        );
    } else {
        // Fallback for when asset file doesn't exist
        wp_enqueue_script(
            'swmw-law-main',
            SWMW_LAW_URI . 'dist/js/main.js',
            ['jquery', 'splide-js'],
            SWMW_LAW_VERSION,
            true
        );
    }
    wp_script_add_data('swmw-law-main', 'type', 'module');

    // Localize the script with new data
    wp_localize_script(
        'swmw-law-main',
        'swmwLawData',
        [
            'ajaxUrl' => admin_url( 'admin-ajax.php' ),
            'nonce'   => wp_create_nonce( 'swmw-law-nonce' ),
            'load_more_attorneys_nonce' => wp_create_nonce( 'swmw_law_load_more_attorneys_nonce' )
        ]
    );

    // Conditionally enqueue the tabs script if the block is present on the page.
    $post = get_post();
    if ( $post && has_block( 'acf/tabs', $post->post_content ) ) {
        wp_enqueue_script(
            'swmw-law-tabs',
            get_template_directory_uri() . '/assets/js/blocks/tabs.js',
            array(), // No dependencies
            filemtime( get_template_directory() . '/assets/js/blocks/tabs.js' ),
            true // Load in footer
        );
    }

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\scripts' );

/**
 * Enqueue admin scripts and styles.
 */
function admin_scripts() {
    // Enqueue the admin script using its asset file.
    $admin_asset_path = SWMW_LAW_DIR . 'dist/js/admin.asset.php';
    if ( file_exists( $admin_asset_path ) ) {
        $admin_asset = require $admin_asset_path;
        wp_enqueue_script(
            'swmw-law-admin-script',
            SWMW_LAW_URI . 'dist/js/admin.js',
            $admin_asset['dependencies'],
            $admin_asset['version'],
            true
        );
    } else {
        // Fallback for when asset file doesn't exist
        wp_enqueue_script(
            'swmw-law-admin-script',
            SWMW_LAW_URI . 'dist/js/admin.js',
            [ 'wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n', 'wp-hooks' ],
            SWMW_LAW_VERSION,
            true
        );
    }
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\admin_scripts' ); 

// Register block styles for animations
add_action( 'init', function() {
    $animation_styles = [
        [
            'name'  => 'animate-fade-in',
            'label' => __( 'Fade In', 'swmw-law' ),
        ],
        [
            'name'  => 'animate-slide-up',
            'label' => __( 'Slide Up', 'swmw-law' ),
        ],
        [
            'name'  => 'animate-slide-in-right',
            'label' => __( 'Slide In Right', 'swmw-law' ),
        ],
        [
            'name'  => 'animate-slide-in-left',
            'label' => __( 'Slide In Left', 'swmw-law' ),
        ],
    ];

    $all_blocks = \WP_Block_Type_Registry::get_instance()->get_all_registered();
    foreach ( $all_blocks as $block_name => $block_type ) {
        foreach ( $animation_styles as $style ) {
            register_block_style( $block_name, $style );
        }
    }
} ); 
