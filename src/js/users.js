$(document).ready(function() 
{
  $(".thumbnailHoverLink").mouseover(function() 
  {
    var x = $(".hoverThumbnail", $(this).parent()).show();
  });

  $(".thumbnailHoverLink").mouseout(function() 
  {
    $(".hoverThumbnail", $(this).parent()).hide();
  });

    toggleOverviewMap();

});

function toggleOverviewMap()
{
  var mapExists = $("#overviewMap").length > 0;
  
  if(mapExists)
  {
    $("#overviewMap").toggle();
  }
  else
  {
    var overviewMap = $('<div id="overviewMap"/>');
    $("#overviewMapContainer").append(overviewMap);
    overviewMap.overviewMap({ data: overviewMapData });
  }
}
