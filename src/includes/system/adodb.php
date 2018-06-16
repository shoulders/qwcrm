<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/* ADODB Options */

// ADODB_ASSOC_CASE - You can control the associative fetch case for certain drivers which behave differently. - native is default since v2.90
//define('ADODB_ASSOC_CASE', 1);  // set what case to use for recordsets where the field name (not table names): 0 = lowercase, 1 = uppercase, 2 = native case

// $ADODB_FETCH_MODE - This is a global variable that determines how arrays are retrieved by recordsets. 
//$ADODB_FETCH_MODE = ADODB_FETCH_NUM; 

/* -- */

/*
// Set Path for ADODB in the php include path (Not needed because it is loaded by Composer)
set_include_path(get_include_path() . PATH_SEPARATOR . LIBRARIES_DIR.'adodb/');

// Load Dependency Manually (Not needed because it is loaded by composer)
require('adodb.inc.php');

// Enable error trapping - this extends the system class Exception - http://adodb.org/dokuwiki/doku.php?id=v5:userguide:error_handling
// I think this tries to convert standard PHP errors to Exceptions - needed for get_qwcrm_database_version_number()
require('adodb-exceptions.inc.php');
require(VENDOR_DIR.'adodb/adodb-php/adodb-exceptions.inc.php');
*/

// create adodb database connection
$db = ADONewConnection('mysqli');

// This is needed to allow install/migration/upgrade
if($QConfig->db_host != '' && $QConfig->db_user != '' || $QConfig->db_name != '') {
    
    // Get current PHP error reporting level
    $reporting_level = error_reporting();
    
    // Disable PHP error reporting (works globally)
    error_reporting(0);
    
    // Create ADOdb database connection - and collection exceptions
    try
    {        
        $db->Connect($QConfig->db_host, $QConfig->db_user, $QConfig->db_pass, $QConfig->db_name);
    }
    
    // Error handling is tested/displayed/handled in verify_qwcrm_is_installed_correctly() this allows a graceful fail
    
    catch (Exception $e)
    {
        
        // Re-Enable PHP error reporting
        error_reporting($reporting_level);
        
        //echo $e->msg;
        //var_dump($e);
        //adodb_backtrace($e->gettrace());        
        //$smarty->assign('warning_msg', $e->msg);
              
    }
    
    // Re-Enable PHP error reporting
    error_reporting($reporting_level);
    
    /* Return the connection status
    if(!$db->isConnected()) {        
        die('<div style="color: red;">'._gettext("There is a database connection issue. Check your settings in the config file.").'<br><br>'.$db->ErrorMsg().'</div>');      
    }*/
   
}