addAction(CONTROLLER_NAME, '_precontroller', function() {
    // allow closing of messages
    $( ".close" ).each( function() {
        $( this ).click( function() {
            $( this ).parent().slideUp();
            return false;
        });
    });
});