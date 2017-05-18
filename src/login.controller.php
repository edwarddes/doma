<?php
  include_once(dirname(__FILE__) ."/include/main.php");
  
  class LoginController
  {
    public function Execute()
    {
      $viewData = array();  
  
      $errors = array();

      // no user specified - redirect to user list page
      if(!getCurrentUser()) Helper::Redirect("users.php");

      // user is hidden - redirect to user list page
      if(!getCurrentUser()->Visible) Helper::Redirect("users.php");

      if(isset($_POST["cancel"]))
      {
        Helper::Redirect("index.php?". Helper::CreateQuerystring(getCurrentUser()));
      }

      if(isset($_GET["action"]) && $_GET["action"] == "logout")
      {
        $location = "index.php?". Helper::CreateQuerystring(getCurrentUser());
        Helper::LogoutUser();
        Helper::Redirect($location);
	  }
      
      $viewData["Errors"] = $errors;
      return $viewData;
    }
  }
?>
