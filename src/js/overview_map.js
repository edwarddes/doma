(function($) {
  $.fn.overviewMap = function(options)
  {
    $(this).each(function() 
	{
      var mapElement = $(this);
      var mapBounds = new L.LatLngBounds();
      var markers = [];
      var borderPolygons = [];
      var routePolylines = [];
	  
	  var map = L.map(mapElement.get(0));	
	  L.esri.basemapLayer('Topographic').addTo(map);
	  map.on('zoomstart',function(e) { zoomChanged();});
      var lastZoom = -1;
      var zoomLimit = 10;
      
      // get bounds of maps
      for(var i in options.data)
      {
        var data = options.data[i];
        
        // the map borders for large scale overview map
        var vertices =         
        [
          new L.LatLng(data.Corners[0].Latitude, data.Corners[0].Longitude),
          new L.LatLng(data.Corners[1].Latitude, data.Corners[1].Longitude),
          new L.LatLng(data.Corners[2].Latitude, data.Corners[2].Longitude),
          new L.LatLng(data.Corners[3].Latitude, data.Corners[3].Longitude),
          new L.LatLng(data.Corners[0].Latitude, data.Corners[0].Longitude)
        ];
		var borderPolygon = L.polygon(vertices, 
		{
			color: data.BorderColor,
			weight: data.BorderWidth,
			opacity: data.BorderOpacity,
			fillColor: data.FillColor,
			fillOpacity: data.FillOpacity
		}).addTo(map);
        borderPolygon.Data = data;
		if(data.TooltipMarkup != null)
			borderPolygon.bindTooltip(data.TooltipMarkup).addTo(map);
		
        borderPolygons.push(borderPolygon);
        
        // the map as an icon for small scale overview map
		var marker = new L.Marker(
			[data.MapCenter.Latitude, data.MapCenter.Longitude],
			{icon: L.icon({iconUrl: "gfx/control_flag.png",iconSize: [16, 16]})});
		marker.bindTooltip(data.TooltipMarkup).openTooltip();
        marker.Data = data;
        markers.push(marker);
		
		
        // tooltips
        if(data.TooltipMarkup != null)
        {
          marker.on('click', function(e) 
		  {
            window.location = this.Data.Url.replace('&amp;', '&')
          });
          marker.on('mouseover', function(e) 
          { 
			  this.setIcon(new L.icon({iconUrl: "gfx/control_flag_highlighted.png",iconSize: [16, 16]}));
          });
          marker.on('mouseout', function(e)
          { 
            this.setIcon(new L.icon({iconUrl: "gfx/control_flag.png",iconSize: [16, 16]}));
          });
		  
          borderPolygon.on('click', function(e) 
		  {
            window.location = this.Data.Url.replace('&amp;', '&');
          });
          borderPolygon.on('mouseover', function(e) 
          { 
            this.setStyle({ color: this.Data.SelectedBorderColor, fillColor: this.Data.SelectedFillColor });
          });
          borderPolygon.on('mouseout', function(e)
          { 
            this.setStyle({ color: this.Data.BorderColor, fillColor: this.Data.FillColor });
          });
        }
		
		
        // the route lines (if data.RouteCoordinates is present)
        if(data.RouteCoordinates != null)
        {
          for(var i in data.RouteCoordinates)
          {
            var points = new Array(data.RouteCoordinates[i].length);
            for(var j in data.RouteCoordinates[i])
            {
              var vertex = data.RouteCoordinates[i][j];
              points[j] = new L.LatLng(vertex[1], vertex[0]);
            }
            var polyline = new L.Polyline(points,
			{
				color: data.RouteColor, 
				weight: data.RouteWidth, 
				opacity: data.RouteOpacity 
			}).addTo(map);
            routePolylines.push(polyline);
          }
        }
        
        // make sure all maps fits in overview map
        mapBounds.extend(vertices[0]);
        mapBounds.extend(vertices[1]);
        mapBounds.extend(vertices[2]);
        mapBounds.extend(vertices[3]);
      }
      
      map.fitBounds(mapBounds);
	  
      function zoomChanged()
      {
        var zoom = map.getZoom();
        
        if(zoom < zoomLimit && (lastZoom >= zoomLimit || lastZoom == -1))
        {
          for(var i in borderPolygons)
          {
            borderPolygons[i].remove();
          }
          for(var i in routePolylines)
          {
            routePolylines[i].remove();
          }
          for(var i in markers)
          {
            markers[i].addTo(map);
          }
        }
        if(zoom >= zoomLimit && (lastZoom < zoomLimit || lastZoom == -1))
        {
          for(var i in markers)
          {
            markers[i].remove();
          }
          for(var i in borderPolygons)
          {
            borderPolygons[i].addTo(map);
          }
          for(var i in routePolylines)
          {
            routePolylines[i].addTo(map);
          }
        }
        lastZoom = zoom;
      }  
	    
    });
  };
})(jQuery);

var Tooltip=function(){
    var id = 'tt';
    var top = 12;
    var left = 12;
    var maxw = 600;
    var speed = 15;
    var timer = 20;
    var endalpha = 85;
    var alpha = 0;
    var tt,h;
    var ie = document.all ? true : false;
    return{
        show:function(v,w){
            if(tt == null){
                tt = document.createElement('div');
                tt.setAttribute('id',id);
                document.body.appendChild(tt);
                tt.style.opacity = 0;
                tt.style.filter = 'alpha(opacity=0)';
                document.onmousemove = this.pos;
            }
            tt.style.display = 'block';
            tt.innerHTML = v;
            tt.style.width = w ? w + 'px' : 'auto';
            if(!w && ie){
                tt.style.width = tt.offsetWidth;
            }
            if(tt.offsetWidth > maxw){tt.style.width = maxw + 'px'}
            h = parseInt(tt.offsetHeight) + top;
            clearInterval(tt.timer);
            tt.timer = setInterval(function(){Tooltip.fade(1)},timer);
        },
        pos:function(e){
            var u = ie ? event.clientY + document.documentElement.scrollTop : e.pageY;
            var l = ie ? event.clientX + document.documentElement.scrollLeft : e.pageX;
            tt.style.top = (u - h) + 'px';
            tt.style.left = (l + left) + 'px';
        },
        fade:function(d){
            var a = alpha;
            if((a != endalpha && d == 1) || (a != 0 && d == -1)){
                var i = speed;
                if(endalpha - a < speed && d == 1){
                    i = endalpha - a;
                }else if(alpha < speed && d == -1){
                    i = a;
                }
                alpha = a + (i * d);
                tt.style.opacity = alpha * .01;
                tt.style.filter = 'alpha(opacity=' + alpha + ')';
            }else{
                clearInterval(tt.timer);
                if(d == -1){tt.style.display = 'none'}
            }
        },
        hide:function(){
          if(tt != null)
          {
            clearInterval(tt.timer);
            tt.timer = setInterval(function(){Tooltip.fade(-1)},timer);
          }          
        }
    };
}();