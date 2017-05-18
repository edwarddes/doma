<?php
  include_once(dirname(__FILE__) ."/oauth.controller.php");

  $controller = new OAuthController();
  $data = $controller->Execute();
?>
<?php print '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <link rel="stylesheet" href="style.css?v=<?php print DOMA_VERSION; ?>" type="text/css" />
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <link rel="icon" type="image/png" href="gfx/favicon.png" />
  <script type="text/javascript" src="js/jquery/jquery-1.7.1.min.js"></script>
  <script src="js/common.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
</head>

<body id="loginBody">
<div id="wrapper">
<?php Helper::CreateTopbar() ?>
<div id="content">


<h1>OAuth</h1>

<?php if(count($data["Errors"]) > 0) { ?>
<ul class="error">
<?php
  foreach($data["Errors"] as $e)
  {
    print "<li>$e</li>";
  }
?>
</ul>
<?php } ?>

<?php
print_r($data);
?>

</div>
</div>
</body>
</html>