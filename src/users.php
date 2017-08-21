<?php
  include_once(dirname(__FILE__) ."/users.controller.php");
  
  $controller = new UsersController();
  $vd = $controller->Execute();
  
  $template = $twig->load('users.html');
  echo $template->render($vd);
?>