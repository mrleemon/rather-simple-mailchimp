(function ($) {

    function subscribe( $form ) {
        $.ajax({
            type: 'GET',
            url: $form.attr( 'action' ),
            data: $form.serialize(),
            cache: false,
            dataType: 'json',
            contentType: 'application/json; charset=utf-8',
            error: function( err ) {
                console.log( 'error' );
            },
            success: function( data ) {
                if ( data.result != 'success' ) {
                    $( '.mce-error-response', $form ).show();
                    $( '.mce-error-response', $form ).html( '<p>' + data.msg.substring( 4 ) + '</p>' );
                } else {
                    $( '.mce-success-response', $form ).show();
                    $( '.mce-success-response', $form ).html( '<p>Thank you for subscribing. We have sent you a confirmation email.</p>' );
                }
            }
        });
    }

    $( '.mc-embedded-subscribe-form' ).on( 'submit', function( e ) {
        try {
            var $form = $( this );
            e.preventDefault();
            subscribe( $form );
        } catch ( error ) {
        }
    });

})(jQuery);


