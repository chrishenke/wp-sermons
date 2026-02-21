<?php
/**
 * Handle plugin settings page.
 *
 * @package    WP_Sermons
 */

class WP_Sermons_Settings {

	/**
	 * The ID of this plugin.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Initialize the class.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Add settings page to admin menu.
	 */
	public function add_settings_page() {
		add_submenu_page(
			'edit.php?post_type=sermon',
			__( 'Sermon Settings', 'wp-sermons' ),
			__( 'Settings', 'wp-sermons' ),
			'manage_options',
			'wp-sermons-settings',
			array( $this, 'render_settings_page' )
		);
	}

	/**
	 * Register plugin settings.
	 */
	public function register_settings() {
		register_setting(
			'wp_sermons_settings_group',
			'wp_sermons_options',
			array( $this, 'sanitize_settings' )
		);

		// General Settings Section
		add_settings_section(
			'wp_sermons_general_section',
			__( 'General Settings', 'wp-sermons' ),
			array( $this, 'render_general_section' ),
			'wp-sermons-settings'
		);

		// Sermons per page
		add_settings_field(
			'sermons_per_page',
			__( 'Sermons Per Page', 'wp-sermons' ),
			array( $this, 'render_sermons_per_page_field' ),
			'wp-sermons-settings',
			'wp_sermons_general_section'
		);

		// Enable archive
		add_settings_field(
			'enable_archive',
			__( 'Enable Archive Page', 'wp-sermons' ),
			array( $this, 'render_enable_archive_field' ),
			'wp-sermons-settings',
			'wp_sermons_general_section'
		);

		// Enable single
		add_settings_field(
			'enable_single',
			__( 'Enable Single Sermon Pages', 'wp-sermons' ),
			array( $this, 'render_enable_single_field' ),
			'wp-sermons-settings',
			'wp_sermons_general_section'
		);
	}

	/**
	 * Render settings page.
	 */
	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error(
				'wp_sermons_messages',
				'wp_sermons_message',
				__( 'Settings Saved', 'wp-sermons' ),
				'updated'
			);
		}

		settings_errors( 'wp_sermons_messages' );
		?>
		<div class="wrap">
			<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
			<form action="options.php" method="post">
				<?php
				settings_fields( 'wp_sermons_settings_group' );
				do_settings_sections( 'wp-sermons-settings' );
				submit_button( __( 'Save Settings', 'wp-sermons' ) );
				?>
			</form>
		</div>
		<?php
	}

	/**
	 * Render general section description.
	 */
	public function render_general_section() {
		echo '<p>' . esc_html__( 'Configure general settings for the WP Sermons plugin.', 'wp-sermons' ) . '</p>';
	}

	/**
	 * Render sermons per page field.
	 */
	public function render_sermons_per_page_field() {
		$options = get_option( 'wp_sermons_options' );
		$value   = isset( $options['sermons_per_page'] ) ? $options['sermons_per_page'] : 10;
		?>
		<input type="number" 
			   name="wp_sermons_options[sermons_per_page]" 
			   value="<?php echo esc_attr( $value ); ?>" 
			   min="1" 
			   max="100" />
		<p class="description">
			<?php esc_html_e( 'Number of sermons to display per page in archives.', 'wp-sermons' ); ?>
		</p>
		<?php
	}

	/**
	 * Render enable archive field.
	 */
	public function render_enable_archive_field() {
		$options = get_option( 'wp_sermons_options' );
		$value   = isset( $options['enable_archive'] ) ? $options['enable_archive'] : true;
		?>
		<label>
			<input type="checkbox" 
				   name="wp_sermons_options[enable_archive]" 
				   value="1" 
				   <?php checked( $value, true ); ?> />
			<?php esc_html_e( 'Enable sermon archive pages', 'wp-sermons' ); ?>
		</label>
		<?php
	}

	/**
	 * Render enable single field.
	 */
	public function render_enable_single_field() {
		$options = get_option( 'wp_sermons_options' );
		$value   = isset( $options['enable_single'] ) ? $options['enable_single'] : true;
		?>
		<label>
			<input type="checkbox" 
				   name="wp_sermons_options[enable_single]" 
				   value="1" 
				   <?php checked( $value, true ); ?> />
			<?php esc_html_e( 'Enable individual sermon pages', 'wp-sermons' ); ?>
		</label>
		<?php
	}

	/**
	 * Sanitize settings before saving.
	 *
	 * @param array $input The input array.
	 * @return array Sanitized input.
	 */
	public function sanitize_settings( $input ) {
		$sanitized = array();

		if ( isset( $input['sermons_per_page'] ) ) {
			$sanitized['sermons_per_page'] = absint( $input['sermons_per_page'] );
			if ( $sanitized['sermons_per_page'] < 1 ) {
				$sanitized['sermons_per_page'] = 10;
			}
		}

		$sanitized['enable_archive'] = isset( $input['enable_archive'] ) ? true : false;
		$sanitized['enable_single']  = isset( $input['enable_single'] ) ? true : false;

		return $sanitized;
	}
}
