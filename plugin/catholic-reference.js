var cathref_popup_activated = new Object;
var cathref_popup_timers = new Object;
var cathref_popup_showing = new Object;
var cathref_ref_timers = new Object;

function extract_type_from_class( obj ) {
    var regexp = /^([^_]+)_/;
    var match = ( obj.attr( 'class' ) ).match( regexp );
    return match[ 1 ];
}

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

function reference_activated( event ) {
    var this_obj = $( this );
    var id = $( this ).attr( 'refid' );
    cathref_ref_timers[ id ] = setTimeout(
        function() {
            clearTimeout( cathref_popup_timers[ id ] );
            if( ! cathref_popup_showing[ id ] ) {
                show_popup(
                    id,
                    event,
                    extract_type_from_class( this_obj )
                );
            }
        },
        200
    );
}
function reference_deactivated() {
    var this_obj = $( this );
    var id = $( this ).attr( 'refid' );
    clearTimeout( cathref_ref_timers[ id ] );
    cathref_popup_timers[ id ] = setTimeout(
        function() {
            if( ! cathref_popup_activated[ id ] ) {
                hide_popup( id, extract_type_from_class( this_obj ) );
            }
        },
        1500
    );
}

$(document).ready( function() {
    
    $( '.scripture_popup' ).hover(
        function() {
            var id = $( this ).attr( 'popid' );
            cathref_popup_activated[ id ] = true;
        },
        function() {
            hide_popup( $( this ).attr( 'popid' ), 'scripture' );
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
    
    
    $( '.cathref_close_button' ).click(
        function() {
            var parent = $( this ).parent().parent();
            hide_popup(
                $( this ).attr( 'closeid' ),
                extract_type_from_class( parent )
            );
        }
    );
    $( '.cathref_close_button' ).hover(
        function() {
            $( this ).children( '.cathref_close_button_highlight' ).fadeIn( 'fast' );
        },
        function() {
            $( this ).children( '.cathref_close_button_highlight' ).fadeOut( 'slow' );
        }
    );
    
    /* -------------------------
       Admin
    */
    
    $( '.cathref_config input' ).click(
        function() {
            $( '.cathref_config_notice' ).html( '&nbsp;' );
        }
    );
} );
