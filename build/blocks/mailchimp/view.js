document.querySelectorAll(".mc-field-group .fname, .mc-field-group .lname, .mc-field-group .email").forEach((function(e){e.addEventListener("focus",(function(e){e.currentTarget.form.querySelectorAll(".mce-responses .response").forEach((function(e){e.style.display="none"}))}))}));