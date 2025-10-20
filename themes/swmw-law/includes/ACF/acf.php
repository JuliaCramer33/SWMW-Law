<?php
/**
 * ACF Integration for SWMW Law Theme
 *
 * @package SWMW_Law
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Include ACF field group definitions.
require_once SWMW_LAW_DIR . 'includes/ACF/field-groups/archive-heroes.php';
require_once SWMW_LAW_DIR . 'includes/ACF/options.php';
require_once SWMW_LAW_DIR . 'includes/ACF/testimonials.php';
require_once SWMW_LAW_DIR . 'includes/ACF/results.php';
require_once SWMW_LAW_DIR . 'includes/ACF/page-settings.php';
require_once SWMW_LAW_DIR . 'includes/ACF/cities.php';
require_once SWMW_LAW_DIR . 'includes/ACF/attorneys.php';
// require_once SWMW_LAW_DIR . 'includes/acf/blocks/angled-image-pair-content-fields.php';

// Add any other ACF related hooks or functions below, if needed. 

// Enqueue admin scripts for ACF
add_action('acf/input/admin_enqueue_scripts', function() {
    // Only enqueue on post types that use our header fields
    $screen = get_current_screen();
    if ($screen && $screen->base === 'post' && $screen->post_type === 'page') {
        wp_enqueue_script(
            'swmw-law-acf-header-preview',
            get_template_directory_uri() . '/includes/ACF/js/header-preview.js',
            ['jquery'],
            SWMW_LAW_VERSION,
            true
        );
    }
}); 
