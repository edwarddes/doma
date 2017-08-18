<?php
  include_once(dirname(__FILE__) ."/create.controller.php");

  $controller = new CreateController();
  $vd = $controller->Execute();  
  
  $template = $twig->load('create.html');
  echo $template->render($vd);
?>