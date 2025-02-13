(function () {

	document.querySelectorAll('.wp-classic-occ-rather-simple-mailchimp .mc-field-group .fname, .wp-classic-occ-rather-simple-mailchimp .mc-field-group .lname, .wp-classic-occ-rather-simple-mailchimp .mc-field-group .email').forEach(function (item) {
		item.addEventListener('focus', function (e) {
			var form = e.currentTarget.form;
			form.querySelectorAll('.mce-responses .response').forEach(function (item) {
				item.style.display = 'none';
			});
		});
	});

})();
