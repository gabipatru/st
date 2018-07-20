addAction( 'controller_user', 'newuser', {
    run: function() {
        $( "#newUser" ).validate( aFormValidate );
        
        // password strength meter
        $( "#password" ).on('input', function() {
            // get the strength
            Strength = zxcvbn( $( this ).val() );
            
            // update the meter and the text
            $( "#password-strength-meter" ).val( Strength.score );
        })
    },
});