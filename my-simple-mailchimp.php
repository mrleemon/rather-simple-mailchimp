<?php
/*
Plugin Name: My Simple Mailchimp
Version: v1.0
Plugin URI:
Author: leemon
Author URI: 
Description: A simple Mailchimp plugin
Text Domain: my-simple-mailchimp
*/

define('SM_FOLDER', dirname(plugin_basename(__FILE__)));
define('SM_URL', get_option('siteurl') . '/wp-content/plugins/' . SM_FOLDER);

/*
* sm_init
*/
function sm_init() {
	load_plugin_textdomain('my-simple-mailchimp', '', dirname(plugin_basename( __FILE__ )) . '/languages/');
	add_shortcode('mailchimp', 'sm_shortcode_mailchimp');
	add_action('wp_enqueue_scripts', 'sm_enqueue_scripts'); 
}
add_action('init', 'sm_init');


/*
 * sm_enqueue_scripts 
 */
  
function sm_enqueue_scripts() {
	wp_enqueue_script('mc-validate', SM_URL . '/js/mc-validate.js', array('jquery'), '1.0', true);
	wp_enqueue_script('mc-messages', SM_URL . '/js/mc-messages.js', array('mc-validate'), '1.0', true); 
	wp_enqueue_style('ajaxchimp-css', SM_URL . '/style.css');
}


/**
 * sm_shortcode_mailchimp
 */
function sm_shortcode_mailchimp($atts) {
	$html = sm_shortcode_atts($atts);
	return $html;
}

/**
 * sm_shortcode_atts
 */
function sm_shortcode_atts($atts) {
	extract(shortcode_atts(array(
		'url' => '',
		'u' => '',
		'id' => ''
	), $atts));
	$html = '<!-- Begin MailChimp Signup Form -->
	<div id="mc_embed_signup">
		<form action="' . untrailingslashit($atts['url']) . '/subscribe/post?u=' . $atts['u'] . '&amp;id=' . $atts['id'] .'" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
		<div id="mc_embed_signup_scroll">
			<div style="position: absolute; left: -5000px;"><input type="text" name="b_' . $atts['u'] . '_' . $atts['id'] . '" tabindex="-1" value=""></div>
			<div class="mc-field-group">
				<label for="mce-EMAIL">' . __('email:', 'my-simple-mailchimp') . '<span class="required">*</span></label>
				<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL">
			</div>
			<div class="mc-submit-button">
				<input type="submit" value="' . __('subscribe', 'my-simple-mailchimp') . '" name="subscribe" id="mc-embedded-subscribe" class="button">
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
