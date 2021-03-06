<?php
  include_once(dirname(__FILE__) ."/include/main.php");

  class UsersController
  {
    public function Execute()
    {
      $viewData = array();

      $errors = array();
      
      $viewData["Users"] = DataAccess::GetAllUsers(!Helper::IsLoggedInAsAdmin());
      
      $viewData["LastMapForEachUser"] = DataAccess::GetLastMapsForUsers("date");
      
      // last x maps
      $numberOfMaps = isset($_GET["lastMaps"]) && is_numeric($_GET["lastMaps"]) 
        ? (int)$_GET["lastMaps"] 
        : (isset($_GET["lastMaps"]) && $_GET["lastMaps"] == "all" ? 999999 : 10);
      $viewData["LastMaps"] = DataAccess::GetMaps(0, 0, 0, 0, null, $numberOfMaps, "createdTime", Helper::GetLoggedInUserID());
      
      $viewData["OverviewMapData"] = null;
      $categories = DataAccess::GetCategoriesByUserID();
      foreach($viewData["LastMaps"] as $map)
      {
        $data = Helper::GetOverviewMapData($map, false, true, true, $categories);
        if($data != null) $viewData["OverviewMapData"][] = $data;
      }

      if(isset($_GET["error"]) && $_GET["error"] == "email") $errors[] = sprintf(__("ADMIN_EMAIL_ERROR"), ADMIN_EMAIL);
      
      $viewData["Errors"] = $errors;
      
      return $viewData;
    }
  }      
 
?>
