<?php
/*
Plugin Name: Rather Simple Mailchimp
Version: v1.0
Plugin URI:
Author: Oscar Ciutat
Author URI: http://oscarciutat.com/code/
Description: A simple Mailchimp plugin with a block, a shortcode and a widget
*/

class Rather_Simple_Mailchimp {

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
        add_action( 'init', array( $this, 'register_block' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );

        add_shortcode( 'mailchimp', array( $this, 'render_shortcode' ) );

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
        require_once 'include/rather-simple-mailchimp-widget.php';
        require_once 'include/rather-simple-mailchimp-popup-widget.php';
    }
    
    /**
     * Loads language
     *
     * @since 1.0
     *
     */
    function load_language() {
        load_plugin_textdomain( 'rather-simple-mailchimp', '', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
    }
    
    /**
     * wp_enqueue_scripts
     */
    function wp_enqueue_scripts() {
        global $post;
        if ( is_page() && has_shortcode( $post->post_content, 'mailchimp' ) || is_active_widget( false, false, 'rather_simple_mailchimp_widget' ) || is_active_widget( false, false, 'rather_simple_mailchimp_popup_widget' ) ) {
            wp_enqueue_style( 'rather-simple-mailchimp-css', plugins_url( '/style.css', __FILE__ ) );
            wp_enqueue_script( 'mc-subscribe', plugins_url( '/assets/js/mc-subscribe.js', __FILE__ ), array( 'jquery' ), false, true );
            wp_enqueue_script( 'frontend', plugins_url( '/assets/js/frontend.js', __FILE__ ), array( 'mc-subscribe' ), false, true );
        }
    }

    /**
     * Registers block
     *
     * @since 1.0
     *
     */
    function register_block() {

        if ( ! function_exists( 'register_block_type' ) ) {
            // The block editor is not active.
            return;
        }

        $dir = dirname( __FILE__ );
        $script_asset_path = "$dir/build/index.asset.php";
        if ( ! file_exists( $script_asset_path ) ) {
            throw new Error(
                'You need to run `npm start` or `npm run build` for the block first.'
            );
        }
        $script_asset = require( $script_asset_path );
        
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
            filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' )
        );

        if ( is_admin() ) {
            register_block_type( 'occ/mailchimp', array(
                'editor_script' => 'rather-simple-mailchimp-block',
                'style' => 'rather-simple-mailchimp-frontend',
                'render_callback' => array( $this, 'render_block' ),
                'attributes' => array(
                    'url' => array(
                        'type'    => 'string',
                    ),
                    'u' => array(
                        'type'    => 'string',
                    ),
                    'id' => array(
                        'type'    => 'string',
                    ),
                    'firstName'   => array(
                        'type'    => 'boolean',
                        'default' => false,
                    ),
                    'lastName'    => array(
                        'type'    => 'boolean',
                        'default' => false,
                    ),
                ),
            ) );
        } else {
            // Only load Mailchimp scripts on frontend
            register_block_type( 'occ/mailchimp', array(
                'editor_script' => 'rather-simple-mailchimp-block',
                'style' => 'rather-simple-mailchimp-frontend',
                'script' => 'rather-simple-mailchimp-frontend',
                'render_callback' => array( $this, 'render_block' ),
                'attributes' => array(
                    'url' => array(
                        'type'    => 'string',
                    ),
                    'u' => array(
                        'type'    => 'string',
                    ),
                    'id' => array(
                        'type'    => 'string',
                    ),
                    'firstName'   => array(
                        'type'    => 'boolean',
                        'default' => false,
                    ),
                    'lastName'    => array(
                        'type'    => 'boolean',
                        'default' => false,
                    ),
                ),
            ) );
        }

        wp_set_script_translations( 'rather-simple-mailchimp-block', 'rather-simple-mailchimp', plugin_dir_path( __FILE__ ) . 'languages' );

    }

    /**
     * render_shortcode
     */
    function render_shortcode( $attr ) {
        $html = $this->shortcode_atts( $attr );
        return $html;
    }
    
    /**
     * shortcode_atts
     */
    function shortcode_atts( $attr ) {
        $atts = shortcode_atts( array(
            'url' => '',
            'u' => '',
            'id' => '',
            'first_name' => 'false',
            'last_name' => 'false',
        ), $attr, 'mailchimp' );

        $atts['first_name'] = filter_var( $atts['first_name'], FILTER_VALIDATE_BOOLEAN );
        $atts['last_name'] = filter_var( $atts['last_name'], FILTER_VALIDATE_BOOLEAN );

        $html = '<!-- Begin Mailchimp Signup Form -->
          <div class="mc-embed-signup">
            <form action="' . esc_attr( untrailingslashit( $atts['url'] ) ) . '/subscribe/post-json?u=' . esc_attr( $atts['u'] ) . '&amp;id=' . esc_attr( $atts['id'] ) . '&amp;c=?" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
            <div class="mc-embed-signup-scroll">
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_' . esc_attr( $atts['u'] ) . '_' . esc_attr( $atts['id'] ) . '" tabindex="-1" value=""></div>';

        if ( $atts['first_name'] ) {
            $html .= '<div class="mc-field-group">
                    <label for="mce-FNAME">' . __( 'First Name', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                    <input type="text" value="" name="FNAME" class="required fname" id="mce-FNAME" required>
                </div>';
        }

        if ( $atts['last_name'] ) {
            $html .= '<div class="mc-field-group">
                    <label for="mce-LNAME">' . __( 'Last Name', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                    <input type="text" value="" name="LNAME" class="required lname" id="mce-LNAME" required>
                </div>';
        }
        
        $html .= '<div class="mc-field-group">
                    <label for="mce-EMAIL">' . __( 'Email', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                    <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" required>
                </div>';

        $html .= '<div class="mc-field-group">
                <input type="checkbox" value="1" name="PRIVACY" id="mc-privacy-policy" required> <label>' . sprintf( __( 'I have read and agree to the %s.', 'rather-simple-mailchimp' ), get_the_privacy_policy_link() ) . ' <span class="required">*</span></label>
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
     * render_block
     */
    function render_block( $attr, $content ) {
        $html = '';
        if ( $attr['url'] && $attr['u'] && $attr['id'] ) {
            $html = '<!-- Begin Mailchimp Signup Form -->
            <div class="mc-embed-signup">
                <form action="' . esc_attr( untrailingslashit( $attr['url'] ) ) . '/subscribe/post-json?u=' . esc_attr( $attr['u'] ) . '&amp;id=' . esc_attr( $attr['id'] ) . '&amp;c=?" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
                <div class="mc-embed-signup-scroll">
                    <div style="position: absolute; left: -5000px;"><input type="text" name="b_' . esc_attr( $attr['u'] ) . '_' . esc_attr( $attr['id'] ) . '" tabindex="-1" value=""></div>';

            if ( $attr['firstName'] ) {
                $html .= '<div class="mc-field-group">
                        <label for="mce-FNAME">' . __( 'First Name', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                        <input type="text" value="" name="FNAME" class="required fname" id="mce-FNAME" required>
                    </div>';
            }

            if ( $attr['lastName'] ) {
                $html .= '<div class="mc-field-group">
                        <label for="mce-LNAME">' . __( 'Last Name', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                        <input type="text" value="" name="LNAME" class="required lname" id="mce-LNAME" required>
                    </div>';
            }
            
            $html .= '<div class="mc-field-group">
                        <label for="mce-EMAIL">' . __( 'Email', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                        <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" required>
                    </div>';

            $html .= '<div class="mc-field-group">
                    <input type="checkbox" value="1" name="PRIVACY" id="mc-privacy-policy" required> <label>' . sprintf( __( 'I have read and agree to the %s.', 'rather-simple-mailchimp' ), get_the_privacy_policy_link() ) . ' <span class="required">*</span></label>
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
            <!--End mc-embed-signup-->';
        }

        return $html;
    }

}

add_action( 'plugins_loaded', array( Rather_Simple_Mailchimp::get_instance(), 'plugin_setup' ) );
