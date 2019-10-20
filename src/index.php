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

// Define and check QWcrm Minimum PHP version
define('QWCRM_MINIMUM_PHP', '5.6.21');
if (version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '<')) {
    die('QWcrm requires PHP '.QWCRM_MINIMUM_PHP.' '.'or later to run. Your current version is '.PHP_VERSION);
}

/* Define and check QWcrm Maximum PHP version
define('QWCRM_MAXIMUM_PHP', 'x.x.x');
if (version_compare(PHP_VERSION, QWCRM_MAXIMUM_PHP, '>=')) {
    die('QWcrm requires a PHP version lower than '.QWCRM_MAXIMUM_PHP.' to run. Your current version is '.PHP_VERSION);
}*/

// Disable magic quotes
ini_set('magic_quotes_runtime', 0);

// Constant that is checked in included files to prevent direct access
define('_QWEXEC', 1);
define('_JEXEC', 1);
define('JPATH_PLATFORM', 1);

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
$startMem = memory_get_usage();

################################################
#         Load QWCRM                           #
################################################

// Load the framework (session/user/database/template engine/system includes)
define('QFRAMEWORK_DIR', 'libraries/qframework/'); 
require(QFRAMEWORK_DIR.'qwcrm/loader.php');
\QFactory::loadQwcrm();

###################################################
# Debugging Information Start Variable Acqusition #
###################################################

// Save the start time and memory usage. (to system)
\QFactory::$VAR['system']['startTime'] = $startTime;
\QFactory::$VAR['system']['startMem'] = $startMem;

################################################
#         Initialise QWCRM                     #
################################################

if(!defined('QWCRM_SETUP')) {
    
    // Start the QFramework 
    $app = new \QFactory;
       
}

################################################
#         Build Page and Content               #
################################################

// Build and set the system Messages
smarty_set_system_messages(\QFactory::$VAR);

// Set the Smarty User variables (This seems the best place for this function to run)
smarty_set_user_variables();

// Build and Load the page into memmory
load_page();

################################################
#         Content Plugins                      #
################################################

// You can add plugins here that parse and change the page content

################################################
#         Logging                              #
################################################

// Update the Logged in User's Last Active Times
if(!defined('QWCRM_SETUP')) {    
    update_user_last_active(\QFactory::getUser()->login_user_id);    
}

// Access Logging
if(!defined('SKIP_LOGGING') && (!defined('QWCRM_SETUP'))) {
    
    // This logs QWcrm page load details to the access log
    if(\QFactory::getConfig()->get('qwcrm_access_log')){
        write_record_to_access_log();
    }
    
}

################################################
#         Headers                              #
################################################

// Send optional Headers if 'print' mode is not set (print does: email, pdf and onscreen)
if(!isset($VAR['theme']) || $VAR['theme'] !== 'print') { 

    // Compress page payload and send compression headers
    if (\QFactory::getConfig()->get('gzip')) {
        \QFactory::$BuildPage = compress_page_output(\QFactory::$BuildPage);
    }
        
}

################################################
#         Display the Built Page               #
################################################

echo \QFactory::$BuildPage;