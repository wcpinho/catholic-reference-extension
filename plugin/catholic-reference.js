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

function popup_by_id( id ) {
    return $( '[@popid=' + id + ']' );
}

$(document).ready( function() {
    
    $( '.scripture_reference' ).hover(
        function( event ) {
            var id = $( this ).attr( 'refid' );
            do_popup( popup_by_id( id ), event );
        },
        function() {
            var id = $( this ).attr( 'refid' );
            var popup = popup_by_id( id );
            setTimeout(
                function() {
                    if( ! cathref_popup_activated[ id ] ) {
                        hide_popup( popup );
                    }
                },
                1500
            );
        }
    );
    $( '.scripture_popup' ).hover(
        function() {
            var id = $( this ).attr( 'popid' );
            cathref_popup_activated[ id ] = true;
        },
        function() {
            hide_popup( $( this ) );
        }
    );
} );
