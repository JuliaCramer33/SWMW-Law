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
 * Register ACF Blocks
 *
 * Looks for block.json files in subdirectories and registers them.
 * Also includes a PHP file from the block's directory if one exists,
 * checking in a specific order (fields.php, block.php, etc.).
 */
function register_acf_blocks() {
	// Get all subdirectories in the current directory.
	$block_dirs = glob( __DIR__ . '/*', GLOB_ONLYDIR );

	foreach ( $block_dirs as $block_dir ) {
		$block_slug = basename( $block_dir );
		// Skip deprecated hero-homepage block; replaced by block patterns.
		if ( 'hero-homepage' === $block_slug ) {
			continue;
		}
		// Only proceed if a block.json file exists.
		if ( file_exists( $block_dir . '/block.json' ) ) {
			register_block_type( $block_dir );

			// After registering, check for optional PHP files to include.
			$php_files_to_check = [
				$block_dir . '/fields.php',
				$block_dir . '/block.php',
				$block_dir . '/' . $block_slug . '.php',
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
add_action( 'acf/init', __NAMESPACE__ . '\register_acf_blocks' );
