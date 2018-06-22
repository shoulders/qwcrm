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
define('QWCRM_MINIMUM_PHP', '5.5.0');

// Check the PHP version is high enough to run QWcrm
if (version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '<')){
    //die(_gettext("QWcrm requires PHP").' '.QWCRM_MINIMUM_PHP.' '.'or later to run.'.' '._gettext("Your current version is").' '.PHP_VERSION);
    die('QWcrm requires PHP '.QWCRM_MINIMUM_PHP.' '.'or later to run.'.' Your current version is '.PHP_VERSION);
}

// Disable magic quotes
ini_set('magic_quotes_runtime', 0);

// Constant that is checked in included files to prevent direct access
define('_QWEXEC', 1);

#################################################
# Debuging Information Start Varible Acqusition #
#################################################

// Save the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

################################################
#    Get Root Folder and Physical path info    #
################################################

// QWcrm Physical Path  - D:\websites\htdocs\develop\qwcrm\
define('QWCRM_PHYSICAL_PATH', __DIR__.DIRECTORY_SEPARATOR);

// QWcrm Protocol - http:// || https://
define('QWCRM_PROTOCOL', 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://');

// QWcrm Domain - quantumwarp.com
define('QWCRM_DOMAIN', $_SERVER['HTTP_HOST']);

// QWcrm Path - /develop/qwcrm/
define('QWCRM_BASE_PATH', str_replace('index.php', '', $_SERVER['PHP_SELF']));

################################################
#         Load QWCRM                           #
################################################

// Load the session and user framework
define('QFRAMEWORK_DIR', 'libraries/qframework/');
require(QFRAMEWORK_DIR.'qframework.php');

// Load System Constants
require('includes/system/defines.php');

// Configure PHP error reporting
require(INCLUDES_DIR.'system/error.php');

// Load dependencies via composer
require(VENDOR_DIR.'autoload.php');

// Whoops Error Handler - Here so it can load ASAP
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
//trigger_error("Number cannot be larger than 10"); // This can be used to simulate an error

// Load System Include
require(INCLUDES_DIR.'system/include.php');

// Load Language
require(INCLUDES_DIR.'system/language.php');

// Load Database Abstraction Layer  -  Not currently needed here because it is in the framework, but might be needed for install/migrate/upgrade
//require(INCLUDES_DIR.'system/adodb.php');

// Load QWcrm Security including mandatory security code
require(INCLUDES_DIR.'system/security.php');

// Load PDF creation library
//require(INCLUDES_DIR.'system/mpdf.php');

// Load email transport
require(INCLUDES_DIR.'system/email.php');

// Load Smarty Template Engine
//require(INCLUDES_DIR.'system/smarty.php');

// Configure variables to be used by QWcrm
require(INCLUDES_DIR.'system/variables.php');

// Route the page request
require(INCLUDES_DIR.'system/router.php');

// Build the page content payload
require(INCLUDES_DIR.'system/buildpage.php');

################################################
#         Test QWCRM Enviroment                #
################################################

// Verify QWcrm is installed correctly
//verify_qwcrm_install_state();

################################################
#         Initialise QWCRM                     #
################################################

// Load the system variables
load_system_variables($VAR);

// Start the QFramework 
if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
    $app = new QFactory;
}

// Set the User's smarty variables
set_user_smarty_variables();

################################################
#         Build Page and Content               #
################################################

// Get the page controller - no user has been set to calculate what page to load
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
update_user_last_active(QFactory::getUser()->get('login_user_id'));

// Access Logging
if(!defined('SKIP_LOGGING') && (!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install')) {
    
    // This logs QWcrm page load details to the access log
    if(QFactory::getConfig()->get('qwcrm_access_log')){
        write_record_to_access_log();
    }
    
}

################################################
#         Headers                              #
################################################

// Send optional Headers if 'print' mode is not set
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