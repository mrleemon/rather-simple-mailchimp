import { store, getContext, getElement } from '@wordpress/interactivity';

store('rsm-store', {
	actions: {
		subscribe() {
			const context = getContext()
			const subscribe = fetch(
				'https://' + window.location.hostname + '/wp-json/occ/v1/mailchimp/subscribe',
				{
					method: 'POST',
				}
			).then(function (response) {
				context.displaySuccess = 'block';
				return response.json();
			}).catch(function (error) {
				context.displayError = 'block';
				console.error(error);
			});
		},
	}
});
