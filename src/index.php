<?php
  include_once(dirname(__FILE__) ."/include/main.php");
  include_once(dirname(__FILE__) ."/index.controller.php");
  include_once(dirname(__FILE__) ."/include/json.php");
  
  $controller = new IndexController();
  $vd = $controller->Execute();
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
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css"
    integrity="sha512-wcw6ts8Anuw10Mzh9Ytw4pylW8+NAD4ch3lqm9lzAsTxg0GFeJgoAtxuCLREZSC5lUXdVyo/7yfsqFjQ4S+aKw=="
    crossorigin=""/>
  <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet.js"
    integrity="sha512-mNqn2Wg7tSToJhvHcqfzLMU6J4mkOImSPTxVZAdo+lcPlk+GhZmYgACEe0x35K7YzW1zJ7XyJV/TT1MrdXvMcA=="
    crossorigin=""></script>
  <script src="https://unpkg.com/esri-leaflet@2.1.0/dist/esri-leaflet.js"
      integrity="sha512-Tojl3UMd387f6DdAJlo+fKfJZiP55fYT+6Y58yKbHydnueOdSFOxrgLPuUxm7VW1szEt3hZVwv3V2sSUCuT35w=="
      crossorigin=""></script>
  <script src="js/overview_map.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
  <?php if($vd["DisplayMode"] == "overviewMap") { ?>
    <script type="text/javascript">
      <!--
      $(function() { 
        var overviewMapData = <?php print json_encode($vd["OverviewMapData"]); ?>;        
        $("#overviewMap").overviewMap({ data: overviewMapData });
      });
      -->
    </script>
  <?php } ?>
  <script src="js/index.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
  <script src="js/common.js?v=<?php print DOMA_VERSION; ?>" type="text/javascript"></script>
</head>

<body id="indexBody">
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

<div>
  <img id="logo" src="gfx/book.png" alt="" />
</div>

<div id="rssIcon"><a href="rss.php?<?php print Helper::CreateUserQuerystring(getCurrentUser())?>"><img src="gfx/feed-icon-28x28.png" alt="<?php print __("RSS_FEED")?>" title="<?php print __("RSS_FEED")?>" /></a></div>

<div id="intro">
<h1><?php print hsc(getCurrentUser()->FirstName ." ".getCurrentUser()->LastName) ?></h1>
<h1><?php print __("CAPTION")?></h1>
<p><?php print nl2br(__("INTRO"))?></p>

<div id="selectCategoryAndYear">
<?php 
  if(count($vd["YearsWithText"]) < 2)
  {
    print __("NO_MAPS");
  }
  else
  {
  ?>  
  <label for="categoryID"><?php print __("SELECT_CATEGORY")?>:</label>
  <select name="categoryID" id="categoryID">
  <?php
    foreach($vd["CategoriesWithText"] as $category)
    {
      print '<option value="'. $category->ID .'"'. ($vd["SearchCriteria"]["selectedCategoryID"] == $category->ID? ' selected="selected"' : '') .'>'. $category->Name .'</option>';
    }
  ?>
  </select>

  <label for="year"><?php print __("SELECT_YEAR")?>:</label>
  <select name="year" id="year">
  <?php
    foreach($vd["YearsWithText"] as $year)
    {
      print '<option value="'. $year["value"] .'"'. ($vd["SearchCriteria"]["selectedYear"] == $year["value"] ? ' selected="selected"' : '') .'>'. $year["text"] .'</option>';
    }
  ?>
  </select>

  <label for="filter"><?php print __("SELECT_FILTER"); ?>:</label>
  <input type="text" name="filter" id="filter" value="<?php print hsc($vd["SearchCriteria"]["filter"]); ?>"/>

  <?php if($vd["GeocodedMapsExist"]) { ?>
    <label for="displayMode"><?php print __("SELECT_DISPLAY_MODE"); ?>:</label>
    <select name="displayMode" id="displayMode">
      <option value="list"<?php if($vd["DisplayMode"] == "list") print ' selected="selected"'; ?>><?php print __("DISPLAY_MODE_LIST")?></option>
      <option value="overviewMap"<?php if($vd["DisplayMode"] == "overviewMap") print ' selected="selected"'; ?>><?php print __("DISPLAY_MODE_OVERVIEW_MAP")?></option>
    </select>
  <?php } ?>
<?php } ?>
</div>
</div>

<div id="maps">

<?php if(count($vd["Maps"]) == 0 && count($vd["YearsWithText"]) > 1) { ?>
  <p class="clear">
  <?php print __("NO_MATCHING_MAPS"); ?>
  </p>
<?php } ?>

<?php
  if($vd["DisplayMode"] == "list") include("index_list.php");  
  if($vd["DisplayMode"] == "overviewMap") include("index_overview_map.php");  
?>
  </div>
<div class="clear"></div>

<p id="footer"><?php print __("FOOTER")?></p>

</form>
</div>
</div>
<?php Helper::GoogleAnalytics() ?>
</body>
</html>
