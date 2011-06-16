function runAjax (data_obj,callback,data_type){
  if (data_type == null) {
        data_type = "html";
    }
  $( "#working" ).dialog({
			height: 140,
      width:200,
			modal: true
		});
  $.ajax({
    url:"/script/ajax.php",
    dataType: data_type,
    data: data_obj,
    success: function(data) {
      if ( typeof(callback) == "function") {
        callback(data);
      }
    }
  });
}

(function($) {
  var cache = [];
  // Arguments are image paths relative to the current page.
  $.preLoadImages = function() {
    var args_len = arguments.length;
    for (var i = args_len; i--;) {
      var cacheImage = document.createElement('img');
      cacheImage.src = arguments[i];
      cache.push(cacheImage);
    }
  }
})(jQuery)

function message(message_title,message_text,lifetime,sticky,message_type){
  jQuery.noticeAdd({
    text: message_text,
    title: message_title,
    type: message_type,
    stayTime: lifetime,
    stay: sticky
  });

  return false;
}
$(document).ready(function () {
  $('.message').each(function(){
    if($(this).html() != ''){
      message($(this).find('input[name="title"]').val(),$(this).find('input[name="message"]').val(),$(this).find('input[name="stayTime"]').val(),$(this).find('input[name="stay"]').val(),$(this).find('input[name="type"]').val());
      $(this).remove();
    }
  });
  $('.delete').click(function(){
    if(confirm('Are you sure you want to '+$(this).attr('title'))){
      runAjax({
        action: 'delete',
        id: $(this).closest('div.content').find('input[name="id"]').val(),
        module: window.location.pathname
      },
      function(result){
        $( "#working" ).dialog( "destroy" );
        message('Message',result,3000,false);
      });
      $(this).closest('div.content').prev().remove();
      $(this).closest('div.content').remove();
      return false;
    } else{
      return false;
    }
  });

  //Start accordion
  var accordion = $("#accordion");
  var index = $.cookie("accordion"+window.location.pathname);
  var active;
  if (index !== null) {
    active = accordion.find("h2:eq(" + index + ")");
  } else {
    active = 0
  }
  accordion.accordion({
    header: "h2",
    event: "click hoverintent",
    active: active,
    collapsible: true,
    change: function(event, ui) {
      var index = $(this).find("h2").index ( ui.newHeader[0] );
      $.cookie("accordion"+window.location.pathname, index, {
        path: "/"
      });
    },
    autoHeight: false
  });
  $('.confirm').click(function(){
    if(!confirm('Are you sure you want to '+$(this).attr('title'))){
      return false
    }
  });
  //Start tabs
  $("#tabs").tabs();
  //Start sortable lists
  $("ul.droptrue").sortable({
    connectWith: 'ul.droptrue',
    items: 'li:not(.disabled)'
  });
  jQuery.preLoadImages("/graphic/overlay.png");
  // Disable selections in lists
  $("#sortable1, #sortable2").disableSelection();
  $('.toggle-hide').hide();
  $('.toggle').click(function(){
    $(this).next().toggle(500);
  });
});
