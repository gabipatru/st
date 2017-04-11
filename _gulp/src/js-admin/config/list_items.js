addAction('controller_admin_config', 'list_items', {
    run: function() {
        $( ".js-slide" ).each( function(){
            $( this ).click( function(){
                // get the element to be slided
                $elem = $ ( this ).parent().next();
                
                // slide it up or down
                if ( $elem.attr( "data-slided" ) == 'true') {
                    $elem.slideUp();
                    $elem.attr( "data-slided", "false" );
                }
                else {
                    $elem.slideDown();
                    $elem.attr( "data-slided", "true" );
                }
                
                return false;
            });
        });
    },
});