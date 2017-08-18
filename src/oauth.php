<?php
  include_once(dirname(__FILE__) ."/oauth.controller.php");

  $controller = new OAuthController();
  $data = $controller->Execute();
  
  $template = $twig->load('oauth.html');
  echo $template->render($data);
?>