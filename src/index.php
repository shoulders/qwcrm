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

// Load the config if it exists
if(is_file('configuration.php')) {
    
    // Load the config file
    require('configuration.php');
    
    // Create config object for global scope
    $QConfig = new QConfig;
    
}

// Load System Constants
require('includes/defines.php');

// Configure PHP error reporting
require(INCLUDES_DIR.'error.php');

// Load dependencies via composer
require(VENDOR_DIR.'autoload.php');

// Load Language
require(INCLUDES_DIR.'language.php');

// Load Libraries, Includes and QWFramework
require(INCLUDES_DIR.'include.php');

// Load database abstraction layer
require(INCLUDES_DIR.'adodb.php');

// Load QWcrm Security including mandatory security code
require(INCLUDES_DIR.'security.php');

// Load PDF creation library
//require(INCLUDES_DIR.'mpdf.php');

// Load email transport
require(INCLUDES_DIR.'email.php');

// Load template engine
require(INCLUDES_DIR.'smarty.php');

// Load the session and user framework
require(QFRAMEWORK_DIR.'qwframework.php');

// Route the page request
require(INCLUDES_DIR.'router.php');

// Build the page content payload
require(INCLUDES_DIR.'buildpage.php');

################################################
#         Test QWCRM Enviroment                #
################################################

// Verify QWcrm is installed correctly
verify_qwcrm_install_state($db);

################################################
#         Initialise QWCRM                     #
################################################

// Start the QFramework 
if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
    $app = new QFactory;
}

// Configure variables to be used by QWcrm
require(INCLUDES_DIR.'variables.php');

################################################
#         Build Page and Content               #
################################################

// Get the page controller - no user has been set to calculate what page to load
$page_controller = get_page_controller($db, $VAR, $QConfig, $user, $employee_id, $customer_id, $workorder_id, $invoice_id);

// Build the page
$BuildPage = get_page_content($db, $startTime, $page_controller, $VAR, $QConfig, $user);

################################################
#         Logging                              #
################################################

// Access Logging
if(!$skip_logging && (!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install')) {
    
    // This logs QWcrm page load details to the access log
    if($QConfig->qwcrm_access_log){
        write_record_to_access_log();
    }
    
}

################################################
#         Content Plugins                      #
################################################

// Plugins You can add plugins here that parse and change the page content

################################################
#         Headers                              #
################################################

// Send optional Headers if 'print' mode is not set
if(!isset($VAR['theme']) || $VAR['theme'] !== 'print') { 

    // Compress page payload and send compression headers
    if ($QConfig->gzip) {
        $BuildPage = compress_page_output($BuildPage);    
    }
        
}

################################################
#         Display the Built Page               #
################################################

echo $BuildPage;