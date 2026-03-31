<?php
/**
 * ACF Blocks Registration
 *
 * @package SWMW_Law
 */

namespace SWMW_Law\Blocks;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register ACF blocks.
 */
function swmw_law_register_acf_blocks() {
	// An array of block folder names to register.
	$blocks = [
		'hero',
		'logo-grid',
		'expandable-card',
		'image-split',
		'results',
		'tabs',
		'tab-panel',
		'testimonials',
		'attorneys',
		'accordion',
		'accordion-panel',
		'jobsites-by-city',
		'hero-dropdown-menu',
	];

	foreach ( $blocks as $block_name ) {
		$block_path = SWMW_LAW_DIR . 'includes/blocks/' . $block_name;
		// Only proceed if a block.json file exists.
		if ( file_exists( $block_path . '/block.json' ) ) {
			register_block_type( $block_path );

			// After registering, check for optional PHP files to include.
			$php_files_to_check = [
				$block_path . '/fields.php',
				$block_path . '/block.php',
				$block_path . '/' . $block_name . '.php',
			];

			foreach ( $php_files_to_check as $php_file ) {
				// Only include the file if it actually exists.
				if ( file_exists( $php_file ) ) {
					require_once $php_file;
					break; // Important: Only include the FIRST file found.
				}
			}
		}
	}
}
add_action( 'acf/init', __NAMESPACE__ . '\swmw_law_register_acf_blocks' );
