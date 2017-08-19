<?php
  include_once(dirname(__FILE__) ."/edit_user.controller.php");
  
  $controller = new EditUserController();
  $vd = $controller->Execute();
  
  $template = $twig->load('editUser.html');
  echo $template->render($vd);
?>