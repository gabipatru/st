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
    
  // hook cancel button to dialog closing
  $( ".js-dialog-cancel" ).each( function() {
    $( this ).click( function() {
      dialogId = $( this ).parent().attr( 'data-dialog' );
      $( "#"+dialogId ).dialog( "close" );
    });
  });
  
  // show or hide the div with run queries when the link is clicked
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