<?php
	$block_attributes = get_block_wrapper_attributes()
?>
<div
	<?php echo $block_attributes; ?>
	<?php
	echo wp_interactivity_data_wp_context(
		array(
			'offset'   => 0,
		)
	);
	?>
	data-wp-interactive="rsm-store"
>

	<?php if ( $attributes['id'] ) : ?>
		<!-- Begin Mailchimp Signup Form -->
		<div class="mc-embed-signup">
			<form method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="mc-embedded-subscribe-form">
				<input type="hidden" value="<?php echo esc_attr( $attributes['id'] ); ?>" name="ID">
				
				<?php if ( $attributes['firstName'] ) : ?>
					<?php $placeholder = $attributes['placeholder'] ? ' placeholder="' . __( 'First Name', 'rather-simple-mailchimp' ) . '"' : ''; ?>
					<div class="mc-field-group">
						<label for="mce-FNAME">
							<?php _e( 'First Name', 'rather-simple-mailchimp' ); ?> 
							<abbr class="required" title="<?php _e( 'required', 'rather-simple-mailchimp' ); ?>">*</abbr>
						</label>
						<input type="text" value="" name="FNAME" class="required fname" id="mce-FNAME" required <?php echo $placeholder; ?>>
					</div>
				<?php endif; ?>
				
				<?php if ( $attributes['lastName'] ) : ?>
					<?php $placeholder = $attributes['placeholder'] ? ' placeholder="' . __( 'Last Name', 'rather-simple-mailchimp' ) . '"' : ''; ?>
					<div class="mc-field-group">
						<label for="mce-LNAME">
							<?php _e( 'Last Name', 'rather-simple-mailchimp' ); ?> 
							<abbr class="required" title="<?php _e( 'required', 'rather-simple-mailchimp' ); ?>">*</abbr>
						</label>
						<input type="text" value="" name="LNAME" class="required lname" id="mce-LNAME" required <?php echo $placeholder; ?>>
					</div>
				<?php endif; ?>
				
				<?php $placeholder = $attributes['placeholder'] ? 'placeholder="' . __( 'Email', 'rather-simple-mailchimp' ) . '"' : ''; ?>
				<div class="mc-field-group">
					<label for="mce-EMAIL">
						<?php _e( 'Email', 'rather-simple-mailchimp' ); ?> 
						<abbr class="required" title="<?php _e( 'required', 'rather-simple-mailchimp' ); ?>">*</abbr>
					</label>
					<input type="email" value="" name="EMAIL" class="required email" id="mce-EMAIL" required <?php echo $placeholder; ?>>
				</div>
				
				<div class="mc-field-group">
					<input type="checkbox" value="1" name="PRIVACY" id="mc-privacy-policy" required>
					<label>
						<?php printf( __( 'I have read and agree to the %s.', 'rather-simple-mailchimp' ), get_the_privacy_policy_link() ); ?> 
						<abbr class="required" title="<?php _e( 'required', 'rather-simple-mailchimp' ); ?>">*</abbr>
					</label>
				</div>
				
				<div class="mc-submit-button">
					<button data-wp-on--click="actions.subscribe"><?php _e( 'Subscribe', 'rather-simple-mailchimp' ); ?></button>
				</div>
				<div class="mce-responses clear">
					<div class="response mce-error-response" data-wp-style--display="context.displayError" style="display:none"></div>
					<div class="response mce-success-response" data-wp-style--display="context.displaySuccess" style="display:none">
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
