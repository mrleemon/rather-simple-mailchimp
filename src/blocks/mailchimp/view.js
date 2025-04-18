import { store, withSyncEvent, getContext } from '@wordpress/interactivity';

store('rsm-store', {
	actions: {
		reset: function() {
			const context = getContext();
			context.displayError = 'none';
			context.displaySuccess = 'none';
		},
		subscribe: withSyncEvent(function* (event) {
			const context = getContext();
			const formData = new FormData(event.target);
			event.preventDefault();
			context.displayError = 'none';
			context.displaySuccess = 'none';
			try {
				const response = yield fetch(
					'https://' + window.location.hostname + '/wp-json/occ/v1/mailchimp/subscribe',
					{
						method: 'POST',
						body: formData,
					});
				const data = yield response.json();
				if (data.result === 'success') {
					context.displaySuccess = 'block';
				} else {
					context.displayError = 'block';
					context.errorMessage = data.msg || 'An error occurred';
				}
			} catch (error) {
				context.displayError = 'block';
				context.errorMessage = error;
			}
		}),
	}
});
