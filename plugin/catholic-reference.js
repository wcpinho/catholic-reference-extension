var cathref_popup_activated = new Object;
var cathref_popup_timers = new Object;
var cathref_popup_showing = new Object;
var cathref_ref_timers = new Object;

function hide_popup( id, type ) {
    var obj = popup_by_id( id, type );
    if( obj != null ) {
        if( obj.css( 'opacity' ) >= 0.80 ) {
            id = obj.attr( 'popid' );
            if( cathref_popup_showing[ id ] ) {
                cathref_popup_showing[ id ] = false;
                cathref_popup_activated[ id ] = false;
                obj.fadeOut( 'slow' );
                var shadow = shadow_by_id( id, type );
                shadow.fadeOut( 'fast' );
            }
        }
    }
}

function show_popup( id, event, type ) {
    var obj = popup_by_id( id, type );
    cathref_popup_showing[ id ] = true;
    
    obj.css( 'top',  event.pageY + 10 );
    obj.css( 'left', event.pageX + 10 );
    obj.fadeIn( 'fast' );
    
    var shadow = shadow_by_id( id, type );
    shadow.css( 'top',  event.pageY + 20 );
    shadow.css( 'left', event.pageX + 20 );
    shadow.fadeIn( 'slow' );
}

function popup_by_id( id, type ) {
    return $( 'div.' + type + '_popup[@popid="' + id + '"]' );
}
function shadow_by_id( id, type ) {
    return $( 'div.' + type + '_popup_shadow[@popid="' + id + '"]' );
}

$(document).ready( function() {
    
    $( '.scripture_reference' ).hover(
        function( event ) {
            var id = $( this ).attr( 'refid' );
            cathref_ref_timers[ id ] = setTimeout(
                function() {
                    clearTimeout( cathref_popup_timers[ id ] );
                    if( ! cathref_popup_showing[ id ] ) {
                        show_popup( id, event, 'scripture' );
                    }
                },
                200
            );
        },
        function() {
            var id = $( this ).attr( 'refid' );
            clearTimeout( cathref_ref_timers[ id ] );
            cathref_popup_timers[ id ] = setTimeout(
                function() {
                    if( ! cathref_popup_activated[ id ] ) {
                        hide_popup( id, 'scripture' );
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
            hide_popup( $( this ).attr( 'popid' ), 'scripture' );
        }
    );
    
    $( '.ccc_reference' ).hover(
        function( event ) {
            var id = $( this ).attr( 'refid' );
            cathref_ref_timers[ id ] = setTimeout(
                function() {
                    clearTimeout( cathref_popup_timers[ id ] );
                    if( ! cathref_popup_showing[ id ] ) {
                        show_popup( id, event, 'ccc' );
                    }
                },
                200
            );
        },
        function() {
            var id = $( this ).attr( 'refid' );
            clearTimeout( cathref_ref_timers[ id ] );
            cathref_popup_timers[ id ] = setTimeout(
                function() {
                    if( ! cathref_popup_activated[ id ] ) {
                        hide_popup( id, 'ccc' );
                    }
                },
                1500
            );
        }
    );
    $( '.ccc_popup' ).hover(
        function() {
            var id = $( this ).attr( 'popid' );
            cathref_popup_activated[ id ] = true;
        },
        function() {
            hide_popup( $( this ).attr( 'popid' ), 'ccc' );
        }
    );
    
    
    $( '.close_button' ).click(
        function() {
            var regexp = /^([^_]+)_/;
            var match = ($( this ).parent().parent().attr( 'class' ) ).match( regexp );
            hide_popup(
                $( this ).attr( 'closeid' ),
                match[ 1 ]
            );
        }
    );
    $( '.close_button' ).hover(
        function() {
            $( this ).children( '.close_button_highlight' ).fadeIn( 'fast' );
        },
        function() {
            $( this ).children( '.close_button_highlight' ).fadeOut( 'slow' );
        }
    )
} );
