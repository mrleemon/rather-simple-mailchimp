<?php
	$block_attributes = get_block_wrapper_attributes()
?>
<div
	<?php echo $block_attributes; ?>
	<?php
	echo wp_interactivity_data_wp_context(
		array(
			'displaySuccess' => 'none',
			'displayError'   => 'none',
			'errorMessage'   => '',
		)
	);
	?>
	data-wp-interactive="rsm-store"
>

	<?php if ( $attributes['id'] ) : ?>
		<!-- Begin Mailchimp Signup Form -->
		<div class="mc-embed-signup">
			<form data-wp-on--submit="actions.subscribe" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
				<input type="hidden" value="<?php echo esc_attr( $attributes['id'] ); ?>" name="id">
				
				<?php if ( $attributes['firstName'] ) : ?>
					<?php $placeholder = $attributes['placeholder'] ? ' placeholder="' . __( 'First Name', 'rather-simple-mailchimp' ) . '"' : ''; ?>
					<div class="mc-field-group">
						<label for="mce-FNAME">
							<?php _e( 'First Name', 'rather-simple-mailchimp' ); ?> 
							<abbr class="required" title="<?php _e( 'required', 'rather-simple-mailchimp' ); ?>">*</abbr>
						</label>
						<input data-wp-on--focus="actions.reset" type="text" value="" name="fname" class="required fname" id="mce-FNAME" required <?php echo $placeholder; ?>>
					</div>
				<?php endif; ?>
				
				<?php if ( $attributes['lastName'] ) : ?>
					<?php $placeholder = $attributes['placeholder'] ? ' placeholder="' . __( 'Last Name', 'rather-simple-mailchimp' ) . '"' : ''; ?>
					<div class="mc-field-group">
						<label for="mce-LNAME">
							<?php _e( 'Last Name', 'rather-simple-mailchimp' ); ?> 
							<abbr class="required" title="<?php _e( 'required', 'rather-simple-mailchimp' ); ?>">*</abbr>
						</label>
						<input data-wp-on--focus="actions.reset" type="text" value="" name="lname" class="required lname" id="mce-LNAME" required <?php echo $placeholder; ?>>
					</div>
				<?php endif; ?>
				
				<?php $placeholder = $attributes['placeholder'] ? 'placeholder="' . __( 'Email', 'rather-simple-mailchimp' ) . '"' : ''; ?>
				<div class="mc-field-group">
					<label for="mce-EMAIL">
						<?php _e( 'Email', 'rather-simple-mailchimp' ); ?> 
						<abbr class="required" title="<?php _e( 'required', 'rather-simple-mailchimp' ); ?>">*</abbr>
					</label>
					<input data-wp-on--focus="actions.reset" type="email" value="" name="email" class="required email" id="mce-EMAIL" required <?php echo $placeholder; ?>>
				</div>
				
				<div class="mc-field-group">
					<input type="checkbox" value="1" name="privacy" id="mc-privacy-policy" required>
					<label>
						<?php printf( __( 'I have read and agree to the %s.', 'rather-simple-mailchimp' ), get_the_privacy_policy_link() ); ?> 
						<abbr class="required" title="<?php _e( 'required', 'rather-simple-mailchimp' ); ?>">*</abbr>
					</label>
				</div>
				
				<div class="mc-submit-button">
					<button type="submit"><?php _e( 'Subscribe', 'rather-simple-mailchimp' ); ?></button>
				</div>
				<div class="mce-responses clear">
					<div class="response mce-error-response" data-wp-style--display="context.displayError" data-wp-text="context.errorMessage"></div>
					<div class="response mce-success-response" data-wp-style--display="context.displaySuccess">
						<p><?php _e( 'Thank you for subscribing. We have sent you a confirmation email.', 'rather-simple-mailchimp' ); ?></p>
					</div>
				</div>
			</form>
		</div>
		<!-- End mc-embed-signup -->
	<?php else : ?>
		<?php _e( 'The Mailchimp form is not set up correctly.', 'rather-simple-mailchimp' ); ?>
	<?php endif; ?>

</div>
