(function ($) {

	function subscribe($form) {
		$.ajax({
			type: 'POST',
			url: ajax_var.url,
			data: $form.serialize() + '&action=' + ajax_var.action + '&nonce=' + ajax_var.nonce,
			cache: false,
		}).done(function (data) {
			if (data.result !== 'success') {
				$('.mce-error-response', $form).show();
				$('.mce-error-response', $form).html(
					'<p>' + data.msg + '</p>'
				);
			} else {
				$('.mce-success-response', $form).show();
			}
		}).fail(function (jqXHR, textStatus, error) {
			console.log('error:' + error);
		});
	}

	$('.mc-embedded-subscribe-form').on('submit', function (e) {
		try {
			var $form = $(this);
			e.preventDefault();
			subscribe($form);
		} catch (error) { }
	});

})(jQuery);
