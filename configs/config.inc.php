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
$dsn_config['host'] 	= 'MYSQL HOST';
$dsn_config['username'] = 'MYSQL USER';
$dsn_config['password'] = 'MYSQL PASSWORD';
$dsn_config['database'] = 'fbanthenaeum';

/**
 * Facebook Athenaeum customizations
 */
$APP_NAME = 'Facebook Athenaeum';
$FEED_URL = 'YOUR RSS NEWS FEED'; // RSS feed location
$APP_DIR  = 'WHERE ON YOUR SERVER FB-ANTHENAEUM LIVES'; //Use only fully qualified paths

/**
 * Define some search strings, the search term will be placed at the end of it.
 * TODO: Make buttons on homepage dissapear if left blank
 */

//The website search should return XML instead of a formatted page
$WEBSITE_SEARCH = "YOUR SEARCH HERE";

/**
 * These should just redirect to the site you want the user to see when the user searches
 * A catalog search should look something like this:
 * http://swem.wm.edu/process/search-processor.cfm?engine=catalog&q=
 * The search string will be appended to each query before redirecting the user to that page.
 */
$DATABASE_SEARCH = "YOUR DATABASE SEARCH HERE";
$CATALOG_SEARCH = "YOUR CATALOG SEARCH HERE";


/*
 * Set each floor's map location and what each floor is called.
 * An example from Swem is included below.
 */

$Floor_Map = array(
	array(
		'name' => 'Ground Floor',
		'map' => 'http://swem.wm.edu/images/floor-plans/0.gif'
	),
	array(
		'name' => 'First Floor',
		'map' => 'http://swem.wm.edu/images/floor-plans/1.gif'
	),
	array(
		'name' => 'Second Floor',
		'map' => 'http://swem.wm.edu/images/floor-plans/2.gif'	
	),
	array(
		'name' => 'Third Floor',
		'map' => 'http://swem.wm.edu/images/floor-plans/3.gif'	
	)
	);

/*
 * Set the floor that the Friend Locator defaults to when there is no floor specified by the user.
 * Be sure and use the floor number - the first in the array above is 0 and it goes up from there.
 * The user will never see this as a number.
 */
$DEFAULT_FLOOR = 1;

/*
 * Determine how long (in hours) a set location is good for.
 * TODO: Create an option so that a location is ignored after the library closes.
 */
$LOCATION_VALID_TIME = 24;

define('FBTOOLS_DIR', $APP_DIR);

/**
 * Get from http://www.google.com/analytics
 * defined on the line 
 *     var pageTracker = _gat._getTracker("UA-nnnnnnn-n");
 */
$GOOGLE_ANALYTICS_KEY = 'UA-nnnnnnn-n';

/*
 * In production set this to 0 rather than 1 to supress any possible PHP errors
 */
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

?>
