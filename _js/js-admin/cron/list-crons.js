addAction('controller_admin_cron', 'list_crons', {
  run: function() {
    var cronId;
    // display dialog when clicking on modify link
    $( ".js-cron-edit" ).each( function() {
      $( this ).click( function() {
        cronId = $( this ).attr( 'data-cron-id' );
        $( "#dialog-script" ).html( $( this ).attr( 'data-cron-script' ) );
        $( "#dialog-new-interval" ).val( $( this ).attr( 'data-cron-interval' ) );
        $( "#dialog-new-status" ).val( $( this ).attr( 'data-cron-status' ) );
        $( "#cron-edit-dialog" ).dialog();
        return false;
      });
    });

    // save button functionality
    $( "#dialog-save" ).click( function() {
      var interval = parseInt( $( "#dialog-new-interval" ).val() );
      if ( interval == 0 || Number.isNaN( interval ) ) {
        $( "#dialog-error" ).html( __( 'Interval must be a number' ) );
        return false;
      }
      // set up ajax call
      JQ_AJAX.fetch({
        dataType:  'json',
        url:    MVC_MODULE_URL + '/ajax_edit_cron.html',
        lock:    true,
        spinIn:    'dialog-ajax-spinner',
        params:    {
          cron_id: cronId,
          new_status: $( "#dialog-new-status" ).val(),
          new_interval: $( "#dialog-new-interval" ).val(),
          token: TOKEN
        },
        onSuccess:   function() {
          $( "#cron-edit-dialog" ).dialog( "close" );
          location.reload();
        },
        onError:   function( errorMesage ) {
          $( "#dialog-ajax-error" ).html( errorMesage );
        }
      });
    });
  }
});