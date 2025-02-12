(function () {

	document.querySelectorAll('.wp-block-occ-rather-simple-mailchimp .mc-field-group .fname, .wp-block-occ-rather-simple-mailchimp .mc-field-group .lname, .wp-block-occ-rather-simple-mailchimp .mc-field-group .email').forEach(function (item) {
		item.addEventListener('focus', function (e) {
			var form = e.currentTarget.form;
			form.querySelectorAll('.mce-responses .response').forEach(function (item) {
				item.style.display = 'none';
			});
		});
	});

})();
