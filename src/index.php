<?php
/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

#################################################
#   PHP Configuration                           #
#################################################

// Define the application's minimum supported PHP version as a constant so it can be referenced within the application.
define('QWCRM_MINIMUM_PHP', '5.6.21');

// Check the PHP version is high enough to run QWcrm (cant use _gettext() here)
if (version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '<')) {
    die('QWcrm requires PHP '.QWCRM_MINIMUM_PHP.' '.'or later to run.'.' Your current version is '.PHP_VERSION);
}

// Disable magic quotes
ini_set('magic_quotes_runtime', 0);

// Constant that is checked in included files to prevent direct access
define('_QWEXEC', 1);

// Get Root Folder and Physical path info (moved from index.php)    
define('QWCRM_PHYSICAL_PATH', __DIR__.DIRECTORY_SEPARATOR);                         // QWcrm Physical Path  - D:\websites\htdocs\develop\qwcrm\ || /home/myuser/public_html/develop/qwcrm/
define('QWCRM_PROTOCOL', 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://');   // QWcrm Protocol - http:// || https://    
define('QWCRM_DOMAIN', $_SERVER['HTTP_HOST']);                                      // QWcrm Domain - quantumwarp.com    
define('QWCRM_BASE_PATH', str_replace('index.php', '', $_SERVER['PHP_SELF']));      // QWcrm Base Path - /develop/qwcrm/    
define('QWCRM_PART_URL', QWCRM_PROTOCOL.QWCRM_DOMAIN.'/');                          // QWcrm Part URL  - http(s)://quantumwarp.com/
define('QWCRM_FULL_URL', QWCRM_PROTOCOL.QWCRM_DOMAIN.QWCRM_BASE_PATH);              // QWcrm Full URL  - http(s)://quantumwarp.com/develop/qwcrm/

###################################################
# Debugging Information Start Variable Acqusition #
###################################################

// Save the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

################################################
#         Load QWCRM                           #
################################################

// Intialise QWcrm Global variable $VAR
$VAR = array();

// Load the framework (session/user/database/template engine/system includes)
define('QFRAMEWORK_DIR', 'libraries/qframework/'); 
require(QFRAMEWORK_DIR.'qframework.php');
QFactory::loadQwcrm($VAR);

################################################
#         Initialise QWCRM                     #
################################################

if(!defined('QWCRM_SETUP')) {
    
    // Start the QFramework 
    $app = new QFactory;
    
}

// Set the Smarty User variables (This seems the best place for this function to run)
set_user_smarty_variables();

################################################
#         Build Page and Content               #
################################################

// Get the page controller
$page_controller = get_page_controller($VAR);

// Build the page
$BuildPage = get_page_content($page_controller, $startTime, $VAR);

################################################
#         Content Plugins                      #
################################################

// You can add plugins here that parse and change the page content

################################################
#         Logging                              #
################################################

// Update the Logged in User's Last Active Times
if(!defined('QWCRM_SETUP')) {    
    update_user_last_active(QFactory::getUser()->get('login_user_id'));    
}

// Access Logging
if(!defined('SKIP_LOGGING') && (!defined('QWCRM_SETUP'))) {
    
    // This logs QWcrm page load details to the access log
    if(QFactory::getConfig()->get('qwcrm_access_log')){
        write_record_to_access_log();
    }
    
}

################################################
#         Headers                              #
################################################

// Send optional Headers if 'print' mode is not set (print does: email, pdf and onscreen)
if(!isset($VAR['theme']) || $VAR['theme'] !== 'print') { 

    // Compress page payload and send compression headers
    if (QFactory::getConfig()->get('gzip')) {
        compress_page_output($BuildPage);    
    }
        
}

################################################
#         Display the Built Page               #
################################################

echo $BuildPage;