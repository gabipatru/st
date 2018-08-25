addAction('controller_admin_users', 'list_users', {
  run: function() {
    var userId;
    // display dialog when clicking on modify link
    $( ".js-user-list-edit" ).each( function() {
      $( this ).click( function() {
        userId = $( this ).attr( 'data-user-id' );
        $( "#dialog-username" ).html( $( this ).attr( 'data-user-username' ) );
        $( "#dialog-email" ).html( $( this ).attr( 'data-user-email' ) );
        $( "#dialog-status" ).html( $( this ).attr( 'data-user-status' ) );
        $( "#dialog-new-status" ).val( $( this ).attr( 'data-user-status' ) );
        $( "#dialog-new-user-group" ).val( $( this ).attr( 'data-user-group' ) );
        $( "#user-edit-dialog" ).dialog();
        return false;
      });
    });
    
    // save button functionality
    $( "#dialog-save" ).click( function() {
      // set up ajax call
      JQ_AJAX.fetch({
        dataType:  'json',
        url:    MVC_MODULE_URL + '/ajax_change_status.html',
        lock:    true,
        spinIn:    'dialog-ajax-spinner',
        params:    {
            user_id: userId, 
            new_status: $( "#dialog-new-status" ).val(), 
            new_user_group_id: $( "#dialog-new-user-group" ).val(),
            token: TOKEN
        },
        onSuccess:   function() {
          $( "#user-edit-dialog" ).dialog( "close" );
          location.reload();
        },
        onError:   function(errorMesage) {
          $( "#dialog-ajax-error" ).html( errorMesage );
        }
      });
    });
  }
});