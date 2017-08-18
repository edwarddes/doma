<?php
  include_once(dirname(__FILE__) ."/edit_map.controller.php");

  $controller = new EditMapController();
  $vd = $controller->Execute();

  $template = $twig->load('editMap.html');
  echo $template->render($vd);
?>