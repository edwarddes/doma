<?php
  include_once(dirname(__FILE__) ."/include/main.php");
  include_once(dirname(__FILE__) ."/index.controller.php");
  include_once(dirname(__FILE__) ."/include/json.php");
  
  $controller = new IndexController();
  $vd = $controller->Execute();
  
  $template = $twig->load('index.html');
  echo $template->render($vd);
?>
