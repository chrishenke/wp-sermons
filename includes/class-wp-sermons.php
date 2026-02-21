<?php
/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * @package    WP_Sermons
 */

class WP_Sermons {

	/**
	 * The loader that's responsible for maintaining and registering all hooks.
	 *
	 * @var WP_Sermons_Loader
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @var string
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {
		$this->version     = WP_SERMONS_VERSION;
		$this->plugin_name = 'wp-sermons';

		$this->load_dependencies();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->register_post_type();
		$this->register_shortcodes();
		$this->register_rest_api();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		// Admin class
		require_once WP_SERMONS_PLUGIN_DIR . 'admin/class-wp-sermons-admin.php';
		require_once WP_SERMONS_PLUGIN_DIR . 'admin/class-wp-sermons-settings.php';

		// Public class
		require_once WP_SERMONS_PLUGIN_DIR . 'public/class-wp-sermons-public.php';

		// Custom post type
		require_once WP_SERMONS_PLUGIN_DIR . 'includes/class-wp-sermons-post-type.php';

		// Shortcodes
		require_once WP_SERMONS_PLUGIN_DIR . 'includes/class-wp-sermons-shortcodes.php';

		// REST API
		require_once WP_SERMONS_PLUGIN_DIR . 'includes/class-wp-sermons-rest-api.php';
	}

	/**
	 * Register all of the hooks related to the admin area functionality.
	 */
	private function define_admin_hooks() {
		$plugin_admin = new WP_Sermons_Admin( $this->get_plugin_name(), $this->get_version() );

		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $plugin_admin, 'enqueue_scripts' ) );

		// Settings page
		$plugin_settings = new WP_Sermons_Settings( $this->get_plugin_name(), $this->get_version() );
		add_action( 'admin_menu', array( $plugin_settings, 'add_settings_page' ) );
		add_action( 'admin_init', array( $plugin_settings, 'register_settings' ) );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality.
	 */
	private function define_public_hooks() {
		$plugin_public = new WP_Sermons_Public( $this->get_plugin_name(), $this->get_version() );

		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $plugin_public, 'enqueue_scripts' ) );
	}

	/**
	 * Register the custom post type.
	 */
	private function register_post_type() {
		$post_type = new WP_Sermons_Post_Type();
		add_action( 'init', array( $post_type, 'register' ) );
	}

	/**
	 * Register shortcodes.
	 */
	private function register_shortcodes() {
		$shortcodes = new WP_Sermons_Shortcodes();
		add_action( 'init', array( $shortcodes, 'register' ) );
	}

	/**
	 * Register REST API endpoints.
	 */
	private function register_rest_api() {
		$rest_api = new WP_Sermons_REST_API();
		add_action( 'rest_api_init', array( $rest_api, 'register_routes' ) );
	}

	/**
	 * Run the loader to execute all of the hooks.
	 */
	public function run() {
		// Plugin is now running with all hooks registered
	}

	/**
	 * The name of the plugin used to uniquely identify it.
	 *
	 * @return string
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return string
	 */
	public function get_version() {
		return $this->version;
	}
}
