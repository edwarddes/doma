<?php
  include_once(dirname(__FILE__) ."/show_map.controller.php");
  include_once("./include/quickroute_jpeg_extension_data.php");
  
  $controller = new ShowMapController();
  $vd = $controller->Execute();
  $map = $vd["Map"];  
  $QR = $map->GetQuickRouteJpegExtensionData();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print __("PAGE_TITLE")?> :: <?php print strip_tags($vd["Name"])?></title>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <link rel="stylesheet" href="style.css?v=<?php print DOMA_VERSION; ?>" type="text/css" />
  <link rel="icon" type="image/png" href="gfx/favicon.png" />
  <link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php?<?php print Helper::CreateUserQuerystring($vd['user'])?>" />
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css"
    integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw=="
    crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js"
    integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA=="
    crossorigin=""></script>
  <script src="https://unpkg.com/esri-leaflet@2.1.0/dist/esri-leaflet.js"
      integrity="sha512-Tojl3UMd387f6DdAJlo+fKfJZiP55fYT+6Y58yKbHydnueOdSFOxrgLPuUxm7VW1szEt3hZVwv3V2sSUCuT35w=="
      crossorigin=""></script>
  <script type="text/javascript" src="js/jquery/jquery-1.7.1.min.js"></script>  
  <script type="text/javascript" src="js/show_map.js?v=<?php print DOMA_VERSION; ?>"></script>
  <script type="text/javascript" src="js/jquery/jquery.timeago.js"></script>
  <?php 
    $lang = Session::GetLanguageCode();
    if($lang != "" && $lang != "en")
    {
      ?>
      <script type="text/javascript" src="js/jquery/jquery.timeago.<?php print $lang; ?>.js"></script>
      <?php
    }
  ?>
  <script src="js/common.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
  <?php if(isset($vd["OverviewMapData"])) { ?>
    <script src="js/overview_map.js" type="text/javascript"></script>
    <script type="text/javascript">
      <!--
      $(function() { 
        var overviewMapData = <?php print json_encode($vd["OverviewMapData"]); ?>;        
        $("#overviewMap").overviewMap({ data: overviewMapData });
      });
      -->
    </script>
  <?php } ?> 
  <?php if(isset($vd["ProcessRerun"])) {?>
    <?php if($vd["RerunMaps"]!="") {?>
      <script type="text/javascript" src="js/rerun.js?v=<?php print DOMA_VERSION; ?>"></script>
    <?php } else { ?>
      <script type="text/javascript">
        $.get("ajax_server.php?action=saveLastRerunCheck");
      </script>
    <?php }?>
  <?php }?>
  

	<meta property="og:url"		content="<?php print "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" />
	<meta property="og:type"	content="article" />
	<meta property="og:title"	content="<?php print __("PAGE_TITLE")?> :: <?php print $vd["Map"]->Name ?>" />
<?php
	if($vd["Map"]->Comment != "") 
	{
		?><meta property="og:description" content="<?php print $vd["Map"]->Comment ?>"><?php
	}
	else
	{
		?><meta property="og:description" content=" "><?php
	}
?>
	<meta property="og:image"	content="<?php print "http://$_SERVER[HTTP_HOST]"; print Helper::GetThumbnailImage($vd["Map"]); ?>" />

	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="<?php print __("PAGE_TITLE")?> :: <?php  print $vd["Map"]->Name ?>">
	<?php
		if($vd["Map"]->Comment != "") 
		{
			?><meta name="twitter:description" content="<?php print $vd["Map"]->Comment ?>"><?php
		}
		else
		{
			?><meta name="twitter:description" content="<?php print $vd["Map"]->Name ?>"><?php
		}
	?>
	<meta name="twitter:image" content="<?php print "http://$_SERVER[HTTP_HOST]"; print Helper::GetThumbnailImage($vd["Map"]); ?>">
</head>
<body id="showMapBody">
	<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.10";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
	<script>window.twttr = (function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0],
	    t = window.twttr || {};
	  if (d.getElementById(id)) return t;
	  js = d.createElement(s);
	  js.id = id;
	  js.src = "https://platform.twitter.com/widgets.js";
	  fjs.parentNode.insertBefore(js, fjs);

	  t._e = [];
	  t.ready = function(f) {
	    t._e.push(f);
	  };

	  return t;
	}(document, "script", "twitter-wjs"));</script>
<center>
<div id="wrapper">
<?php Helper::CreateTopbar() ?>

<div id="navigation">
  <div class="left">
  <?php if(isset($vd["SecondMapImageName"])) {?>
    <a href="#" id="showSecondImage" title="<?php print __("TOGGLE_IMAGE_TOOLTIP")?>"><?php print __("SHOW_ROUTE_ON_MAP")?></a>
    <a href="#" id="hideSecondImage" title="<?php print __("TOGGLE_IMAGE_TOOLTIP")?>"><?php print __("HIDE_ROUTE_ON_MAP")?></a>
    <span class="separator">|</span>
  <?php }?>
  <?php if(isset($QR) && $QR->IsValid) { ?>
    <a id="showOverviewMap" href="#"><?php print __("SHOW_OVERVIEW_MAP"); ?></a>
    <a id="hideOverviewMap" href="#"><?php print __("HIDE_OVERVIEW_MAP"); ?></a>
    <span class="separator">|</span>
    <a href="export_kml.php?id=<?php print $map->ID; ?>&amp;format=kml" title="<?php print __("KMZ_TOOLTIP"); ?>"><?php print __("KMZ"); ?></a>
    <span class="separator">|</span>
  <?php } ?>
  <a href="<?php print $vd["BackUrl"]?>"><?php print __("BACK")?></a>
  </div>
  <div class="right">
  <?php if($vd["Previous"]) { ?><a href="show_map.php?<?php print Helper::CreateMapQuerystring( $vd["Previous"]->ID)?>"><?php print "&lt;&lt; ". $vd["PreviousName"]; ?></a><?php } ?>
  <?php if($vd["Next"]) { ?><span class="separator">|</span><a href="show_map.php?<?php print Helper::CreateMapQuerystring($vd["Next"]->ID)?>"><?php print $vd["NextName"] ." &gt;&gt;"; ?></a>
  
  <?php } ?>
  </div>
  <div class="clear"></div>
</div>

<div id="content">
<form id="frm" method="post" action="<?php print Helper::SelfPath(); ?>">
<?php if(isset($vd["ProcessRerun"]) && $vd["RerunMaps"]!="") {?>
  <input id="rerun_maps" type="hidden" value="<?php print $vd["RerunMaps"]; ?>" />
  <input id="base_url" type="hidden" value="<?php print BASE_URL; ?>" />
  <input id="rerun_apikey" type="hidden" value="<?php print RERUN_APIKEY; ?>" />
  <input id="rerun_apiurl" type="hidden" value="<?php print RERUN_APIURL; ?>" />  
  <input id="total_rerun_maps" type="hidden" value="<?php print $vd["TotalRerunMaps"]; ?>" />
  <input id="processed_rerun_maps" type="hidden" value="0" />
<?php }?>
<div>
	<?php
	if($map->IsGeocoded)
	{ 
	  print '<input id="staticMapLatitude" type="hidden" value="'.$map->MapCenterLatitude.'" />';
	  print '<input id="staticMapLongitude" type="hidden" value="'.$map->MapCenterLongitude.'" />';
	  print '<input id="kmlURL" type="hidden" value="'.$vd["kmlURL"].'" />';
	  print '<div id="staticMap"></div>';
	}
	?>
	
<div id="mapInfo">
<div id="name"><?php print $vd["Name"]?></div>

<div id="zoomButtonDiv">
  <div id="zoomIn" class="zoomButton"></div>
  <div id="zoomOut" class="zoomButton"></div>
</div>

<div id="propertyContainer">
	<div id="social-buttons" >
		<div class="fb-share-button" data-href="<?php print "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ?>" data-width="200" 	data-type="button" data-size="large" style="vertical-align:top;"></div>

		<a class="twitter-share-button"
			data-size="large"
		  	href="https://twitter.com/intent/tweet">
		Tweet</a>
	</div>
<?php
  print '<div class="property"><span class="caption">'. __("CATEGORY") .":</span> ". $map->GetCategory()->Name .'</div>'; 
  if(__("SHOW_MAP_AREA_NAME") && $map->MapName != "") print '<div class="property"><span class="caption">'. __("MAP_AREA_NAME") .':</span> '. $map->MapName .'</div>';
  if(__("SHOW_ORGANISER") && $map->Organiser != "") print '<div class="property"><span class="caption">'. __("ORGANISER") .':</span> '. $map->Organiser .'</div>';
  if(__("SHOW_COUNTRY") && $map->Country != "") print '<div class="property"><span class="caption">'. __("COUNTRY") .':</span> '. $map->Country .'</div>';
  if(__("SHOW_DISCIPLINE") && $map->Discipline != "") print '<div class="property"><span class="caption">'. __("DISCIPLINE") .':</span> '. $map->Discipline .'</div>';
  if(__("SHOW_RELAY_LEG") && $map->RelayLeg != "") print '<div class="property"><span class="caption">'. __("RELAY_LEG") .':</span> '. $map->RelayLeg .'</div>';
  if(__("SHOW_RESULT_LIST_URL") && $map->ResultListUrl != "") print '<div class="property"><span class="caption"><a href="'. hsc($map->CreateResultListUrl()) .'" target="_blank">'. __("RESULTS") .'</a></span></div>';

if(isset($QR) && $QR->IsValid)
{
	$waypoints = $QR->Sessions[0]->Route->Segments[0]->Waypoints;
	$c1 = 0;
	$c2 = 0;
	$max1 = 0;
	$val = count($waypoints);
	for ($i = 0; $i < $val; $i++) {
		$c1 += $waypoints[$i]->HeartRate;
		$c2 += 1;
		if ($waypoints[$i]->HeartRate > $max1) 
		{
			$max1 = $waypoints[$i]->HeartRate;
		}
	}
	
  if((__("SHOW_DISTANCE"))||(__("SHOW_ELAPSEDTIME"))) 
	{
		if(__("SHOW_DISTANCE") && $map->Distance != "") print '<div class="property"><span class="caption">'. __("DISTANCE") .':</span> '. round(($map->Distance)/1000,2) .' km</div>';
		if(__("SHOW_ELAPSEDTIME") && $map->ElapsedTime != "") print '<div class="property"><span class="caption">'. __("ELAPSEDTIME") .':</span> '. Helper::ConvertToTime($map->ElapsedTime,"MM:SS").'</div>';
	}
	
  if (($c1 != 0)&&((__("SHOW_MAXHR"))||(__("SHOW_AVGHR")))) 
  {
		if(__("SHOW_AVGHR")) print '<div class="property"><span class="caption">'. __("AVGHR") .':</span> '. round($c1/$c2,0).'</div>';
		if(__("SHOW_MAXHR")) print '<div class="property"><span class="caption">'. __("MAXHR") .':</span> '. round($max1,0).'</div>';
	}
    if($map->RerunID && $map->RerunID != 0 && USE_3DRERUN == "1") print '<div class="property"><a href="http://3drerun.worldofo.com/?id='.$map->RerunID.'&type=info" target="_blank">'. __("3DRERUN") .'</a></div>';

}
?>

</div>
<?php 
if($map->Comment != "") 
	print '<div id="comment">'. nl2br($map->Comment) .'</div>'; 
?>

</div>

<div class="clear"></div>
</div>
</form>


<div id="overviewMapContainer"></div>
  
</div>
</div>


<div class="clear">&nbsp;</div>



<div>
  <img id="mapImage" src="<?php print $vd["FirstMapImageName"]; ?>" alt="<?php print hsc(strip_tags($vd["Name"]))?>"<?php if(isset($vd["SecondMapImageName"])) print ' title="'. __("TOGGLE_IMAGE_CLICK") .'" class="toggleable"'; ?>/>
  <?php if(isset($vd["SecondMapImageName"])) { ?>
  <img id="hiddenMapImage" src="<?php print $vd["SecondMapImageName"]; ?>" alt="<?php print hsc(strip_tags($vd["Name"]))?>" <?php if($vd["SecondMapImageName"]) {?>title="<?php print __("TOGGLE_IMAGE_CLICK")?>"<?php }?>/>
  <?php } ?>
  <input type="hidden" id="id" value="<?php print $map->ID; ?>" />
  <input type="hidden" id="imageWidth" value="<?php print $vd["ImageWidth"] ?>" />
  <input type="hidden" id="imageHeight" value="<?php print $vd["ImageHeight"] ?>" />
</div>

</center>
<?php Helper::GoogleAnalytics() ?>
</body>
</html>
