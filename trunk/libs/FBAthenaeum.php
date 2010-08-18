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
	
	
	function __construct(){
		$this->sql =& new FBTools_SQL;
		$this->tpl =& new FBTools_Smarty;
		$this->facebook =& new FBTools_FB();
		$this->tblPrefix = $GLOBALS['dsn_config']['table_prefix'];
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
	

	/**
	 * If a user is not already in the database, then create an entry so that everything else always works right
	 */
	function installIfNeeeded()
	{
		$this->requireFacebook();
		$this->sql->query(
			"SELECT uid FROM ".$this->tblPrefix."locations WHERE uid=".$this->facebook->user.";",
			SQL_INIT,
			SQL_ASSOC
		);
		
		if(empty($this->sql->record))
		{
			$this->sql->query(
			"INSERT INTO ".$this->tblPrefix."locations SET uid=".$this->facebook->user.";"
			);
		}
	}
	

	/**
	 * Display the search library page.  The page usually doubles as the homepage
	 *
	 */
	function displaySearchLibrary(){
		$this->requireFacebook();
		$this->tpl->assign('search', $GLOBALS['SEARCH']);
		$this->tpl->assign('RSSFeed', $GLOBALS['FEED_URL']);
		$this->tpl->display('SearchLibrary.tpl');
	}
	

	/**
	 * Find the location of the currently logged in user.
	 * 
	 * @return An array containing the current floor and x-y coordinates of the currently logged in user.
	 */
	function getMyLocation()
	{
		$query = "SELECT floor,x,y FROM ".$this->tblPrefix."locations 
					WHERE uid=".$this->facebook->user." AND 
					updated >= (DATE_ADD(NOW(), INTERVAL -".$GLOBALS['LOCATION_VALID_TIME']." HOUR));";
		$this->sql->query($query, SQL_INIT, SQL_ASSOC);
		
		return $this->sql->record;
	}


	/**
	 * Return the locations for friends
	 *
	 * @param array $friends An array listing all of a users friends that have the application installed
	 * @param integer $floor What floor you are checking for users on.
	 * @return An array of location records for the given floor.  A location record contains the users' UID, and x-y coordinates
	 */
	function getLocations($friends = array(), $floor){
		$query_string = "SELECT uid, x, y FROM ".$this->tblPrefix."locations WHERE floor=".$floor." AND (1=0";
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
	 * Update a location in the database
	 *
	 * @param array $formvars The form variables that are passed from the AJAX on the map page.
	 */
	function updateLocation($formvars){
		$_query = sprintf(
			"UPDATE ".$this->tblPrefix."locations SET
			x= %d, y=%d, floor=%d, updated=NOW()
			WHERE uid=%d;", 
			(int)$formvars['x'], 
			(int)$formvars['y'], 
			(int)$formvars['floor'],
			$this->facebook->user
		);
		
		//if($formvars['oldfloor'] != $formvars['floor'])
		
		$oldLoc = $this->getMyLocation();
		
		if(!isset($oldLoc['floor']) || (isset($oldLoc['floor']) && $oldLoc['floor'] != $formvars['floor']))
		{
			$action = array('message' => $GLOBALS['Floor_Map'][$formvars['floor']]['feed'], 'floor_id'=>$formvars['floor']);
		//	$this->facebook->api_client->feed_publishUserAction($TEMPLATE_ID, json_encode($action));	
		}
		
		return $this->sql->query($_query);
	}
	
	
	/**
	 * Clears a location by setting the date stamp to the unix epoch.
	 * 
	 * @param The UID of the user who's location is to be cleared.
	 */
	function clearLocation($uid)
	{
		$query = "UPDATE ".$this->tblPrefix."locations SET updated=0 WHERE uid=".$uid.";";
		return $this->sql->query($query) or die(mysql_error() );
	}
	

	/**
	 * Display the friend locator.
	 * 
	 * @param The floor that is to be displayed
	 */
	function displayFriendLocator($floor = null)
	{
		$currentLoc = $this->getMyLocation();
		if(!isset($currentLoc['floor']))
			$currentLoc['floor'] = -1;
			
		if(!isset($floor['f']) && $currentLoc['floor'] == -1)
			$floor = $GLOBALS['DEFAULT_FLOOR'];
		else if(!isset($floor['f']))
			$floor = $currentLoc['floor'];
		else
			$floor = $floor['f'];
			
		$this->tpl->assign('shortName', $GLOBALS['SHORT_NAME']);
		$this->tpl->assign('imageURL', $GLOBALS['Small_Logo']);
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
	function displayHours(){
		$admin = $this->isAdmin();
		$this->tpl->assign('admin', $admin);
		$this->requireFacebook();
		$this->tpl->display('Hours.tpl');
	}
	

	/**
	 * Determine if a given user is an Administrator or not
	 *
	 * @return 1 if the user is an Administrator, 0 otherwise
	 */
	function isAdmin(){
	$admin = 0;
		foreach($GLOBALS['ADMINS'] as $pid){
			if($pid == $this->facebook->user){
				return 1;
			}
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
	
	
	/**
	 * Write the hours template to a file.  If the file exists before this function
	 * is run then the PHP/Webserver user must have read/write access
	 * 
	 * @param string $data What is going to be written to the file.  New lines will be converted to HTML line breaks
	 */
	function writeHours($data){
		if($this->isAdmin()){
			$string = nl2br($data);
			$file = $this->tpl->template_dir."/hourData.tpl";
			if(is_writable($file)){
				file_put_contents($file, $string);	
			}
		}
	}
}
?>

