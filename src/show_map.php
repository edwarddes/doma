<?php
  include_once(dirname(__FILE__) ."/show_map.controller.php");
  include_once("./include/quickroute_jpeg_extension_data.php");
  
  $controller = new ShowMapController();
  $vd = $controller->Execute();
  $map = $vd["Map"];  
  $vd['QR'] = $map->GetQuickRouteJpegExtensionData();
  
  $template = $twig->load('showMap.html');
  echo $template->render($vd);
?>