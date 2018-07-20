$( window ).on( "error", function( evt ) {
    // get the javascript event
    var e = evt.originalEvent;
    if ( DEBUGGER_AGENT ) {
        if ( e.message ) { 
            alert( "JavaScript Error:\n\t" + e.message );
        } 
        else {
            alert( "JavaScript Error:\n\t" + e.type );
        }
    }
});