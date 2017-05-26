<?php
  include_once(dirname(__FILE__) ."/include/main.php");
  include_once(dirname(__FILE__) ."/include/neon.php");
  
  
  class OAuthController
  {
    public function Execute()
    {
		$neon = new Neon();
		
        $viewData = array();  
  
        $errors = array();
		
		$parameters = array();

		if ( isset( $_GET['code'] ) ) 
		{
		    $parameters['client_id']     = '';
		    $parameters['client_secret'] = '';
		    $parameters['redirect_uri']  = '127.0.0.1/doma/oauth.php';
		    $parameters['code']          = $_GET['code']; // Get code from URL Parameter
		    $parameters['grant_type']    = 'authorization_code'; // required, fixed value

		    // Convert the parameters array to URLEncoded string
		    $parameters = http_build_query($parameters);
			
		    /* HTTP POST request to NeonCRM */
		    $url = 'https://www.z2systems.com/np/oauth/token'; // Always use this URL
		    $ch = curl_init();
		    curl_setopt($ch,CURLOPT_URL, $url);
		    curl_setopt($ch,CURLOPT_POST, TRUE);
		    curl_setopt($ch,CURLOPT_POSTFIELDS, $parameters);
		    curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
		    curl_setopt($ch,CURLOPT_HTTPHEADER,array('Content-Type: application/x-www-form-urlencoded')); // Must specify content type in header
		    $result = curl_exec($ch);
		    curl_close($ch);
			
			//{"error":"invalid_request","error_description":"Authorization code expired."}
			$accessTokenResponse = json_decode($result);
			if($accessTokenResponse->{'error'})
			{
				$errors[] = $accessTokenResponse->{'error_description'};
		        $viewData["Errors"] = $errors;
		        return $viewData;
			}
			
			$neon->login();
			$membershipData = $neon->getIndividualAccount(json_decode($result)->{'access_token'});
			
			
			if($membershipData['status']!="SUCCESS")
			{
				$errors[] = $membershipData['Errors'];
			}
			else
			{
				$isNewUser = false;
	            if(!Helper::LoginUserByAccountId($membershipData['accountId']))
	            {
					$isNewUser = true;
					$newUser = Helper::CreateAndSaveDefaultUser();
					$newUser->AccountID = $membershipData['accountId'];
					$newUser->Save();
					
					Helper::LoginUserByAccountId($membershipData['accountId']);
				}
				Helper::UpdateUserInfo($membershipData);
				$neon->logout();
				
				$isNewUser ? Helper::Redirect("edit_user.php") :
					Helper::Redirect("index.php?". Helper::CreateUserQuerystring(getCurrentUser()));
			}
			
			$viewData['membershipData'] = $membershipData;
		}
		else
		{
			$errors[] = "no OAuth code";
		}
		
		$neon->logout();
        $viewData["Errors"] = $errors;
        return $viewData;
    }
  }
?>
