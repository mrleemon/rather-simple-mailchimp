import { store, getContext, getElement } from '@wordpress/interactivity';

store('rsm-store', {
	actions: {
		reset() {
			const context = getContext();
			context.displayError = 'none';
			context.displaySuccess = 'none';
		},
		*subscribe(event) {
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
					console.log(data);
				if (data.result === 'success') {
					context.displaySuccess = 'block';
				} else {
					context.displayError = 'block';
					context.errorMessage = data.msg || 'An error occurred';
				}
			} catch (error) {
				context.displayError = 'block';
				context.errorMessage = error;
				console.error(error);
			}
		},
	}
});
