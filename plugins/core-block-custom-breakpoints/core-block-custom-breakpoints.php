<?php
/**
 * Plugin Name:       Core Block Custom Breakpoints
 * Description:       Extend Core Columns Block with Custom Breakpoints.
 * Requires at least: 6.2
 * Requires PHP:      8.0
 * Version:           1.0.0
 * Author:            Kanahoma
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       core-block-custom-breakpoints
 *
 * @package           create-block
 */

function prefix_blocks_block_init() {

	$build_dir = plugin_dir_path( __FILE__ ) . 'build/blocks';

	$blocks = glob( $build_dir . '/*' );
	$blocks = array_map(
		function ( $block_name_path ) {
			return basename( $block_name_path );
		},
		$blocks
	);

	foreach ( $blocks as $block ) {

		$block_location = $build_dir . '/' . $block;
		if ( ! is_dir( $block_location ) ) {
			continue;
		}
		register_block_type( $block_location );
	}
}
add_action( 'init', 'prefix_blocks_block_init' );

function prefix_enqueue_block_scripts() {

	$index_assets = plugin_dir_path( __FILE__ ) . 'build/index.asset.php';

	if ( file_exists( $index_assets ) ) {
		$assets = require_once $index_assets;
		wp_enqueue_script(
			'prefix-blocks',
			plugin_dir_url( __FILE__ ) . '/build/index.js',
			$assets['dependencies'],
			$assets['version'],
			true
		);
	}
}
add_action( 'enqueue_block_editor_assets', 'prefix_enqueue_block_scripts' );
