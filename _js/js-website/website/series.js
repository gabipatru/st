addAction('controller_website', 'series', {
  run: function() {
    
    // filter the surprises by groups
    $( "#filterGroup" ).change( function() {
      var groupId = $( this ).val();
      $( ".js-surprise" ).each( function() {
        var surpriseGroupId = $( this ).attr( "data-group-id" );
        if (groupId != 0) {
          if ( surpriseGroupId == groupId ) {
            $( this ).show();
          }
          else {
            $( this ).hide();
          }
        }
        else {
          $( this ).show();
        }
      });
    });
  },
});