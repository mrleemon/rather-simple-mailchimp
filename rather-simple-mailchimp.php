<?php
/*
Plugin Name: Rather Simple MailChimp
Version: v1.0
Plugin URI:
Author: Oscar Ciutat
Author URI: http://oscarciutat.com/code/
Description: A simple MailChimp plugin with a block, a shortcode and a widget
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
        require_once 'include/rather-simple-mailchimp-widget.php';
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
        if ( is_page() && has_shortcode( $post->post_content, 'mailchimp' ) || is_active_widget( false, false, 'my_simple_mailchimp_widget' ) ) {
            wp_enqueue_style( 'rather-simple-mailchimp-css', plugins_url( '/style.css', __FILE__ ) );
            wp_enqueue_script( 'mc-validate', plugins_url( '/assets/js/mc-validate.js', __FILE__ ), array( 'jquery' ), false, true );
            wp_enqueue_script( 'mc-messages', plugins_url( '/assets/js/mc-messages.js', __FILE__ ), array( 'mc-validate' ), false, true );
            wp_enqueue_script( 'frontend', plugins_url( '/assets/js/frontend.js', __FILE__ ), array( 'mc-messages' ), false, true );
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

        /*wp_register_style(
            'rather-simple-mailchimp',
            plugins_url( 'build/editor.css', __FILE__ ),
            array( 'wp-edit-blocks' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'build/editor.css' )
        );*/
        wp_register_style(
            'rather-simple-mailchimp-frontend',
            plugins_url( 'build/style-index.css', __FILE__ ),
            array(),
            filemtime( plugin_dir_path( __FILE__ ) . 'build/style-index.css' )
        );
        wp_register_script(
            'mc-validate',
            plugins_url( 'assets/js/mc-validate.js', __FILE__ ),
            array( 'jquery' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/mc-validate.js' ),
            true
        );
        wp_register_script(
            'rather-simple-mailchimp-frontend',
            plugins_url( 'assets/js/frontend.js', __FILE__ ),
            array( 'mc-validate' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'assets/js/frontend.js' ),
            true
        );
        wp_register_script(
            'rather-simple-mailchimp',
            plugins_url( 'build/index.js', __FILE__ ),
            array( 'wp-block-editor', 'wp-blocks', 'wp-components', 'wp-element', 'wp-i18n' ),
            filemtime( plugin_dir_path( __FILE__ ) . 'build/index.js' ),
            false // can't be loaded on footer at the moment
        );

        register_block_type( 'occ/mailchimp', array(
            /*'editor_style'  => 'rather-simple-mailchimp',*/
            'editor_script' => 'rather-simple-mailchimp',
            'style' => 'rather-simple-mailchimp-frontend',
            'script' => 'rather-simple-mailchimp-frontend',
        ) );

        wp_set_script_translations( 'rather-simple-mailchimp-block', 'rather-simple-mailchimp', plugin_dir_path( __FILE__ ) . 'languages' );

    }

    /**
     * shortcode_mailchimp
     */
    function shortcode_mailchimp( $attr ) {
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

        $html = '<!-- Begin MailChimp Signup Form -->
          <div id="mc_embed_signup">
            <form action="' . esc_attr( untrailingslashit( $atts['url'] ) ) . '/subscribe/post?u=' . esc_attr( $atts['u'] ) . '&amp;id=' . esc_attr( $atts['id'] ) .'" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
            <div id="mc_embed_signup_scroll">
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_' . esc_attr( $atts['u'] ) . '_' . esc_attr( $atts['id'] ) . '" tabindex="-1" value=""></div>';

        if ( $atts['first_name'] ) {
            $html .= '<div class="mc-field-group">
                    <label for="mce-FNAME">' . __( 'First Name', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                    <input type="text" value="" name="FNAME" class="required fname" id="mce-FNAME">
                </div>';
        }

        if ( $atts['last_name'] ) {
            $html .= '<div class="mc-field-group">
                    <label for="mce-LNAME">' . __( 'Last Name', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                    <input type="text" value="" name="LNAME" class="required lname" id="mce-LNAME">
                </div>';
        }
        
        $html .= '<div class="mc-field-group">
                    <label for="mce-EMAIL">' . __( 'Email', 'rather-simple-mailchimp' ) . ' <span class="required">*</span></label>
                    <input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
                </div>';

        $html .= '<div class="mc-submit-button">
                    <input type="submit" value="' . __( 'Subscribe', 'rather-simple-mailchimp' ) . '" name="subscribe" id="mc-embedded-subscribe" class="button">
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

add_action( 'plugins_loaded', array( Rather_Simple_Mailchimp::get_instance(), 'plugin_setup' ) );
