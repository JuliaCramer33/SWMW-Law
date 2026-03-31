<?php
/**
 * Upgrade routines for version 3.0.6
 *
 * @package Search_Filter_Pro
 */

namespace Search_Filter_Pro\Core\Upgrader;

/**
 * Handles upgrade to version 3.0.6
 */
class Upgrade_3_0_6 {

	/**
	 * Run the upgrade.
	 *
	 * @since 3.0.6
	 */
	public static function upgrade() {

		// We renamed the task meta table version key, so move it.
		$task_meta_table_version = get_option( 'search_filter_taskmeta_table_version' );
		if ( $task_meta_table_version ) {
			update_option( 'search_filter_pro_taskmeta_table_version', $task_meta_table_version );
			delete_option( 'search_filter_taskmeta_table_version' );
		}
	}
}
