$cathref_current_popup = null;
$cathref_active = false;
$cathref_timeout = null;

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

function do_popup( obj, event ) {
    $cathref_current_popup = obj;
    obj.css( 'top',  event.pageY + 10 );
    obj.css( 'left', event.pageX + 10 );
    obj.fadeIn();
}

function show_popup( obj, event ) {
    if( $cathref_current_popup != null ) {
        hide_popup(
            function() {
                do_popup( obj, event );
            }
        );
    } else {
        do_popup( obj, event );
    }
}

$(document).ready( function() {
    
    $( '.scripture_reference' ).hover(
        function( event ) {
            show_popup( $( this ).next( '.scripture_popup' ), event );
        },
        function() {
            var popup = $( this ).next( '.scripture_popup' );
            $cathref_timeout = setTimeout(
                function() {
                    if( ! $cathref_active ) {
                        hide_popup();
                    }
                },
                1500
            );
        }
    );
    $( '.scripture_popup' ).hover(
        function() {
            $cathref_active = true;
            clearTimeout( $cathref_timeout );
        },
        function() {
            hide_popup();
        }
    );
} );
