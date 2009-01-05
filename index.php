<?php

/**
 * Project: Facebook Athenaeum
 * Date: $Date$
 * File: index.php
 * Version: $Id$
 * 
 */
include('configs/config.inc.php');
include($APP_DIR . 'libs/facebook_setup.php');

// create facebook tools 
$fbtools =& new FBAthenaeum;

$_action = isset($_REQUEST['action']) ? $_REQUEST['action'] : 'SearchLibrary';

switch($_action){
	case 'searchResults':
		$fbtools->searchWebsite($_POST);
		break; 
	case 'FriendLocator':
		$fbtools->installIfNeeeded();
		$fbtools->displayFriendLocator($_GET);
		break;
	case 'setLocation':
		$fbtools->updateLocation($_POST);
		break;
	case 'clearLocation':
		$fbtools->clearLocation($_POST['fb_sig_user']);
		break;
	case 'Hours':
		$fbtools->displayHours();
		break;
	case 'News':
		$fbtools->displayNews();
		break;
	case 'writeHours':
		$fbtools->writeHours($_REQUEST['hours']);
		break;
	case 'SearchLibrary':
	default:
		$fbtools->displaySearchLibrary();
		break;
}


?>

