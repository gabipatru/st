addAction('controller_admin_email', 'email_log', {
    run: function() {
        $( ".js-show-error-info" ).each(function() {
            $( this ).click(function() {
                var emailBody = atob($( this ).attr( "data-body" ));
                var Window = window.open("", "EmailWindow", "width=700,height=500");
                Window.document.write( emailBody );
                return false;
            });
        });
        
        $( ".js-show-debug" ).each(function() {
            $( this ).click(function() {
                var emailBody = atob($( this ).attr( "data-body" ));
                var Window = window.open("", "EmailWindow1", "width=700,height=500");
                Window.document.write( emailBody );
                return false;
            });
        });
    }
});