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
  
	reloadStaticMap();
  
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

function reloadStaticMap()
{
	if($("#staticMap").size())
	{
		var staticMap = L.map('staticMap',{ zoomControl:false }).setView([$("#staticMapLatitude").val(),$("#staticMapLongitude").val()], 6);
		//L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {
		//    attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
		//}).addTo(staticMap);	
		L.esri.basemapLayer('Topographic').addTo(staticMap);
		var marker = L.marker([$("#staticMapLatitude").val(),$("#staticMapLongitude").val()]).addTo(staticMap);

		staticMap.on('click', function(e)
		{
			window.location = $("#kmlURL").val()
		});
		
		staticMap.dragging.disable();
		staticMap.touchZoom.disable();
		staticMap.doubleClickZoom.disable();
		staticMap.scrollWheelZoom.disable();
		staticMap.boxZoom.disable();
		staticMap.keyboard.disable();
		if (staticMap.tap) map.tap.disable();
		$("#staticMap").css( 'cursor', 'pointer' );
	}
}

$(function() {
  $("#showOverviewMap,#hideOverviewMap").click(function() {
    toggleOverviewMap($("#overviewMapContainer"));
    return false;
  });
});

