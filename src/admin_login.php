<?php
  include_once(dirname(__FILE__) ."/admin_login.controller.php");
  
  $controller = new AdminLoginController();
  $vd = $controller->Execute();
  
  $template = $twig->load('adminLogin.html');
  echo $template->render($vd);
?>
