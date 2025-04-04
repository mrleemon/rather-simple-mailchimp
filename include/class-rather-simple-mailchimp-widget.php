<?php
/**
 * Rather_Simple_Mailchimp_Widget class
 *
 * @package rather_simple_mailchimp
 */

/**
 * Core class used to implement the widget.
 *
 * @see WP_Widget
 */
class Rather_Simple_Mailchimp_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$widget_ops = array(
			'classname'   => 'rather_simple_mailchimp_widget',
			'description' => __( 'A simple Mailchimp widget', 'rather-simple-mailchimp' ),
		);
		parent::__construct( 'rather_simple_mailchimp_widget', __( 'Rather Simple Mailchimp widget', 'rather-simple-mailchimp' ), $widget_ops );
	}

	/**
	 * Output widget.
	 *
	 * @param array $args    Display arguments.
	 * @param array $instance Settings for the current widget instance.
	 */
	public function widget( $args, $instance ) {
		$title       = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance );
		$textarea    = apply_filters( 'widget_textarea', empty( $instance['textarea'] ) ? '' : $instance['textarea'], $instance );
		$id          = empty( $instance['id'] ) ? '' : $instance['id'];
		$first_name  = ! empty( $instance['first_name'] );
		$last_name   = ! empty( $instance['last_name'] );
		$placeholder = ! empty( $instance['placeholder'] );

		$this->enqueue();

		echo $args['before_widget'];

		if ( ! empty( $title ) ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}

		if ( ! empty( $textarea ) ) {
			echo wpautop( $textarea );
		}

		$html = '<div class="wp-classic-occ-rather-simple-mailchimp">
			<!-- Begin Mailchimp Signup Form -->
			<div class="mc-embed-signup">
			<form method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
			<input type="hidden" value="' . esc_attr( $id ) . '" name="ID">';

		if ( $first_name ) {
			$placeholder_st = $placeholder ? ' placeholder="' . __( 'First Name', 'rather-simple-mailchimp' ) . '"' : '';
			$html          .= '<div class="mc-field-group">
					<label for="mce-FNAME">' . __( 'First Name', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
					<input type="text" value="" name="FNAME" class="required" id="mce-FNAME" required ' . $placeholder_st . '>
					</div>';
		}

		if ( $last_name ) {
			$placeholder_st = $placeholder ? ' placeholder="' . __( 'Last Name', 'rather-simple-mailchimp' ) . '"' : '';
			$html          .= '<div class="mc-field-group">
					<label for="mce-LNAME">' . __( 'Last Name', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
					<input type="text" value="" name="LNAME" class="required" id="mce-LNAME" required ' . $placeholder_st . '>
					</div>';
		}

		$placeholder_st = $placeholder ? 'placeholder="' . __( 'Email', 'rather-simple-mailchimp' ) . '"' : '';
		$html          .= '<div class="mc-field-group">
					<label for="mce-EMAIL">' . __( 'Email', 'rather-simple-mailchimp' ) . ' <abbr class="required" title="' . __( 'required', 'rather-simple-mailchimp' ) . '">*</abbr></label>
					<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" required ' . $placeholder_st . '>
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
		<!--End mc-embed-signup-->
		</div>';

		echo $html;

		echo $args['after_widget'];
	}

	/**
	 * Updates a particular instance of a widget.
	 *
	 * @param array $new_instance New settings for this instance as input by the user via WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = $old_instance;
		$instance['title'] = wp_strip_all_tags( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) ) {
			$instance['textarea'] = $new_instance['textarea'];
		} else {
			$instance['textarea'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['textarea'] ) ) );
		}
		$instance['id']          = wp_strip_all_tags( $new_instance['id'] );
		$instance['first_name']  = ! empty( $new_instance['first_name'] );
		$instance['last_name']   = ! empty( $new_instance['last_name'] );
		$instance['placeholder'] = ! empty( $new_instance['placeholder'] );
		return $instance;
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @param array $instance Current settings.
	 */
	public function form( $instance ) {
		$instance    = wp_parse_args(
			(array) $instance,
			array(
				'title'    => '',
				'textarea' => '',
				'id'       => '',
			)
		);
		$title       = wp_strip_all_tags( $instance['title'] );
		$textarea    = $instance['textarea'];
		$id          = wp_strip_all_tags( $instance['id'] );
		$first_name  = ! empty( $instance['first_name'] );
		$last_name   = ! empty( $instance['last_name'] );
		$placeholder = ! empty( $instance['placeholder'] );

		?>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			</p>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'textarea' ) ); ?>"><?php _e( 'Content' ); ?></label>
			<textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'textarea' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'textarea' ) ); ?>"><?php echo esc_textarea( $textarea ); ?></textarea>
			</p>
			<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>"><?php _e( 'ID', 'rather-simple-mailchimp' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'id' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'id' ) ); ?>" type="text" value="<?php echo esc_attr( $id ); ?>" />
			</p>
			<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'first_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'first_name' ) ); ?>" <?php echo checked( $first_name, true, false ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'first_name' ) ); ?>"><?php _e( 'Show First Name', 'rather-simple-mailchimp' ); ?></label>
			</p>
			<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'last_name' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'last_name' ) ); ?>" <?php echo checked( $last_name, true, false ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'last_name' ) ); ?>"><?php _e( 'Show Last Name', 'rather-simple-mailchimp' ); ?></label>
			</p>
			<p>
			<input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'placeholder' ) ); ?>" <?php echo checked( $placeholder, true, false ); ?> />
			<label for="<?php echo esc_attr( $this->get_field_id( 'placeholder' ) ); ?>"><?php _e( 'Show Placeholder', 'rather-simple-mailchimp' ); ?></label>
			</p>
		<?php
	}

	/**
	 * Enqueues styles and scripts.
	 */
	public function enqueue() {
		wp_enqueue_style(
			'rsm-style',
			plugins_url( '/style.css', __DIR__ ),
			array(),
			filemtime( plugin_dir_path( __DIR__ ) . 'style.css' )
		);
		wp_enqueue_script(
			'rsm-subscribe',
			plugins_url( '/assets/js/subscribe.js', __DIR__ ),
			array(),
			filemtime( plugin_dir_path( __DIR__ ) . 'assets/js/subscribe.js' ),
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
			plugins_url( '/assets/js/frontend.js', __DIR__ ),
			array( 'rsm-subscribe' ),
			filemtime( plugin_dir_path( __DIR__ ) . 'assets/js/frontend.js' ),
			array(
				'in_footer' => true,
				'strategy'  => 'defer',
			)
		);
	}
}

add_action(
	'widgets_init',
	function () {
		return register_widget( 'Rather_Simple_Mailchimp_Widget' );
	}
);
