$cathref_current_popup = null;
$cathref_active = false;

function hide_popup( callback ) {
    if( $cathref_current_popup != null ) {
        $cathref_current_popup.fadeOut(
            'slow',
            callback
        );
        $cathref_active = false;
        $cathref_current_popup = null;
    }
}

function show_popup( obj ) {
    if( $cathref_current_popup != null ) {
        hide_popup(
            function( event ) {
                $cathref_current_popup = obj;
                obj.fadeIn();
            }
        );
    } else {
        $cathref_current_popup = obj;
        obj.css( 'top',  event.pageY + 10 );
        obj.css( 'left', event.pageX + 10 );
        obj.fadeIn();
    }
}

$(document).ready( function() {
    
    $( '.scripture_reference' ).hover(
        function() {
            show_popup( $( this ).next( '.scripture_popup' ) );
        },
        function() {
            var popup = $( this ).next( '.scripture_popup' );
            popup.animate(
                {opacity: 0.85},
                1500,
                null,
                function() {
                    if( ! $cathref_active ) {
                        hide_popup();
                    }
                }
            )
        }
    );
    $( '.scripture_popup' ).hover(
        function() {
            $cathref_active = true;
        },
        function() {
            hide_popup();
        }
    );
} );
