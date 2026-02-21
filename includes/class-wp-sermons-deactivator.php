<?php
/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @package    WP_Sermons
 */

class WP_Sermons_Deactivator {

	/**
	 * Deactivate the plugin.
	 *
	 * Flush rewrite rules on plugin deactivation.
	 */
	public static function deactivate() {
		// Flush rewrite rules
		flush_rewrite_rules();

		// Note: We don't delete options or custom post type data
		// in case the user wants to reactivate the plugin later.
	}
}
