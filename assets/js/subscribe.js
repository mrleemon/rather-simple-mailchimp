(function () {

	function subscribe(form) {
		var xhr = new XMLHttpRequest();
		xhr.open('POST', ajax_var.url, true);
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

		// Prepare the data to send
		var data = new URLSearchParams(new FormData(form)).toString() + 
			'&action=' + ajax_var.action + 
			'&nonce=' + ajax_var.nonce;

		xhr.onreadystatechange = function () {
			if (xhr.readyState === XMLHttpRequest.DONE) {
				if (xhr.status === 200) {
					var data = JSON.parse(xhr.responseText);
					if (data.result !== 'success') {
						var errorResponse = form.querySelector('.mce-error-response');
						if (errorResponse) {
							errorResponse.style.display = 'block';
							errorResponse.innerHTML = '<p>' + data.msg + '</p>';
						}
					} else {
						var successResponse = form.querySelector('.mce-success-response');
						if (successResponse) {
							successResponse.style.display = 'block';
						}
					}
				} else {
					console.log('error: ' + xhr.statusText);
				}
			}
		};

		xhr.send(data);
	}

	var forms = document.querySelectorAll('.wp-classic-occ-rather-simple-mailchimp .mc-embedded-subscribe-form');
	forms.forEach(function (form) {
		form.addEventListener('submit', function (e) {
			e.preventDefault();
			subscribe(form);
		});
	});

})();