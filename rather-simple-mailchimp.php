<?php
/**
 * Plugin Name: Rather Simple Mailchimp
 * Plugin URI:
 * Update URI: false
 * Version: v1.0
 * Requires at least: 5.3
 * Requires PHP: 7.0
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
		require_once 'include/rather-simple-mailchimp-widget.php';
		require_once 'include/rather-simple-mailchimp-popup-widget.php';
	}

	/**
	 * Loads language
	 */
	public function load_language() {
		load_plugin_textdomain( 'rather-simple-mailchimp', '', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Enqueue scripts
	 */
	public function wp_enqueue_scripts() {
		global $post;
		if ( is_page() && has_shortcode( $post->post_content, 'mailchimp' ) || is_active_widget( false, false, 'rather_simple_mailchimp_widget' ) || is_active_widget( false, false, 'rather_simple_mailchimp_popup_widget' ) ) {
			wp_enqueue_style( 'rather-simple-mailchimp-css', plugins_url( '/style.css', __FILE__ ) );
			wp_enqueue_script( 'mc-subscribe', plugins_url( '/assets/js/mc-subscribe.js', __FILE__ ), array( 'jquery' ), false, true );
			wp_enqueue_script( 'frontend', plugins_url( '/assets/js/frontend.js', __FILE__ ), array( 'mc-subscribe' ), false, true );
		}
	}

	/**
	 * Registers block
	 */
	public function register_block() {

		if ( ! function_exists( 'register_block_type' ) ) {
			// The block editor is not active.
			return;
		}

		$dir               = dirname( __FILE__ );
		$script_asset_path = "$dir/build/index.asset.php";
		if ( ! file_exists( $script_asset_path ) ) {
			throw new Error(
				'You need to run `npm start` or `npm run build` for the block first.'
			);
		}
		$script_asset = require $script_asset_path;

		wp_register_style(
			'rather-simple-mailchimp-frontend',
			plugins_url( 'build/style-index.css', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'build/style-index.css' )
		);
		wp_register_script(
			'rather-simple-mailchimp-frontend',
			plugins_url( 'assets/js/frontend.js', __FILE__ ),
			array(),
			filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/frontend.js' ),
			true
		);
		wp_register_script(
			'rather-simple-mailchimp-block',
			plugins_url( 'build/index.js', __FILE__ ),
			$script_asset['dependencies'],
			filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
			true
		);

		if ( is_admin() ) {
			register_block_type(
				'occ/mailchimp',
				array(
					'editor_script'   => 'rather-simple-mailchimp-block',
					'style'           => 'rather-simple-mailchimp-frontend',
					'render_callback' => array( $this, 'render_block' ),
					'attributes'      => array(
						'url'         => array(
							'type' => 'string',
						),
						'u'           => array(
							'type' => 'string',
						),
						'id'          => array(
							'type' => 'string',
						),
						'firstName'   => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'lastName'    => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'placeholder' => array(
							'type'    => 'boolean',
							'default' => false,
						),
					),
				)
			);
		} else {
			// Only load Mailchimp scripts on frontend.
			register_block_type(
				'occ/mailchimp',
				array(
					'editor_script'   => 'rather-simple-mailchimp-block',
					'style'           => 'rather-simple-mailchimp-frontend',
					'script'          => 'rather-simple-mailchimp-frontend',
					'render_callback' => array( $this, 'render_block' ),
					'attributes'      => array(
						'url'         => array(
							'type' => 'string',
						),
						'u'           => array(
							'type' => 'string',
						),
						'id'          => array(
							'type' => 'string',
						),
						'firstName'   => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'lastName'    => array(
							'type'    => 'boolean',
							'default' => false,
						),
						'placeholder' => array(
							'type'    => 'boolean',
							'default' => false,
						),
					),
				)
			);
		}

		// Register the block by passing the location of block.json to register_block_type.
		// register_block_type( __DIR__ );

		wp_set_script_translations( 'rather-simple-mailchimp-block', 'rather-simple-mailchimp', plugin_dir_path( __FILE__ ) . 'languages' );

	}

	/**
	 * Render shortcode
	 */
	public function render_shortcode( $attr ) {
		$html = $this->shortcode_atts( $attr );
		return $html;
	}

	/**
	 * Shortcode attributes
	 */
	public function shortcode_atts( $attr ) {
		$atts = shortcode_atts(
			array(
				'url'         => '',
				'u'           => '',
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
            <form action="' . esc_attr( untrailingslashit( $atts['url'] ) ) . '/subscribe/post-json?u=' . esc_attr( $atts['u'] ) . '&amp;id=' . esc_attr( $atts['id'] ) . '&amp;c=?" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
            <div class="mc-embed-signup-scroll">
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_' . esc_attr( $atts['u'] ) . '_' . esc_attr( $atts['id'] ) . '" tabindex="-1" value=""></div>';

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
                    <input type="submit" value="' . __( 'Subscribe', 'rather-simple-mailchimp' ) . '" name="subscribe" id="mc-embedded-subscribe" class="button">
                </div>
                <div class="mce-responses" class="clear">
                    <div class="response mce-error-response" style="display:none"></div>
                    <div class="response mce-success-response" style="display:none"></div>
                </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
            </div>
            </form>
        </div>
        <!--End mc-embed-signup-->';

		return $html;
	}

	/**
	 * Render block
	 */
	public function render_block( $attr, $content ) {
		$html = '';
		if ( $attr['url'] && $attr['u'] && $attr['id'] ) {
			$wrapper_attributes = get_block_wrapper_attributes();
			$html              .= '<div ' . $wrapper_attributes . '>
            <!-- Begin Mailchimp Signup Form -->
            <div class="mc-embed-signup">
                <form action="' . esc_attr( untrailingslashit( $attr['url'] ) ) . '/subscribe/post-json?u=' . esc_attr( $attr['u'] ) . '&amp;id=' . esc_attr( $attr['id'] ) . '&amp;c=?" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
                <div class="mc-embed-signup-scroll">
                    <div style="position: absolute; left: -5000px;"><input type="text" name="b_' . esc_attr( $attr['u'] ) . '_' . esc_attr( $attr['id'] ) . '" tabindex="-1" value=""></div>';

			if ( $attr['firstName'] ) {
				$html .= '<div class="mc-field-group">
                        <label for="mce-FNAME">' . __( 'First Name', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
                        <input type="text" value="" name="FNAME" class="required fname" id="mce-FNAME" required>
                    </div>';
			}

			if ( $attr['lastName'] ) {
				$html .= '<div class="mc-field-group">
                        <label for="mce-LNAME">' . __( 'Last Name', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
                        <input type="text" value="" name="LNAME" class="required lname" id="mce-LNAME" required>
                    </div>';
			}

			$html .= '<div class="mc-field-group">
                        <label for="mce-EMAIL">' . __( 'Email', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
                        <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" required>
                    </div>';

			$html .= '<div class="mc-field-group">
                    <input type="checkbox" value="1" name="PRIVACY" id="mc-privacy-policy" required> <label>' . sprintf( __( 'I have read and agree to the %s.', 'rather-simple-mailchimp' ), get_the_privacy_policy_link() ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
                </div>';

			$html .= '<div class="mc-submit-button">
                        <input type="submit" value="' . __( 'Subscribe', 'rather-simple-mailchimp' ) . '" name="subscribe" id="mc-embedded-subscribe" class="button">
                    </div>
                    <div class="mce-responses" class="clear">
                        <div class="response mce-error-response" style="display:none"></div>
                        <div class="response mce-success-response" style="display:none"></div>
                    </div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                </div>
                </form>
                <script src="' . plugins_url( '/assets/js/mc-subscribe.js', __FILE__ ) . '"></script>
            </div>
            <!--End mc-embed-signup-->
            </div>';
		}

		return $html;
	}

}

add_action( 'plugins_loaded', array( Rather_Simple_Mailchimp::get_instance(), 'plugin_setup' ) );
