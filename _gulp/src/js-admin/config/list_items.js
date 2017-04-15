addAction('controller_admin_config', 'list_items', {
    run: function() {
        $( ".js-slide" ).each( function(){
            $( this ).click( function(){
                // get the element to be slided
                $elem = $( this ).parent().next();
                $imgdiv = $(this).children(".right");
                
                // slide it up or down
                if ( $elem.attr( "data-slided" ) == 'true') {
                    $elem.slideUp();
                    $imgdiv.removeClass('img-close-box');
                    $imgdiv.addClass('img-open-box');
                    $elem.attr( "data-slided", "false" );
                }
                else {
                    $elem.slideDown();
                    $imgdiv.removeClass('img-open-box');
                    $imgdiv.addClass('img-close-box');
                    $elem.attr( "data-slided", "true" );
                }
                
                return false;
            });
        });
    },
});