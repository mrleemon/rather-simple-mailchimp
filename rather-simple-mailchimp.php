<?php
/**
 * Plugin Name: Rather Simple Mailchimp
 * Plugin URI:
 * Update URI: false
 * Version: 2.0
 * Requires at least: 6.8
 * Requires PHP: 7.4
 * Author: Oscar Ciutat
 * Author URI: http://oscarciutat.com/code/
 * Text Domain: rather-simple-mailchimp
 * Domain Path: /languages
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

		add_action( 'init', array( $this, 'register_block' ) );
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );

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
		require_once 'includes/class-rather-simple-mailchimp-admin.php';
		require_once 'includes/class-rather-simple-mailchimp-widget.php';
		require_once 'includes/class-rather-simple-mailchimp-popup-widget.php';
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

		// Register the block.
		register_block_type( __DIR__ . '/build/blocks/mailchimp' );

		// Load translations.
		$script_handle = generate_block_asset_handle( 'occ/rather-simple-mailchimp', 'editorScript' );
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

		$html = '<div class="wp-classic-occ-rather-simple-mailchimp">
		  <!-- Begin Mailchimp Signup Form -->
		  <div class="mc-embed-signup">
			<form method="post" class="mc-embedded-subscribe-form">
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
					<input type="submit" value="' . __( 'Subscribe', 'rather-simple-mailchimp' ) . '" name="subscribe" id="mc-embedded-subscribe" class="wp-element-button">
				</div>
				<div class="mc-responses">
					<div class="response mc-error-response" style="display:none"></div>
					<div class="response mc-success-response" style="display:none"><p>
					' . __( 'Thank you for subscribing. We have sent you a confirmation email.', 'rather-simple-mailchimp' ) . '
					</p></div>
				</div>
			</form>
		</div>
		<!--End mc-embed-signup-->
		</div>';

		return $html;
	}

	/**
	 * Handle form with AJAX
	 */
	public function form_handler_ajax() {
		if ( isset( $_POST['action'] ) && 'subscribe' !== $_POST['action'] ) {
			return;
		}

		if ( ! wp_verify_nonce( wp_unslash( $_POST['nonce'] ), 'rsm-nonce' ) ) {
			return;
		}

		$email   = wp_unslash( $_POST['EMAIL'] );
		$fname   = wp_unslash( $_POST['FNAME'] ?? '' );
		$lname   = wp_unslash( $_POST['LNAME'] ?? '' );
		$list_id = wp_unslash( $_POST['ID'] );

		if ( ! empty( $list_id ) &&
		! empty( $email ) &&
		! filter_var( $email, FILTER_VALIDATE_EMAIL ) === false ) {
			$this->subscribe_mailchimp_list( $email, $fname, $lname, $list_id );
		}
	}

	/**
	 * Handle form with REST API
	 */
	public function register_rest_routes() {
		register_rest_route(
			'occ/v1',
			'/mailchimp/subscribe',
			array(
				'methods'             => 'POST',
				'callback'            => array( $this, 'subscribe_mailchimp_list_rest' ),
				'permission_callback' => '__return_true',
				'args'                => array(
					'nonce' => array(
						'required'          => true,
						'validate_callback' => function ( $param ) {
							return wp_verify_nonce( $param, 'rsm-nonce' );
						},
					),
					'id'    => array(
						'required'          => true,
						'validate_callback' => function ( $param ) {
							return is_string( $param ) && preg_match( '/^[a-zA-Z0-9]+$/', $param );
						},
					),
					'email' => array(
						'required'          => true,
						'validate_callback' => function ( $param ) {
							return is_email( $param );
						},
					),
					'fname' => array(
						'validate_callback' => function ( $param ) {
							return is_string( $param );
						},
					),
					'lname' => array(
						'validate_callback' => function ( $param ) {
							return is_string( $param );
						},
					),
				),
			),
		);
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
		$settings = (array) get_option( 'rsm_settings' );
		$api_key  = isset( $settings['api_key'] ) ? $settings['api_key'] : '';

		if ( ! empty( $api_key ) ) {
			// MailChimp user ID.
			$member_id = md5( strtolower( $email ) );
			// MailChimp API URL.
			$data_center = substr( $api_key, strpos( $api_key, '-' ) + 1 );

			// Verify whether user is already subscribed, as Mailchimp retains users even after they unsubscribe.
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

			if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
				wp_send_json(
					array(
						'result' => 'error',
						'msg'    => __( 'Connection to Mailchimp failed.', 'rather-simple-mailchimp' ),
					)
				);
			}

			$body = json_decode( $response['body'] );

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
									'FNAME' => empty( $fname ) ? '' : $fname,
									'LNAME' => empty( $lname ) ? '' : $lname,
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
		} else {
			// If API key is missing, send error message.
			$out['result'] = 'error';
			$out['msg']    = sprintf( __( 'Mailchimp API key is missing.', 'rather-simple-mailchimp' ), $email );
			wp_send_json( $out );
		}
	}

	/**
	 * Subscribe user to list via REST API
	 *
	 * @param WP_REST_Request $request    The REST request.
	 */
	public function subscribe_mailchimp_list_rest( $request ) {
		$list_id = $request->get_param( 'id' );
		$email   = $request->get_param( 'email' );
		$fname   = $request->get_param( 'fname' );
		$lname   = $request->get_param( 'lname' );

		$settings = (array) get_option( 'rsm_settings' );
		$api_key  = isset( $settings['api_key'] ) ? $settings['api_key'] : '';

		if ( ! empty( $api_key ) ) {
			// MailChimp user ID.
			$member_id = md5( strtolower( $email ) );
			// MailChimp API URL.
			$data_center = substr( $api_key, strpos( $api_key, '-' ) + 1 );

			// Verify whether user is already subscribed, as Mailchimp retains users even after they unsubscribe.
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
									'FNAME' => empty( $fname ) ? '' : $fname,
									'LNAME' => empty( $lname ) ? '' : $lname,
								),
								'status'        => 'pending', // Unsubscribed, subscribed or pending.
							)
						),
					)
				);

				if ( is_wp_error( $response ) || empty( $response['body'] ) ) {
					wp_send_json(
						array(
							'result' => 'error',
							'msg'    => __( 'Connection to Mailchimp failed.', 'rather-simple-mailchimp' ),
						)
					);
				}

				$body = json_decode( $response['body'] );

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
		} else {
			// If API key is missing, send error message.
			$out['result'] = 'error';
			$out['msg']    = sprintf( __( 'Mailchimp API key is missing.', 'rather-simple-mailchimp' ), $email );
			wp_send_json( $out );
		}
	}
}

add_action( 'plugins_loaded', array( Rather_Simple_Mailchimp::get_instance(), 'plugin_setup' ) );
