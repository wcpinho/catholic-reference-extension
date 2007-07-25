$(document).ready( function() {
    $( '.scripture_reference,.ccc_reference' ).hover(
        reference_activated,
        reference_deactivated
    );
} );
