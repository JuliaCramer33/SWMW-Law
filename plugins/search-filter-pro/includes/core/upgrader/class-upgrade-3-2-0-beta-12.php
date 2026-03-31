<?php
/**
 * Upgrade routines for version 3.2.0-beta-12
 *
 * @package Search_Filter_Pro
 */

namespace Search_Filter_Pro\Core\Upgrader;

/**
 * Handles upgrade to version 3.2.0 Beta 12.
 *
 * Drops the old index_cache table. The new cache table will be created
 * automatically when the Cache system is initialized.
 */
class Upgrade_3_2_0_Beta_12 {

	/**
	 * Run the upgrade.
	 *
	 * @since 3.2.0
	 */
	public static function upgrade() {
		self::drop_old_index_cache_table();
	}

	/**
	 * Drop the old index_cache table.
	 *
	 * Uses raw SQL to avoid dependency on old cache classes.
	 * The new unified cache table (sf_cache) replaces this.
	 *
	 * @since 3.2.0
	 */
	private static function drop_old_index_cache_table() {
		global $wpdb;

		$table_name = $wpdb->prefix . 'search_filter_index_cache';

		// Check if table exists.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
		$table_exists = $wpdb->get_var(
			$wpdb->prepare(
				'SHOW TABLES LIKE %s',
				$table_name
			)
		);

		if ( $table_exists ) {
			// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching, WordPress.DB.DirectDatabaseQuery.SchemaChange
			$wpdb->query(
				$wpdb->prepare( 'DROP TABLE IF EXISTS %i', $table_name )
			);
		}

		delete_option( 'search_filter_pro_index_cache_table_version' );
	}
}
