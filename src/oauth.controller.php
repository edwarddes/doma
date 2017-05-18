<?php
  include_once(dirname(__FILE__) ."/include/main.php");
  
  class OAuthController
  {
  	public function __construct()
  	{
  		$this->organization_id = "ousa";
  		$this->api_key         = "";
  		$this->base_api_url    = 'https://api.neoncrm.com/neonws/services/api/';
  	}
	
	private function neon_login()
	{
		$this->ch    = curl_init();
		$url         = $this->base_api_url . 'common/login';
		$parameters  = 'login.apiKey='.$this->api_key;
		$parameters .= '&login.orgId='.$this->organization_id;
		$parameters .= '&responseType=json';

		$this->set_curl_options($url,$parameters);

		$result = json_decode(curl_exec($this->ch));
		$this->session_id = $result->loginResponse->userSessionId;
	}

	private function neon_logout()
	{
		$url         = $this->base_api_url . 'common/logout';
		$parameters  = 'userSessionId='.$this->session_id;
		$parameters .= '&responseType=json';

		$this->set_curl_options($url,$parameters);

		$result = json_decode(curl_exec($this->ch));
		curl_close($this->ch);
	}
	
	private function set_curl_options($url,$parameters)
	{
		curl_setopt($this->ch,CURLOPT_URL, $url);
		curl_setopt($this->ch,CURLOPT_POST, TRUE);
		curl_setopt($this->ch,CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($this->ch,CURLOPT_POSTFIELDS, $parameters);
	}
	
	public function get_individual_membership_data($acct_id)
	{	
		$mmbr_data = array();
		//just fill the fields with default values
		$mmbr_data['status']          = 'NA';
		$mmbr_data['responseTime']    = '2000-01-01';
		$mmbr_data['accountId']       = 0;
		$mmbr_data['firstName']       = '';
		$mmbr_data['lastName']        = '';
		$mmbr_data['email']      	  = '';

		$this->neon_login();

		$url         = $this->base_api_url . 'account/retrieveIndividualAccount';
		$parameters  = 'responseType=json';
		$parameters .= '&userSessionId='.$this->session_id;
		$parameters .= '&accountId='.$acct_id;

		$this->set_curl_options($url,$parameters);

		$neon_data = json_decode(curl_exec($this->ch));
		$this->neon_logout();
		
		$individualAccount = isset( $neon_data->retrieveIndividualAccountResponse->individualAccount) 
												?  $neon_data->retrieveIndividualAccountResponse->individualAccount 
												: null;
		
		$mmbr_data['status']          = $neon_data->retrieveIndividualAccountResponse->operationResult;
		$mmbr_data['responseTime']       = $neon_data->retrieveIndividualAccountResponse->responseDateTime;
		$mmbr_data['accountId']      = isset($individualAccount->accountId)
										? $individualAccount->accountId : "";
		$mmbr_data['firstName']      = isset($individualAccount->primaryContact->firstName) 
										? $individualAccount->primaryContact->firstName 
										: "";
		$mmbr_data['lastName']      = isset($individualAccount->primaryContact->lastName) 
										? $individualAccount->primaryContact->lastName 
										: "";
		$mmbr_data['email']      = isset($individualAccount->primaryContact->email1) 
										? $individualAccount->primaryContact->email1 
										: "";

		return $mmbr_data;
	}
	
    public function Execute()
    {
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
			
			$membershipData = $this->get_individual_membership_data(json_decode($result)->{'access_token'});
			
			if($membershipData['status']!="SUCCESS")
			{
				$errors[] = $membershipData['Errors'];
			}
			else
			{
	            if(Helper::LoginUserByAccountId($membershipData['accountId']))
	            { 
					Helper::Redirect("index.php?". Helper::CreateQuerystring(getCurrentUser()));
	            }
	            $errors[] = "invalid userID from NEON";
				
			}
			$viewData['membershipData'] = $membershipData;
		}
		else
		{
			$errors[] = "no OAuth code";
		}
		
        $viewData["Errors"] = $errors;
        return $viewData;
    }
  }
?>
