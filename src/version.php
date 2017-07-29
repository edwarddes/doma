<?php
  include_once(dirname(__FILE__) ."/include/main.php");
  //include_once(dirname(__FILE__) ."/version.controller.php");
  
  //$controller = new VersionController();
  //$vd = $controller->Execute();
?>
<?php print '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <title><?php print __("PAGE_TITLE")?></title>
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <link rel="icon" type="image/png" href="gfx/favicon.png" />
  <link rel="stylesheet" href="style.css?v=<?php print DOMA_VERSION; ?>" type="text/css" />
  <link rel="alternate" type="application/rss+xml" title="RSS" href="rss.php?<?php print Helper::CreateUserQuerystring(getCurrentUser())?>" />
  <script src="js/jquery/jquery-1.7.1.min.js" type="text/javascript"></script>
  <script src="js/overview_map.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
  <script src="js/index.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
  <script src="js/common.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
</head>

<body id="versionBody">
<div id="wrapper">
<?php Helper::CreateTopbar() ?>
<div id="content">
<form method="get" action="<?php print Helper::SelfPath()?>?<?php print Helper::CreateUserQuerystring(getCurrentUser())?>">
<input type="hidden" name="user" value="<?php print getCurrentUser()->ID;?>"/>
<?php if(count($vd["Errors"]) > 0) { ?>
<ul class="error">
<?php
  foreach($vd["Errors"] as $e)
  {
    print "<li>$e</li>";
  }
?>
</ul>
<?php } ?>

<p>
	OUSA doma is based on <a href="http://www.matstroeng.se/doma/?version=<?php print DOMA_VERSION; ?>"><?php printf(__("DOMA_VERSION_X"), DOMA_VERSION); ?></a>
</p>
<p>Source is hosted on <a href="https://github.com/edwarddes/doma">GitHub</a></p>
<div class="clear"></div>

<p id="footer"><?php print __("FOOTER")?></p>

</form>
</div>
</div>
<?php Helper::GoogleAnalytics() ?>
</body>
</html>
