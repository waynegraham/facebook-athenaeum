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
		$this->facebook->require_login();
		$this->facebook->require_frame();
		
		/*
		 * When the facebook stops sending the fb_sig_in_new_facebook parameter
		 * this section, and the FriendLocator.tpl templates will need to be 
		 * updated.
		 */
		
		$this->tpl->assign('uid', $this->facebook->user);
		$this->tpl->assign('uacct', $GLOBALS['GOOGLE_ANALYTICS_KEY']);
		$this->tpl->assign('canvas', $GLOBALS['facebook_config']['canvas_url_end']);
		$this->tpl->assign('tabsMenu', $GLOBALS['facebook_tabs']);
		$this->tpl->assign('app_name', $GLOBALS['APP_NAME']);
		$this->tpl->assign('callback', $GLOBALS['facebook_config']['callback_url']);
	}
	
	/*
	 * Create a line in the database for the user so that they can set locations
	 */
	function installIfNeeeded()
	{
		$this->requireFacebook();
		$this->sql->query(
			"SELECT uid FROM locations WHERE uid=".$this->facebook->user.";",
			SQL_INIT,
			SQL_ASSOC
		);
		
		if(empty($this->sql->record))
		{
			$this->sql->query(
			"INSERT INTO locations SET uid=".$this->facebook->user.";"
			);
			$FBML = "<fb:subtitle>My Location</fb:subtitle>";
			$FBML .= $GLOBALS['NOT_HERE_MESSAGE'];
			$this->facebook->api_client->profile_setFBML(null, $this->facebook->user, null, $FBML, $FBML, $FBML);
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
					WHERE uid=".$this->facebook->user." AND 
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
		$query_string = "SELECT uid, x, y FROM locations WHERE floor=".$floor." AND (1=0";
		if($friends != ""){
			foreach($friends as $friend)
			{
				$query_string .= " OR uid=".$friend;
			}
		}
		$query_string .= ") AND updated >= (DATE_ADD(NOW(), INTERVAL -".$GLOBALS['LOCATION_VALID_TIME']." HOUR)) AND NOT uid=".$this->facebook->user.";";		
		
		$this->sql->query(
			$query_string,
			SQL_ALL,
			SQL_ASSOC
		) or die(mysql_error());
		
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
			$this->facebook->user
		);
		
		
		
		if($formvars['oldfloor'] != $formvars['floor'])
		{
			$FBML = "<fb:subtitle>My Location</fb:subtitle>";
			$FBML .= $GLOBALS['Floor_Map'][$formvars['floor']]['message'];
			$this->facebook->api_client->profile_setFBML(null, $this->facebook->user, null, $FBML, $FBML, $FBML);
		}
		
		return $this->sql->query($_query);
	}
	
	/*
	 * Clears a location by setting the date stamp to the unix epoch.
	 */
	function clearLocation($uid)
	{
		$query = "UPDATE locations SET updated=0 WHERE uid=".$uid.";";
		$FBML = "<fb:subtitle>My Location</fb:subtitle>";
		$FBML .= $GLOBALS['NOT_HERE_MESSAGE'];
		$this->facebook->api_client->profile_setFBML(null, $this->facebook->user, null, $FBML, $FBML, $FBML);
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
			
		$currentLoc = $this->getMyLocation();
		if(!isset($currentLoc['floor']))
			$currentLoc['floor'] = -1;
		
		$this->tpl->assign('myLoc', $currentLoc);
		$this->tpl->assign('floor', $floor);
		$this->tpl->assign('maps', $GLOBALS['Floor_Map']);
		$this->requireFacebook();
		$this->tpl->assign('friend', $this->getLocations($this->facebook->api_client->friends_getAppUsers(), $floor));
		$this->tpl->assign('resetTime', $GLOBALS['LOCATION_VALID_TIME']);
		$this->tpl->display('FriendLocator.tpl');
	}
	
	/**
	 * Display the hours 
	 *
	 */
	function displayHours($data = array()){
		$admin = $this->isAdmin();
		$this->tpl->assign('admin', $admin);
		$this->requireFacebook();
		$this->tpl->display('Hours.tpl');
	}
	
	/**
	* Determine if the user logged in is an Administrator or not.
	*/	
	function isAdmin(){
	$admin = 0;
		foreach($GLOBALS['ADMINS'] as $pid){
			if($pid == $this->facebook->user){
				$admin = 1;
			}
			if($admin == 1)
			return $admin;
		}
		return 0;
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
	
	function writeHours($data){
		if($this->isAdmin()){
			$replace = array("\r\n", "\n", "\r");
			$string = str_replace($replace, "<br />", $data);
			$file = $this->tpl->template_dir."/hourData.tpl";
			echo $string;
			if(is_writeable($file)){
				file_put_contents($file, $string);	
			}
		}
	}
}
?>

