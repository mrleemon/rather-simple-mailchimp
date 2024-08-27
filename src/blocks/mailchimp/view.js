(function () {

	document.querySelectorAll('.mc-field-group .fname, .mc-field-group .lname, .mc-field-group .email').forEach(function (item) {
		item.addEventListener('focus', function (e) {
			var form = e.currentTarget.form;
			form.querySelectorAll('.mce-responses .response').forEach(function (item) {
				item.style.display = 'none';
			});
		});
	});

})();
