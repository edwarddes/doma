$(document).ready(function() 
{

  $("#zoomIn").click(function() 
  {
    ZoomIn();
  });
  
  $("#zoomOut").click(function() 
  {
    ZoomOut();
  });

  $("#showSecondImage,#hideSecondImage,#mapImage").click(function() 
  {
    ToggleImage();
  });
  
  
  $("abbr.timeago").timeago();
    
    reloadGM();
  
});

var zoom = 1;

function ZoomIn()
{
  zoom *= 1.25;
  $("#mapImage").get(0).width = zoom * $("#imageWidth").val();
  $("#mapImage").get(0).height = zoom * $("#imageHeight").val();
}

function ZoomOut()
{
  zoom /= 1.25;
  $("#mapImage").get(0).width = zoom * $("#imageWidth").val();
  $("#mapImage").get(0).height = zoom * $("#imageHeight").val();
}

function ToggleImage()
{
  var mapImage = $("#mapImage").get(0).src;
  var hiddenMapImageControl = $("#hiddenMapImage");
  
  if(hiddenMapImageControl.length > 0)
  {
    var hiddenMapImage = hiddenMapImageControl.get(0).src;
    $("#mapImage").get(0).src = hiddenMapImage;
    $("#hiddenMapImage").get(0).src = mapImage;
    $("#showSecondImage").toggle();
    $("#hideSecondImage").toggle();
  }
}

function toggleOverviewMap(mapContainer)
{
  var id = $("#id").val();
  var mapExists = $(".overviewMap", mapContainer).length > 0;
  
  if(mapExists)
  {
    $(".overviewMap").toggle();
  }
  else
  {
    var mapDiv = $('<div class="overviewMap"/>');
    var loadingIcon = $('<div class="loadingIcon"/>');
    mapDiv.append(loadingIcon);
    mapContainer.append(mapDiv);
    $.getJSON(
      'ajax_server.php', 
      { action: 'getMapCornerPositionsAndRouteCoordinates', id: id }, 
      function(data)
      { 
        loadingIcon.remove();
        mapDiv.overviewMap({ data: [data] });
      });
  }
  $("#showOverviewMap").toggle();
  $("#hideOverviewMap").toggle();
}

function reloadGM()
{
  if($("#gmap").size())
  {
    $("#gmap").html("<a href='"+$("#gmap_url").val()+"' target='_blank'><img src='http://maps.googleapis.com/maps/api/staticmap?center="+$("#gmap_coordinates").val()+"&zoom=6&amp;size=300x174"+"&maptype=terrain&markers=color:red%7C"+$("#gmap_coordinates").val()+"&sensor=false&language="+$("#gmap_lang").val()+"'></a>");
  }
}

$(function() {
  $("#showOverviewMap,#hideOverviewMap").click(function() {
    toggleOverviewMap($("#overviewMapContainer"));
    return false;
  });
});

