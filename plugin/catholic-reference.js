var cathref_popup_activated = new Object;
var cathref_popup_locked = new Object;

function hide_popup( obj ) {
    if( obj != null ) {
        cathref_popup_locked[ obj.attr( 'id' ) ] = true;
        obj.fadeOut(
            'slow',
            function() {
                cathref_popup_locked[ obj.attr( 'id' ) ] = false;
            }
        );
    }
}

function do_popup( obj, event ) {
    obj.css( 'top',  event.pageY + 10 );
    obj.css( 'left', event.pageX + 10 );
    obj.fadeIn();
}

function show_popup( obj, event ) {
    do_popup( obj, event );
}

$(document).ready( function() {
    
    $( '.scripture_reference' ).hover(
        function( event ) {
            do_popup( $( this ).next( '.scripture_popup' ), event );
        },
        function() {
            var popup = $( this ).next( '.scripture_popup' );
            setTimeout(
                function() {
                    if( ! cathref_popup_activated[ popup.attr( 'id' ) ] ) {
                        hide_popup( popup );
                    }
                },
                1500
            );
        }
    );
    $( '.scripture_popup' ).hover(
        function() {
            cathref_popup_activated[ $( this ).attr( 'id' ) ] = true;
        },
        function() {
            hide_popup( $( this ) );
        }
    );
} );
