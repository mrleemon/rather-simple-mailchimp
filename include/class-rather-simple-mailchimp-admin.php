<?php
/**
 * The admin class for the plugin.
 *
 * @package rather_simple_mailchimp
 */

/**
 * Core class used to implement the admin.
 */
final class Rather_Simple_Mailchimp_Admin {

	/**
	 * Holds the instance of this class.
	 *
	 * @var object $instance
	 */
	private static $instance;

	/**
	 * Returns the instance.
	 *
	 * @return object
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Plugin setup.
	 */
	public function __construct() {

		add_filter( 'plugin_action_links', array( $this, 'add_action_links' ), 10, 2 );

		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	/**
	 * Adds action links.
	 *
	 * @param array  $links  An array of plugin action links.
	 * @param string $file   Path to the plugin file relative to the plugins directory.
	 */
	public function add_action_links( $links, $file ) {
		if ( preg_match( '/rather-simple-mailchimp\.php/i', $file ) ) {
			$links[] = '<a href="' . admin_url( 'options-general.php?page=rather-simple-mailchimp' ) . '">' . __( 'Settings' ) . '</a>';
		}
		return $links;
	}

	/**
	 * Admin menu.
	 */
	public function admin_menu() {
		add_options_page( __( 'Rather Simple Mailchimp', 'rather-simple-mailchimp' ), __( 'Rather Simple Mailchimp', 'rather-simple-mailchimp' ), 'manage_options', 'rather-simple-mailchimp', array( $this, 'options_page' ) );
	}

	/**
	 * Admin init.
	 */
	public function admin_init() {
		register_setting(
			'rsm-settings',
			'rsm_settings',
			array(
				'sanitization_callback' => 'sanitize_text_field',
			)
		);
		add_settings_section( 'api-key-section', __( 'Mailchimp API Key', 'rather-simple-mailchimp' ), null, 'rather-simple-mailchimp' );
		add_settings_field( 'api_key', __( 'API Key', 'rather-simple-mailchimp' ), array( $this, 'api_key_callback' ), 'rather-simple-mailchimp', 'api-key-section', array( 'class' => 'api_key' ) );
	}

	/**
	 * Options page.
	 */
	public function options_page() {
		?>
	<div class="wrap">
		<h2><?php _e( 'Rather Simple Mailchimp', 'rather-simple-mailchimp' ); ?></h2>
		<form action="options.php" method="post">
			<?php settings_fields( 'rsm-settings' ); ?>
			<?php do_settings_sections( 'rather-simple-mailchimp' ); ?>
			<?php submit_button(); ?>
		</form>
	</div>
		<?php
	}

	/**
	 * API Key callback.
	 */
	public function api_key_callback() {
		$settings = (array) get_option( 'rsm_settings' );
		$api_key  = isset( $settings['api_key'] ) ? $settings['api_key'] : '';
		?>
		<input name="rsm_settings[api_key]" type="text" id="rsm_settings[api_key]" value="<?php echo esc_attr( $api_key ); ?>" class="regular-text" />
		<p class="description" id="api-key-description">
		<?php
		printf(
			__( 'To use this plugin, please generate a Mailchimp API key by following the instructions <a href="%s" target="_blank">here</a> and paste it into this field.', 'rather-simple-mailchimp' ),
			'https://mailchimp.com/help/about-api-keys/'
		);
		?>
		</p>
		<?php
	}
}

Rather_Simple_Mailchimp_Admin::get_instance();
