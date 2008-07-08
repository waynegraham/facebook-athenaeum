<?php
/**
 * Project: Facebook Athenaeum
 * Date: $Date$
 * File: FBAthenaeum.php
 * Version: $Id$
 * 
 */

class FBAthenaeum {
	var $sql 	= null;
	var $tpl 	= null;
	var $error 	= null;
	var $facebook = null;
	var $uid = null;
	
	function FBAthenaeum(){
		$this->sql =& new FBTools_SQL;
		$this->tpl =& new FBTools_Smarty;
		$this->facebook =& new FBTools_FB();
	}
	/**
	 * Require that the user be logged into facebook and on a canvas page
	 * then define the application name and callback URL so that it will 
	 * be available to the templates. 
	 */
	function requireFacebook(){
		$this->uid = $this->facebook->require_login();
		$this->facebook->require_frame();
		$this->tpl->assign('uid', $this->uid);
		$this->tpl->assign('canvas', $GLOBALS['facebook_config']['canvas_url_end']);
		$this->tpl->assign('tabsMenu', $GLOBALS['facebook_tabs']);
		$this->tpl->assign('app_name', $GLOBALS['APP_NAME']);
		$this->tpl->assign('callback', $GLOBALS['facebook_config']['callback_url']);
		$this->tpl->assign('uacct', $GLOBALS['GOOGLE_ANALYTICS_KEY']);
	}
	
	/*
	 * Create a line in the database for the user so that they can set locations
	 */
	function installIfNeeeded()
	{
		$this->requireFacebook();
		$this->sql->query(
			"SELECT uid FROM locations WHERE uid=".$this->uid.";",
			SQL_INIT,
			SQL_ASSOC
		);
		
		if(empty($this->sql->record))
		{
			$this->sql->query(
			"INSERT INTO locations SET uid=".$this->uid.";"
			);
		}
	}
	
	/**
	 * Display the search library page
	 * Usually doubles as the homepage
	 *
	 */
	function displaySearchLibrary(){
		$this->requireFacebook();
		$this->tpl->assign('search', $GLOBALS['SEARCH']);
		$this->tpl->assign('RSSFeed', $GLOBALS['FEED_URL']);
		$this->tpl->display('SearchLibrary.tpl');
	}
/*
	 * Return the user's current location
	 */
	function getMyLocation()
	{
		$query = "SELECT floor,x,y FROM locations 
					WHERE uid=".$this->uid." AND 
					updated >= (DATE_ADD(NOW(), INTERVAL -".$GLOBALS['LOCATION_VALID_TIME']." HOUR));";
		$this->sql->query($query, SQL_INIT, SQL_ASSOC);
		
		return $this->sql->record;
	}
	/**
	 * Return the locations for friends
	 *
	 * @param unknown_type $friends
	 * @return unknown
	 */
	function getLocations($friends = array(), $floor){
		$query_string = "SELECT uid, x, y FROM locations WHERE floor=".$floor." AND NOT uid=".$this->uid." AND (1=1";
		foreach($friends as $friend)
		{
			$query_string .= " OR uid=".$friend;
		}
		$query_string .= ") AND updated >= (DATE_ADD(NOW(), INTERVAL -".$GLOBALS['LOCATION_VALID_TIME']." HOUR));";		

		$this->sql->query(
			$query_string,
			SQL_ALL,
			SQL_ASSOC
		);
		
		return $this->sql->record;
	}
	
	/**
	 * Add a location to the database
	 *
	 * @param array $formvars
	 */
	function updateLocation($formvars){
		$_query = sprintf(
			"UPDATE locations SET
			x= %d, y=%d, floor=%d, updated=NOW()
			WHERE uid=%d;", 
			(int)$formvars['x'], 
			(int)$formvars['y'], 
			(int)$formvars['floor'],
			(int)$formvars['fb_sig_user']
		);
		
		return $this->sql->query($_query);
	}
	
	/*
	 * Clears a location by setting the date stamp to the unix epoch.
	 */
	function clearLocation($uid)
	{
		$query = "UPDATE locations SET updated=0 WHERE uid=".$uid.";";
		return $this->sql->query($query) or die(mysql_error() );
	}
	
	/*
	 * Display the friend locator.
	 */
	function displayFriendLocator($floor = null)
	{
		if(!isset($floor['f']))
			$floor = $GLOBALS['DEFAULT_FLOOR'];
		else
			$floor = $floor['f'];
		$this->tpl->assign('myLoc', $this->getMyLocation());
		$this->tpl->assign('floor', $floor);
		$this->tpl->assign('maps', $GLOBALS['Floor_Map']);
		$this->requireFacebook();
		$this->tpl->assign('friend', $this->getLocations($this->facebook->api_client->friends_getAppUsers(), $floor));
		$this->tpl->display('FriendLocator.tpl');
	}
	
	/**
	 * Display the hours 
	 *
	 */
	function displayHours($data = array()){
		$this->requireFacebook();
		$this->tpl->display('Hours.tpl');
	}
	
	/**
	 * Display RSS news
	 *
	 */
	function displayNews(){
		$this->requireFacebook();
		$this->tpl->assign('RSSFeed', $GLOBALS['FEED_URL']);
		$this->tpl->display('News.tpl');
	}
	
	/**
	 * Searches the Google appliance then displays the results.
	 * Designed to output only using Facebook's mock AJAX
	 * 
	 * @param array $formvars The input from the form - passed from index.php
	*/
	function searchWebsite($formvars=null)
	{
		if(isset($formvars['Catalog']))
			header("Location: ".$GLOBALS['SEARCH']['CATALOG'].$formvars['q']);

		if(isset($formvars['Databases']))
			header("Location: ".$GLOBALS['SEARCH']['DATABASE'].$formvars['q']);
	
		$xmlFile = implode("", file( $GLOBALS['SEARCH']['WEBSITE'].$formvars['q']));
		$xml = new SimpleXMLElement($xmlFile);
		
		$i=0;
		foreach($xml->RES->R as $results)
		{
			$searchResults[$i] = array(
				'U' => (string)$results->U,
				'T' => (string)$results->T
			);
			$i++;
		}
		if(!isset($searchResults))
		$searchResults = "";
		
		$this->tpl->assign("results", $searchResults);
		$this->tpl->display('searchResults.tpl');
	}
}
?>