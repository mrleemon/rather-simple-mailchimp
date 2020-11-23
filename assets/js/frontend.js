( function ( $ ) {

	$( '.mc-field-group .fname, .mc-field-group .lname, .mc-field-group .email' ).on( 'focus', function () {
        var form = $( this ).form;
		$( '.mce-responses .response', form ).hide();
    } );
    
} )( jQuery );
