<?php
/**
 * 
 * Project: 
 * Date: $Date$
 * File: facebook_setup.php
 * Version: $Id$
 * 
 */

require_once(FBTOOLS_DIR . 'configs/config.inc.php');
require_once(FBTOOLS_DIR . 'libs/sql.lib.php');
require_once(FBTOOLS_DIR . 'libs/FBAthenaeum.php');
require_once(FBTOOLS_DIR . 'libs/facebook_api/facebook.php');
require_once('Smarty/libs/Smarty.class.php');
require_once('DB.php');

/**
 * Class to set up the Facebook API 
 * 
 */
 class FBTools_FB extends Facebook {
	function FBTools_FB(){
		$this->__construct($GLOBALS['facebook_config']['api_key'], $GLOBALS['facebook_config']['secret']);
	}
} 

/**
 * Class to set up the SQL object for Smarty
 *
 */
class FBTools_SQL extends SQL {
	function FBTools_SQL(){
		
		$dsn = array(
			'phptype'  => $GLOBALS['dsn_config']['type'],
			'hostspec' => $GLOBALS['dsn_config']['host'],
			'database' => $GLOBALS['dsn_config']['database'],
			'username' => $GLOBALS['dsn_config']['username'],
			'password' => $GLOBALS['dsn_config']['password']
		);
		
		$this->connect($dsn) || die ('could not connect to database');
	}
}

/**
 * Class to set up Smarty
 *
 */
class FBTools_Smarty extends Smarty {
	var $_PHPDebugHandler = null;
	
	function FBTools_Smarty(){
		$this->template_dir = FBTOOLS_DIR . 'templates';
		$this->compile_dir 	= FBTOOLS_DIR . 'compile';
		$this->cache_dir 	= FBTOOLS_DIR . 'cache';
		$this->config_dir 	= FBTOOLS_DIR . 'config';
		$this->plugin_dir 	= FBTOOLS_DIR . 'plugins';
		
		$this->caching 		= 0;
		$this->debugging	= 0;
	}
}
?>
