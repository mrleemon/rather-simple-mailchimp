<?php
/**
 * Plugin Name: Rather Simple Mailchimp
 * Plugin URI:
 * Update URI: false
 * Version: 1.0
 * Requires at least: 6.6
 * Requires PHP: 7.4
 * Author: Oscar Ciutat
 * Author URI: http://oscarciutat.com/code/
 * Text Domain: rather-simple-mailchimp
 * Description: A simple Mailchimp plugin with a block, a shortcode and a widget
 * License: GPLv2 or later
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 *
 * @package rather_simple_mailchimp
 */

/**
 * Core class used to implement the plugin.
 */
class Rather_Simple_Mailchimp {

	/**
	 * Plugin instance.
	 *
	 * @var object $instance
	 */
	protected static $instance = null;

	/**
	 * Access this plugin’s working instance
	 */
	public static function get_instance() {

		if ( ! self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Used for regular plugin work.
	 */
	public function plugin_setup() {

		$this->includes();

		add_action( 'init', array( $this, 'load_language' ) );
		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'wp_ajax_nopriv_subscribe', array( $this, 'form_handler_ajax' ) );
		add_action( 'wp_ajax_subscribe', array( $this, 'form_handler_ajax' ) );

		add_shortcode( 'mailchimp', array( $this, 'render_shortcode' ) );
	}

	/**
	 * Constructor. Intentionally left empty and public.
	 */
	public function __construct() {}

	/**
	 * Includes required core files used in admin and on the frontend
	 */
	protected function includes() {
		require_once 'include/class-rather-simple-mailchimp-widget.php';
		require_once 'include/class-rather-simple-mailchimp-popup-widget.php';
	}

	/**
	 * Loads language
	 */
	public function load_language() {
		load_plugin_textdomain( 'rather-simple-mailchimp', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Registers block
	 *
	 * @throws Error If block is not built.
	 */
	public function register_block() {
		if ( ! function_exists( 'register_block_type' ) ) {
			// The block editor is not active.
			return;
		}

		// Register the block by passing the location of block.json to register_block_type.
		register_block_type(
			__DIR__ . '/build/blocks/mailchimp',
			array(
				'render_callback' => array( $this, 'render_block' ),
			)
		);

		// Load translations.
		$script_handle = generate_block_asset_handle( 'occ/mailchimp', 'editorScript' );
		wp_set_script_translations( $script_handle, 'rather-simple-mailchimp', plugin_dir_path( __FILE__ ) . 'languages' );
	}

	/**
	 * Enqueue scripts
	 */
	public function wp_enqueue_scripts() {
		global $post;
		if ( is_page() && has_shortcode( $post->post_content, 'mailchimp' ) ) {
			wp_enqueue_style(
				'rsm-style',
				plugins_url( '/style.css', __FILE__ ),
				array(),
				filemtime( plugin_dir_path( __FILE__ ) . 'style.css' )
			);
			wp_enqueue_script(
				'rsm-subscribe',
				plugins_url( '/assets/js/subscribe.js', __FILE__ ),
				array(),
				filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/subscribe.js' ),
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
			wp_localize_script(
				'rsm-subscribe',
				'ajax_var',
				array(
					'url'    => admin_url( 'admin-ajax.php' ),
					'nonce'  => wp_create_nonce( 'rsm-nonce' ),
					'action' => 'subscribe',
				)
			);
			wp_enqueue_script(
				'rsm-frontend',
				plugins_url( '/assets/js/frontend.js', __FILE__ ),
				array( 'rsm-subscribe' ),
				filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/frontend.js' ),
				array(
					'in_footer' => true,
					'strategy'  => 'defer',
				)
			);
		}
	}

	/**
	 * Render shortcode
	 *
	 * @param array $attr  The shortcode attributes.
	 */
	public function render_shortcode( $attr ) {
		$html = $this->shortcode_atts( $attr );
		return $html;
	}

	/**
	 * Shortcode attributes
	 *
	 * @param array $attr  The shortcode attributes.
	 */
	public function shortcode_atts( $attr ) {
		$atts = shortcode_atts(
			array(
				'id'          => '',
				'first_name'  => 'false',
				'last_name'   => 'false',
				'placeholder' => 'false',
			),
			$attr,
			'mailchimp'
		);

		$atts['first_name']  = filter_var( $atts['first_name'], FILTER_VALIDATE_BOOLEAN );
		$atts['last_name']   = filter_var( $atts['last_name'], FILTER_VALIDATE_BOOLEAN );
		$atts['placeholder'] = filter_var( $atts['placeholder'], FILTER_VALIDATE_BOOLEAN );

		$html = '<!-- Begin Mailchimp Signup Form -->
		  <div class="mc-embed-signup">
			<form method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
			<input type="hidden" value="' . esc_attr( $atts['id'] ) . '" name="ID">';

		if ( $atts['first_name'] ) {
			$placeholder = $atts['placeholder'] ? ' placeholder="' . __( 'First Name', 'rather-simple-mailchimp' ) . '"' : '';
			$html       .= '<div class="mc-field-group">
					<label for="mce-FNAME">' . __( 'First Name', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
					<input type="text" value="" name="FNAME" class="required fname" id="mce-FNAME" required ' . $placeholder . '>
				</div>';
		}

		if ( $atts['last_name'] ) {
			$placeholder = $atts['placeholder'] ? ' placeholder="' . __( 'Last Name', 'rather-simple-mailchimp' ) . '"' : '';
			$html       .= '<div class="mc-field-group">
					<label for="mce-LNAME">' . __( 'Last Name', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
					<input type="text" value="" name="LNAME" class="required lname" id="mce-LNAME" required ' . $placeholder . '>
				</div>';
		}

		$placeholder = $atts['placeholder'] ? 'placeholder="' . __( 'Email', 'rather-simple-mailchimp' ) . '"' : '';
		$html       .= '<div class="mc-field-group">
					<label for="mce-EMAIL">' . __( 'Email', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
					<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" required ' . $placeholder . '>
				</div>';

		$html .= '<div class="mc-field-group">
				<input type="checkbox" value="1" name="PRIVACY" id="mc-privacy-policy" required> <label>' . sprintf( __( 'I have read and agree to the %s.', 'rather-simple-mailchimp' ), get_the_privacy_policy_link() ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
			</div>';

		$html .= '<div class="mc-submit-button">
					<input type="submit" value="' . __( 'Subscribe', 'rather-simple-mailchimp' ) . '" name="subscribe" id="mc-embedded-subscribe" class="button wp-element-button">
				</div>
				<div class="mce-responses clear">
					<div class="response mce-error-response" style="display:none"></div>
					<div class="response mce-success-response" style="display:none"><p>
					' . __( 'Thank you for subscribing. We have sent you a confirmation email.', 'rather-simple-mailchimp' ) . '
					</p></div>
				</div>
			</form>
		</div>
		<!--End mc-embed-signup-->';

		return $html;
	}

	/**
	 * Render block
	 *
	 * @param array $attr    The block attributes.
	 */
	public function render_block( $attr ) {
		$html = '<div ' . wp_kses_data( get_block_wrapper_attributes() ) . '>';

		if ( $attr['id'] ) {
			$html .= '<!-- Begin Mailchimp Signup Form -->
			<div class="mc-embed-signup">
				<form method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
				<input type="hidden" value="' . esc_attr( $attr['id'] ) . '" name="ID">';

			if ( $attr['firstName'] ) {
				$placeholder = $attr['placeholder'] ? ' placeholder="' . __( 'First Name', 'rather-simple-mailchimp' ) . '"' : '';
				$html       .= '<div class="mc-field-group">
						<label for="mce-FNAME">' . __( 'First Name', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
						<input type="text" value="" name="FNAME" class="required fname" id="mce-FNAME" required ' . $placeholder . '>
					</div>';
			}

			if ( $attr['lastName'] ) {
				$placeholder = $attr['placeholder'] ? ' placeholder="' . __( 'Last Name', 'rather-simple-mailchimp' ) . '"' : '';
				$html       .= '<div class="mc-field-group">
						<label for="mce-LNAME">' . __( 'Last Name', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
						<input type="text" value="" name="LNAME" class="required lname" id="mce-LNAME" required ' . $placeholder . '>
					</div>';
			}

			$placeholder = $attr['placeholder'] ? 'placeholder="' . __( 'Email', 'rather-simple-mailchimp' ) . '"' : '';
			$html       .= '<div class="mc-field-group">
						<label for="mce-EMAIL">' . __( 'Email', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
						<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" required ' . $placeholder . '>
					</div>';

			$html .= '<div class="mc-field-group">
					<input type="checkbox" value="1" name="PRIVACY" id="mc-privacy-policy" required> <label>' . sprintf( __( 'I have read and agree to the %s.', 'rather-simple-mailchimp' ), get_the_privacy_policy_link() ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
				</div>';

			$html .= '<div class="mc-submit-button">
						<input type="submit" value="' . __( 'Subscribe', 'rather-simple-mailchimp' ) . '" name="subscribe" id="mc-embedded-subscribe" class="button wp-element-button">
					</div>
					<div class="mce-responses clear">
						<div class="response mce-error-response" style="display:none"></div>
						<div class="response mce-success-response" style="display:none"><p>
					' . __( 'Thank you for subscribing. We have sent you a confirmation email.', 'rather-simple-mailchimp' ) . '
						</p></div>
					</div>
				</form>
			</div>
			<!--End mc-embed-signup-->';
		} else {
			$html .= __( 'The Mailchimp form is not set up correctly.', 'rather-simple-mailchimp' );
		}

		$html .= '</div>';

		return $html;
	}

	/**
	 * Handle form with AJAX
	 */
	public function form_handler_ajax() {
		if ( isset( $_POST['action'] ) && 'subscribe' !== $_POST['action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( $_POST['nonce'], 'rsm-nonce' ) ) {
			return;
		}

		$email   = $_POST['EMAIL'];
		$fname   = $_POST['FNAME'] ?? '';
		$lname   = $_POST['LNAME'] ?? '';
		$list_id = $_POST['ID'];

		if ( ! empty( $list_id ) &&
		! empty( $email ) &&
		! filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			$this->subscribe_mailchimp_list( $email, $fname, $lname, $list_id );
		}
	}

	/**
	 * Subscribe user to list
	 *
	 * @param string $email     The user email.
	 * @param string $fname     The user first name.
	 * @param string $lname     The user last name.
	 * @param string $list_id   The list ID.
	 */
	public function subscribe_mailchimp_list( $email, $fname, $lname, $list_id ) {
		$api_key = RSM_API_KEY;
		if ( ! empty( $api_key ) ) {
			// MailChimp user ID.
			$member_id = md5( strtolower( $email ) );
			// MailChimp API URL.
			$data_center = substr( $api_key, strpos( $api_key, '-' ) + 1 );

			// Check if user is already subscribed.
			$response = wp_remote_request(
				'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . $member_id,
				array(
					'method'  => 'GET',
					'headers' => array(
						'Authorization'               => 'Basic ' . base64_encode( 'user:' . $api_key ),
						'Access-Control-Allow-Origin' => '*',
					),
				)
			);
			$body     = json_decode( $response['body'] );

			if ( 'subscribed' !== $body->status ) {

				// If user is not subscribed, send confirmation email.
				$response = wp_remote_request(
					'https://' . $data_center . '.api.mailchimp.com/3.0/lists/' . $list_id . '/members/' . $member_id,
					array(
						'method'  => 'PUT',
						'headers' => array(
							'Authorization' => 'Basic ' . base64_encode( 'user:' . $api_key ),
						),
						'body'    => wp_json_encode(
							array(
								'email_address' => $email,
								'merge_fields'  => array(
									'FNAME' => $fname,
									'LNAME' => $lname,
								),
								'status'        => 'pending', // Unsubscribed, subscribed or pending.
							)
						),
					)
				);
				$body     = json_decode( $response['body'] );

				if ( 200 === $response['response']['code'] ) {
					$out['result'] = 'success';
				} else {
					$out['result'] = 'error';
					$out['msg']    = $body->title . ': ' . $body->detail;
				}
				wp_send_json( $out );

			} else {
				// If user is already subscribed, send error message.
				$out['result'] = 'error';
				$out['msg']    = sprintf( __( '%s is already subscribed.', 'rather-simple-mailchimp' ), $email );
				wp_send_json( $out );
			}
		}
	}
}

add_action( 'plugins_loaded', array( Rather_Simple_Mailchimp::get_instance(), 'plugin_setup' ) );
