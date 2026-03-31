<?php
/**
 * Upgrade routines for version 3.1.5
 *
 * @package Search_Filter_Pro
 */

namespace Search_Filter_Pro\Core\Upgrader;

/**
 * Handles upgrade to version 3.1.5
 */
class Upgrade_3_1_5 {

	/**
	 * Run the upgrade.
	 *
	 * @since 3.1.5
	 */
	public static function upgrade() {
		// Delete option 'license-server' as we no longer need it.
		\Search_Filter\Options::delete( 'license-server' );
	}
}
