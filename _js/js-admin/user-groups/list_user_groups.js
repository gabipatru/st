addAction('controller_admin_user_groups', 'list_user_groups', {
  run: function() {
    var groupId;
    
    // display dialog when clicking on permission link
    $( ".js-user-permission-edit" ).each( function() {
      $( this ).click( function() {
        // make sure the existing permissions are checked
        groupId = $( this ).attr( 'data-user-group-id' );
        $( ".js-task" ).each( function() {
          var taskId = $( this ).attr( 'data-task-id' );
          if ( $( "#permission-"+groupId+"-"+taskId ).length && $( "#permission-"+groupId+"-"+taskId ).val() == 'set' ) {
            $( this ).prop( "checked", true );
            $( this ).closest( "tr" ).removeClass( "font-normal" );
            $( this ).closest( "tr" ).addClass( "font-bold" );
          }
          else {
            $( this ).prop( "checked", false );
            $( this ).closest( "tr" ).removeClass( "font-bold" );
            $( this ).closest( "tr" ).addClass( "font-normal" );
          }
        });
        
        $( "#user-permission-dialog" ).dialog();
      });
    });
    
    // when clicking checkboxes add ore remove bold text
    $( ".js-task" ).each( function() {
      $( this ).click( function() {
        var taskId = $( this ).attr( 'data-task-id' );
        if ( $( "#task-"+taskId ).length && $( "#task-"+taskId ).prop( "checked" ) == true ) {
          $( this ).closest( "tr" ).removeClass( "font-normal" );
          $( this ).closest( "tr" ).addClass( "font-bold" );
        }
        else {
          $( this ).closest( "tr" ).removeClass( "font-bold" );
          $( this ).closest( "tr" ).addClass( "font-normal" );
        }
      });
    });
    
    // save button functionality
    $( "#dialog-permission-save" ).click( function() {
      var permissions = Array();
      // crete array with permissions
      $( ".js-task" ).each( function() {
        var taskId = $( this ).attr( 'data-task-id' );
        if ( $( "#task-"+taskId ).length && $( "#task-"+taskId ).prop( "checked" ) == true ) {
          permissions.push(taskId);
        }
      });
      // set up ajax call
      JQ_AJAX.fetch({
        dataType:  'json',
        url: MVC_MODULE_URL + '/ajax_change_permission.html',
        lock: true,
        spinIn: 'dialog-ajax-permission-spinner',
        params: {
          user_group_id: groupId,
          permissions: permissions,
          token: TOKEN
        },
        onSuccess: function() {
          $( "#user-permission-dialog" ).dialog( "close" );
          location.reload();
        },
        onError: function(errorMesage) {
          $( "#dialog-ajax-permission-error" ).html( errorMesage );
        }
      });
    });
    
  }
});