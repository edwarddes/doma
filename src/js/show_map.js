$(document).ready(function() 
{ 
  $("abbr.timeago").timeago();
  
	reloadStaticMap();
	loadMap($("#mapContainer"));
  
});

function toggleOverviewMap(mapContainer)
{
  var id = $("#id").val();
  var mapExists = $("#overviewMap", mapContainer).length > 0;
  
  if(mapExists)
  {
    $("#overviewMap").toggle();
  }
  else
  {
    var mapDiv = $('<div id="overviewMap"/>');
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
	if($("#staticMap").length)
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

function loadMap()
{
	var mapImageURL = $("#mapURL").val();
	var secondMapImageURL = $("#secondMapURL").val();
	var mapWidth = $("#mapWidth").val();
	var mapHeight = $("#mapHeight").val();
	var ratio = mapWidth/mapHeight
	var map = L.map('mapContainer', {
	    crs: L.CRS.Simple,
		minZoom: 0
	});
	var bounds = [[0,0], [750,750*ratio]];
	var primaryMapLayer = L.imageOverlay(mapImageURL, bounds)
	if(secondMapImageURL)
	{
		var secondaryMapLayer = L.imageOverlay(secondMapImageURL, bounds).addTo(map);
		L.control.layers({}, {"Route":primaryMapLayer},{"collapsed":false}).addTo(map);
	}
	primaryMapLayer.addTo(map);
	map.fitBounds(bounds);
}

$(function() {
  $("#showOverviewMap,#hideOverviewMap").click(function() {
    toggleOverviewMap($("#overviewMapContainer"));
    return false;
  });
});

