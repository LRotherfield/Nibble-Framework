$(document).ready(function () {
  $( ".selector" ).sortable({
    receive: function(event, ui) {
      runAjax({
        action: 'activateAndDeactivatePlugins',
        id: ui.item.children("input").val(),
        module: window.location.pathname
      },
      function(result){
      $( "#working" ).dialog( "destroy" );
        if(result == 1){
          message('Success','Bite was woken up',5000,false);
        }
        else if(result == 0){
          message('Success','Bite was put to sleep',5000,false);
        }
        else if(result == 2){
          if(confirm('Removing this Bite will cause other Bites not to function, these Bites will also be put to sleep.  Is that ok?')){
            runAjax({
              action: 'deactivateDependentPlugins',
              id: ui.item.children("input").val(),
              module: window.location.pathname
            },
            function(){
              location.reload();
            });
          }
          else {
            message('Cancelled',"Change to bite status has been cancelled",5000,false);
            $(ui.sender).sortable('cancel');
          }
        }
        else{
          message('Error',result,0,true);
          $(ui.sender).sortable('cancel');
        }
      });
    }
  });
});