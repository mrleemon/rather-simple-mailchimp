<?php
/*
Plugin Name: My Simple Mailchimp
Version: v1.0
Plugin URI:
Author: Oscar Ciutat
Author URI: http://oscarciutat.com/code/
Description: A simple Mailchimp plugin with a shortcode and a widget
*/

class My_Simple_Mailchimp {

	/**
	 * Plugin instance.
	 *
	 * @since 1.0
	 *
	 */
	protected static $instance = null;


	/**
	 * Access this plugin’s working instance
	 *
	 * @since 1.0
	 *
	 */
	public static function get_instance() {
		
		if ( !self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;

	}

	
	/**
	 * Used for regular plugin work.
	 *
	 * @since 1.0
	 *
	 */
	public function plugin_setup() {

  		$this->includes();

        add_action( 'init', array( $this, 'load_language' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		
		add_shortcode( 'mailchimp', array( $this, 'shortcode_mailchimp' ) );

	}

	
	/**
	 * Constructor. Intentionally left empty and public.
	 *
	 * @since 1.0
	 *
	 */
	public function __construct() {}

	
	
 	/**
	 * Includes required core files used in admin and on the frontend.
	 *
	 * @since 1.0
	 *
	 */
	protected function includes() {
		require_once( 'include/my-simple-mailchimp-widget.php' );
	}

	
	/**
	 * Loads language
	 *
	 * @since 1.0
	 *
	 */
	function load_language() {
		load_plugin_textdomain( 'my-simple-mailchimp', '', dirname(plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	
	/**
	 * enqueue_scripts
	 */
	function enqueue_scripts() {
		global $post;
		if ( is_page() && has_shortcode( $post->post_content, 'mailchimp' ) ) {
			wp_enqueue_style( 'my-simple-mailchimp-css', plugins_url( '/style.css', __FILE__ ) );
			wp_enqueue_script( 'mc-validate', plugins_url( '/js/mc-validate.js', __FILE__ ), array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'mc-messages', plugins_url( '/js/mc-messages.js', __FILE__ ), array( 'mc-validate' ), '1.0', true );
		}
	}


	/**
	 * shortcode_mailchimp
	 */
	function shortcode_mailchimp( $atts ) {
		$html = $this->shortcode_atts( $atts );
		return $html;
	}

	
	/**
	 * shortcode_atts
	 */
	function shortcode_atts( $atts ) {
		extract( shortcode_atts( array(
			'url' => '',
			'u' => '',
			'id' => ''
		), $atts ) );
		$html = '<!-- Begin MailChimp Signup Form -->
		<div id="mc_embed_signup">
			<form action="' . esc_attr( untrailingslashit( $atts['url'] ) ) . '/subscribe/post?u=' . esc_attr( $atts['u'] ) . '&amp;id=' . esc_attr( $atts['id'] ) .'" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
			<div id="mc_embed_signup_scroll">
				<div style="position: absolute; left: -5000px;"><input type="text" name="b_' . esc_attr( $atts['u'] ) . '_' . esc_attr( $atts['id'] ) . '" tabindex="-1" value=""></div>
				<div class="mc-field-group">
					<label for="mce-EMAIL">' . __( 'email:', 'my-simple-mailchimp' ) . '<span class="required">*</span></label>
					<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
				</div>
				<div class="mc-submit-button">
					<input type="submit" value="' . __( 'subscribe', 'my-simple-mailchimp' ) . '" name="subscribe" id="mc-embedded-subscribe" class="button">
				</div>
				<div id="mce-responses" class="clear">
					<div class="response" id="mce-error-response" style="display:none"></div>
					<div class="response" id="mce-success-response" style="display:none"></div>
				</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
			</div>
			</form>
		</div>
		<!--End mc_embed_signup-->';
	
		return $html;
	}
	
}

add_action( 'plugins_loaded', array( My_Simple_Mailchimp::get_instance(), 'plugin_setup' ) );
