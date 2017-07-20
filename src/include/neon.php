<?php
/*
 * NeonCRM PHP API Library
 * http://github.com/z2systems/neon-php
 * Learn more about the API at http://help.neoncrm.com/api
 * Learn more about NeonCRM at http://www.z2systems.com
 * Authored by Colin Pizarek
 * http://github.com/colinpizarek
 */
 
class Neon 
{
  /*
   * Abstracted HTTP request, used by other class methods
   */
  private function api($request) {
    $method = $request['method'];
    $parameters = $request['parameters'];
    $url = 'https://api.neoncrm.com/neonws/services/api/' . $method;
    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL, $url);
    curl_setopt($ch,CURLOPT_POST, TRUE);
    curl_setopt($ch,CURLOPT_POSTFIELDS, $parameters);
    curl_setopt($ch,CURLOPT_RETURNTRANSFER, TRUE);
    //curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Required for WAMP only
    $result = curl_exec($ch);
    curl_close($ch);
    $result = json_decode($result, TRUE);
    return $result;
  }
  
  /*
   * Retrieves the session ID
   */
  private function getSession() 
  {
	  return $this->sessionID;
  }
  
  /*
   * Saves the session ID
   */
  private function setSession($session) 
  {
	  $this->sessionID = $session;
  }
  
  /*
   * Executes a login and stores the Session ID.
   */
  public function login() 
  {
	  $organizationID = "";
	  $apiKey         = "";
	
	  $request = array();
	  $request['method'] = 'common/login';
	  $request['parameters'] = '&login.apiKey=' . $apiKey . '&login.orgid=' . $organizationID;
	  $response = $this->api($request);
	  reset($response);
	  $first_key = key($response);
	  $response = $response[$first_key];
	  if ($response['operationResult'] == 'SUCCESS') {
	    $this->setSession($response['userSessionId']);
	    return $response;
	  } 
	  else {
	    return $response;
	  } 
  }
  
  public function logout()
  {
	  return $this->go( array( 'method' => 'common/logout' ) );
  }
  
  
  /*
   * General purpose API request to be executed after login
   */
  public function go($request) {
    if (isset($request['method'])) {
      $str = null;
      if (isset($request['parameters'])) {
        $str = http_build_query($request['parameters']);
        }
      $parameters = 'responseType=json&userSessionId=' . $this->getSession() . '&' . $str;
      $build = array();
      $build['method'] = $request['method'];
      $build['parameters'] = $parameters;
      $go = $this->api($build);
      reset($go);
      $first_key = key($go);
      $go = $go[$first_key];
      return $go;
    }
  }
  /*
   * search
   * works with listaccounts, listmemberships, listdonations, etc
   */
  public function search($request) {
    $standard = null;
    $custom = null;
    $criteria = null;
    $paging = null;
    if (isset($request['method'])) {
      if (isset($request['columns']['standardFields'])) {
        foreach ($request['columns']['standardFields'] as $std) {
          $std = str_replace(' ','%20',$std);
          $standard = $standard . '&outputfields.idnamepair.id=&outputfields.idnamepair.name=' . $std;
          }
      }
      if (isset($request['columns']['customFields'])) {
        foreach ($request['columns']['customFields'] as $cus) {
          $cus = str_replace(' ','%20',$cus);
          $custom = $custom . '&outputfields.idnamepair.name=&outputfields.idnamepair.id=' . $cus;
          }
        }
      if (isset($request['criteria'])) {
        foreach ($request['criteria'] as $crit) {
          $key = '&searches.search.key=' . $crit[0];
          $operator = '&searches.search.searchOperator=' . $crit[1];
          $value = '&searches.search.value=' . $crit[2];
          $criteria = $criteria . $key . $operator . $value;
          $criteria = str_replace(' ','%20',$criteria);
          }
        }
      if (isset($request['page']['currentPage'])) {
        $paging = $paging . '&page.currentPage=' . $request['page']['currentPage'];
        }
      if (isset($request['page']['pageSize'])) {
        $paging = $paging . '&page.pageSize=' . $request['page']['pageSize'];
        }
      if (isset($request['page']['sortColumn'])) {
        $paging = $paging . '&page.sortColumn=' . str_replace(' ','%20',$request['page']['sortColumn']);
        }
      if (isset($request['page']['sortDirection'])) {
        $paging = $paging . '&page.sortDirection=' . $request['page']['sortDirection'];
        }
      $addon = 'responseType=json&userSessionId=' . $this->getSession();
      $parameters = $addon . $criteria . $standard . $custom . $paging;
      $build = array();
      $build['method'] = $request['method'];
      $build['parameters'] = $parameters;
      $go = $this->api($build);
      $go = $this->parseListRequest($go);
      return $go;
    } else {
    return null;
    }
  }

	public function authenticateUser($ousa_username,$ousa_password)
	{
		$response =  $this->go( array( 
			'method' => 'common/authenticateUser',
			'parameters' => array('username' => $ousa_username,'password' => $ousa_password)) );
			
		if ($response['operationResult'] == "SUCCESS")
		{
			return $response['accountId'];
		}
		return null;
	}
  
	public function getIndividualAccount($accountID)
	{	
		$mmbr_data = array();
		//just fill the fields with default values
		$mmbr_data['status']          = 'FAIL';
		$mmbr_data['responseTime']    = '2000-01-01';
		$mmbr_data['accountId']       = 0;
		$mmbr_data['firstName']       = '';
		$mmbr_data['lastName']        = '';
		$mmbr_data['email']      	  = '';
		
		$response =  $this->go( array( 
			'method' => 'account/retrieveIndividualAccount',
			'parameters' => array('accountId' => $accountID)) );
		
		$mmbr_data['status']          = $response['operationResult'];
		$mmbr_data['responseTime']       = $response['responseDateTime'];
		
		$individualAccount = isset( $response['individualAccount']) ?  $response['individualAccount'] : null;

		$mmbr_data['accountId']      = isset($individualAccount['accountId'])
										? $individualAccount['accountId'] : "";
		$mmbr_data['firstName']      = isset($individualAccount['primaryContact']['firstName']) 
										? $individualAccount['primaryContact']['firstName'] 
										: "";
		$mmbr_data['lastName']      = isset($individualAccount['primaryContact']['lastName']) 
										? $individualAccount['primaryContact']['lastName'] 
										: "";
		$mmbr_data['email']      = isset($individualAccount['primaryContact']['email1']) 
										? $individualAccount['primaryContact']['email1'] 
										: "";
		return $mmbr_data;
	}
  
  
  /*
   * Parses the server response for list requests
   */
  private function parseListRequest($data) {
    reset($data);
    $first_key = key($data);
    $data = $data[$first_key];
    $result = array();
    if ($data['operationResult'] == 'SUCCESS') {
      $people = array();
      foreach ($data['searchResults']['nameValuePairs'] as $key => $value) {
        $people[$key] = $value;
        foreach ($people as $person) {
          foreach ($person['nameValuePair'] as $pair) {
            if (isset($pair['name'])) {
              $name = $pair['name'];
            } else {
              $name = null;
            }
            if (isset($pair['value'])) {
              $value = $pair['value'];
            } else {
              $value = null;
            }
            $data['searchResults'][$key][$name] = $value;
          }
        }
      }
      unset($data['searchResults']['nameValuePairs']);
      return $data;
    } 
    else {
      return $data;
    }
  }
}
?>