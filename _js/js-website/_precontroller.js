addAction(CONTROLLER_NAME, '_precontroller', function() {
  
  // allow closing of messages
  $( ".close" ).each( function() {
    $( this ).click( function() {
      $( this ).parent().slideUp();
      return false;
    });
  });
  
  //show or hide the div with run queries when the link is clicked
  var bQueriesDisplayed = false;
  if ( $( "#js-query-display" ).length ) {
    $( "#js-query-display" ).click(function(){
      
      if (bQueriesDisplayed) {
        $( "#js-query-container" ).hide();
        $( "#js-query-display" ).html('+');
      }
      else {
        $( "#js-query-container" ).show();
        $( "#js-query-display" ).html('-');
      }
      
      bQueriesDisplayed = !bQueriesDisplayed;
      return false;
    });
  }
  
});