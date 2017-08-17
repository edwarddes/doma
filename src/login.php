<?php
  include_once(dirname(__FILE__) ."/login.controller.php");

  $controller = new LoginController();
  $vd = $controller->Execute();
  
  $template = $twig->load('login.html');
  echo $template->render($vd);
?>
