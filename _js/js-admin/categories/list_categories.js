addAction('controller_admin_categories', 'list_categories', {
  run: function() {
    $( ".js-image-cell" ).each(function(event) {
        
      // on mouse over display div
      $( this ).mouseover(function() {
          
        divId = $( this ).attr( "data-image-div" );
        $div = $( "#" + divId );
        
        x = event.screenX;
        y = event.screenY;
        
        if ( $div.length ) {
          $div.show();
          $div.css( "left", x+"px");
          $div.css( "top", y+"px" );
        }
        
      });
      
      // on mouse out hide div
      $( this ).mouseout(function() {
        
        divId = $( this ).attr( "data-image-div" );
        $div = $( "#" + divId );
        
        if ($div.length) {
          $div.hide();
        }
          
      });
    });
  }
});