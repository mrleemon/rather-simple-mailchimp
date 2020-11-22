(function ($) {

    function subscribe( $form ) {
        $.ajax({
            type: 'GET',
            url: $form.attr( 'action' ),
            data: $form.serialize(),
            cache: false,
            dataType: 'jsonp',
            contentType: 'application/json; charset=utf-8',
            error: function( err ) {
                console.log( 'error' );
            },
            success: function( data ) {
                if ( data.result != 'success' ) {
                    console.log( data.msg );
                    $( '#mce-error-response', $form ).show();
                    $( '#mce-error-response', $form ).html( '<p>' + data.msg.substring( 4 ) + '</p>' );
                } else {
                    console.log( data.msg );
                    $( '#mce-success-response', $form ).show();
                    $( '#mce-success-response', $form ).html( '<p>Thank you for subscribing. We have sent you a confirmation email.</p>' );
                }
            }
        });
    }

    $( '.mc-embedded-subscribe-form' ).on( 'submit', function( e ) {
        try {
            // define argument as the current form especially if you have more than one
            var $form = $( this );
            // stop open of new tab
            e.preventDefault();
            // submit form via ajax
            subscribe( $form ) ;
        } catch ( error ) {
        }
    });

})(jQuery);


