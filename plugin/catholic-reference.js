var cathref_popup_activated = new Object;
var cathref_popup_timers = new Object;
var cathref_popup_showing = new Object;

function hide_popup( obj ) {
    if( obj != null ) {
        if( obj.css( 'opacity' ) >= 0.80 ) {
            id = obj.attr( 'popid' );
            if( cathref_popup_showing[ id ] ) {
                cathref_popup_showing[ id ] = false;
                cathref_popup_activated[ id ] = false;
                obj.fadeTo( 'slow', 0.0 );
            }
        }
    }
}

function show_popup( obj, event ) {
    cathref_popup_showing[ obj.attr( 'popid' ) ] = true;
    obj.css( 'top',  event.pageY + 10 );
    obj.css( 'left', event.pageX + 10 );
    obj.fadeTo( 'slow', 0.85 );
}

function popup_by_id( id ) {
    return $( '[@popid=' + id + ']' );
}

$(document).ready( function() {
    
    $( '.scripture_reference' ).hover(
        function( event ) {
            var id = $( this ).attr( 'refid' );
            clearTimeout( cathref_popup_timers[ id ] );
            show_popup( popup_by_id( id ), event );
        },
        function() {
            var id = $( this ).attr( 'refid' );
            var popup = popup_by_id( id );
            cathref_popup_timers[ id ] = setTimeout(
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
