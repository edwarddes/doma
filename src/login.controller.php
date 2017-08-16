<?php
  include_once(dirname(__FILE__) ."/include/main.php");
  
  class LoginController
  {
    public function Execute()
    {
      $viewData = array();  
  
      $errors = array();

      if(Helper::IsLoggedInAsAdmin() && isset($_GET["loginAsUser"]))
      {
        // login as a certain user and redirect to his page
        if(Helper::LoginUserByID($_GET["loginAsUser"]))
        {
          Helper::Redirect("index.php?". Helper::CreateUserQuerystring(getCurrentUser()));
        }
      }
	  
      if(isset($_GET["action"]) && $_GET["action"] == "logout")
      {
        $location = "index.php?". Helper::CreateUserQuerystring(getCurrentUser());
        Helper::LogoutUser();
        Helper::Redirect($location);
	  }
      
      $viewData["Errors"] = $errors;
      return $viewData;
    }
  }
?>
