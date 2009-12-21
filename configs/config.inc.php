<?php
/**
 * Global configurations for Facebook Athenaeum
 * $Id$
 */

/**
 * Facebook Settings
 * 
 * The api_key and secret are given to you when you create your
 * application at http://developers.facebook.com/
 * 
 * You define the callback URL in your application setup.
 */
$facebook_config['debug'] 		= false;
$facebook_config['api_key'] 	= 'YOUR_API_KEY_HERE';
$facebook_config['secret'] 		= 'YOUR SECRET HERE';
$facebook_config['callback_url']= 'YOUR CALLBACK URL HERE';
$facebook_config['canvas_url_end'] = 'EVERYTHING AFTER http://apps.facebook.com HERE';

/**
 * Each element in the facebook_tabs array will be created as a tab.
 * The case statement in the index.php should match each tab name with 
 * all spaces removed.  It is however still case sensitive.
 * 
 * Each corresponding template should start with: 
 * {include file="header.tpl" title="????"}
 * 
 * Be sure and set the title to also match whatever is in the array below.
 */
$facebook_tabs = array(
	'Search Library', 
	'Friend Locator',  
//	'News',
	'Hours'
);

/**
 * DSN Settings
 */
$dsn_config['type'] 	= 'mysql';
$dsn_config['host'] 	= 'MYSQL_HOST';
$dsn_config['username'] = 'MYSQL_USER';
$dsn_config['password'] = 'MYSQL_PASSWORD';
$dsn_config['database'] = 'fbathenaeum';

/**
 * A table prefix can only be used if the tables are manually created.
 * A basic SQL script is located in libs/mysql.sql
 */
$dsn_config['table_prefix'] = ''; 

/**
 * Facebook Athenaeum customizations
 */
$APP_NAME = 'Facebook Athenaeum';
$FEED_URL = 'YOUR RSS NEWS FEED'; // RSS feed location
$APP_DIR  = 'WHERE ON YOUR SERVER FB-ANTHENAEUM LIVES'; //Use only fully qualified paths

/**
 * Define some search strings, the search term will be placed at the end of it.
 */

//The website search should return XML instead of a formatted page
$SEARCH['WEBSITE'] = "";  // Put the URL of your website search here.

/**
 * These should just redirect to the site you want the user to see when the user searches
 * A catalog search should look something like this:
 * http://swem.wm.edu/process/search-processor.cfm?engine=catalog&q=
 * The search string will be appended to each query before redirecting the user to that page.
 */
$SEARCH['DATABASE'] = "";  // Put the URL of your database search here. 
$SEARCH['CATALOG'] = "";   // Put the URL of your catalog search here.


/**
 * Set each floor's map location and what each floor is called.
 * The message element is what is published in a box on the user's homepage if they choose to add it
 * The feed element is what is appened to the the users name when publishing a story in their mini-feed
 * The tag <fb:pronoun /> inserts the word "himself" or "herself" based on the user's gender.
 */

$Floor_Map = array(
	array(
		'name' => 'Ground Floor',
		'map' => 'http://swem.wm.edu/images/floor-plans/0.gif',
		'message' => "I'm currently on the ground floor of Swem."
	),
	array(
		'name' => 'First Floor',
		'map' => 'http://swem.wm.edu/images/floor-plans/1.gif',
		'message' => "I'm currently on the first floor of Swem."
	),
	array(
		'name' => 'Second Floor',
		'map' => 'http://swem.wm.edu/images/floor-plans/2.gif',	
		'message' => "I'm currently on the second floor of Swem."
	),
	array(
		'name' => 'Third Floor',
		'map' => 'http://swem.wm.edu/images/floor-plans/3.gif',
		'message' => "I'm currently on the third floor of Swem."
	)
	);
	
/**
 * The short name of the library
 */
$SHORT_NAME="Swem";
	
/**
 * The URL to a smaller logo for use in the news feed
 */
$Small_Logo = "http://swem.wm.edu/images/windowFB.png";
	
/**
 * Set as a 1 to enable publishing stories on a the users mini-feed, otherwise this should be set to 0
 * Before this can work, someone that is listed as an admin below must first set their location.
 */
$PUBLISH_FEED = 1;

/*
 * Set the floor that the Friend Locator defaults to when there is no floor specified by the user.
 * Be sure and use the floor number - the first in the array above is 0 and it goes up from there.
 * The user will never see this as a number.
 */
$DEFAULT_FLOOR = 1;

/*
 * Determine how long (in hours) a set location is good for.
 */
$LOCATION_VALID_TIME = 24;

define('FBTOOLS_DIR', $APP_DIR);

/**
 * Get from http://www.google.com/analytics
 * defined on the line 
 *     var pageTracker = _gat._getTracker("UA-nnnnnnn-n");
 */
$GOOGLE_ANALYTICS_KEY = 'UA-nnnnnnn-n';


/**
 * An array with the Facebook IDs of all of the admin users of the application.
 * The two listed below are non-working IDs, and are here for example only.
 * Be sure and delete them and put your own Facebook ID here.  It can be found in the URL of
 * your profile page.    http://www.facebook.com/profile.php?id=#######
 */
$ADMINS = array(
	0000001, 
	0000002
);

/**
 * In production set this to "0" to supress any PHP errors.
 */
$REPORT_ERRORS = 1;

/**
 * DO NOT EDIT BELOW THIS LINE
 */

ini_set('display_errors', $report_errors);
error_reporting(E_ALL);

?>
