<?php
  include_once(dirname(__FILE__) ."/include/main.php");
  //include_once(dirname(__FILE__) ."/version.controller.php");
  
  //$controller = new VersionController();
  //$vd = $controller->Execute();
  
  $template = $twig->load('version.html');
  echo $template->render();
?>