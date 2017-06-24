addAction('controller_admin_email', 'email_queue', {
	run: function() {
		$( ".js-show-email-body" ).each(function() {
			$( this ).click(function() {
			    var emailBody = atob($( this ).attr( "data-body" ));
			    var Window = window.open("", "EmailWindow", "width=700,height=500");
			    Window.document.write( emailBody );
			    return false;
			});
		});
	}
});