<?php
/**
 * Theme setup functions
 *
 * @package SWMW_Law
 */

namespace SWMW_Law;

/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
function setup() {
    // Make theme available for translation.
    // Translations can be filed in the /languages/ directory.
    load_theme_textdomain( 'swmw-law', SWMW_LAW_DIR . '/languages' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );

    // Let WordPress manage the document title.
    add_theme_support( 'title-tag' );

    /*
     * Enable support for Post Thumbnails on posts and pages.
     *
     * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
     */
    add_theme_support( 'post-thumbnails' );

    /*
     * Add support for custom logo.
     *
     * @link https://developer.wordpress.org/themes/functionality/custom-logo/
     */
    add_theme_support( 'custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
        'header-text' => [ 'site-title', 'site-description' ],
    ] );

    /**
     * Add support for wide and full-width block alignments.
     */
    add_theme_support( 'align-wide' );

    // Register navigation menus
    register_nav_menus(
        [
            'primary' => esc_html__( 'Primary Menu', 'swmw-law' ),
            'utility_header' => esc_html__( 'Utility Header Menu', 'swmw-law' ),
            'footer'  => esc_html__( 'Footer Menu', 'swmw-law' ),
        ]
    );

    // Switch default core markup to output valid HTML5.
    add_theme_support(
        'html5',
        [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]
    );

    // Add theme support for selective refresh for widgets.
    add_theme_support( 'customize-selective-refresh-widgets' );

    // Add support for responsive embeds.
    add_theme_support( 'responsive-embeds' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\setup' ); 
