<?php
  include_once(dirname(__FILE__) ."/login.controller.php");

  $controller = new LoginController();
  $vd = $controller->Execute();
?>
<?php print '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <link rel="stylesheet" href="style.css?v=<?php print DOMA_VERSION; ?>" type="text/css" />
  <meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
  <title><?php print (__("PAGE_TITLE") ." :: ". __("LOGIN"))?></title>
  <link rel="icon" type="image/png" href="gfx/favicon.png" />
  <script type="text/javascript" src="js/jquery/jquery-1.7.1.min.js"></script>
  <script src="js/common.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
</head>

<body id="loginBody">
<div id="wrapper">
<?php Helper::CreateTopbar() ?>
<div id="content">
<form>

<h1><?php print __("LOGIN")?></h1>

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

<div class="container">
<script>
                (function(w,d,t,s,p,c,r,u){a=d.createElement(t);a.async=1;
                    a.src=u+s;es=d.getElementsByTagName(t);e=es[es.length-1];
                    e.parentNode.insertBefore(a,e);w[p]=c;w[r]=u;
		})(window,document,'script','/js/oauth/npsi.js','client_id',
                'zd17Stpsdmpuyh2/TozYtYglWLg6LHxx3t2+ECvmrDmKZdnb2QTbuu10+3ym8/y106LsDlsRniY=','domain',
                'https://secure.orienteeringusa.org/np');

		setting = {	
                    "redirect_uri":"http://127.0.0.1/doma/oauth.php" //required
		}		
		c_css = {
			"label":"Sign In with OUSA"
		}
	  </script>
</div>
</form>

</div>
</div>
</body>
</html>
