<?php
/**
 * Load legacy block PHP files.
 *
 * @package SWMW_Law
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// This file is for loading legacy PHP-based block files.
// Modern blocks are registered via `block.json` in `includes/blocks/blocks.php`.
// This scans for a `[block-slug].php` file in each block directory and includes it if it exists
// for backwards compatibility.

$block_folders = glob( SWMW_LAW_DIR . 'includes/blocks/*', GLOB_ONLYDIR );

foreach ( $block_folders as $block_folder ) {
	if ( is_dir( $block_folder ) ) {
		$block_slug      = basename( $block_folder );
		$legacy_php_file = $block_folder . '/' . $block_slug . '.php';

		if ( file_exists( $legacy_php_file ) ) {
			require_once $legacy_php_file;
		}
	}
} 
