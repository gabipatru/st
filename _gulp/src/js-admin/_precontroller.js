addAction(CONTROLLER_NAME, '_precontroller', function() {
    // allow closing of messages
    $( ".close" ).each( function() {
        $( this ).click( function() {
            $( this ).parent().slideUp();
            return false;
        });
    });
    
    // submit GF forms when selects are changed
    $( ".js-gfselect" ).each( function() {
    	$( this ).change( function() {
    		$( this ).closest('form').submit();
    		return false;
    	});
    });
});