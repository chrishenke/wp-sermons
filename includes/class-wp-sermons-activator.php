<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @package    WP_Sermons
 */

class WP_Sermons_Activator {

	/**
	 * Activate the plugin.
	 *
	 * Register custom post type and flush rewrite rules.
	 */
	public static function activate() {
		// Register the custom post type
		require_once WP_SERMONS_PLUGIN_DIR . 'includes/class-wp-sermons-post-type.php';
		$post_type = new WP_Sermons_Post_Type();
		$post_type->register();

		// Flush rewrite rules
		flush_rewrite_rules();

		// Set default options
		self::set_default_options();
	}

	/**
	 * Set default plugin options.
	 */
	private static function set_default_options() {
		$default_options = array(
			'sermons_per_page' => 10,
			'enable_archive'   => true,
			'enable_single'    => true,
		);

		add_option( 'wp_sermons_options', $default_options );
	}
}
