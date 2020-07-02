<?php
/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

#################################################
#   PHP Enviroment Configuration                #
#################################################

// Save the start time and memory usage.
$startTime = microtime(1);
$startMem = memory_get_usage();

// Define and check QWcrm Minimum PHP version
define('QWCRM_MINIMUM_PHP', '7.2.0');
if (version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '<')) {
    die('QWcrm requires PHP '.QWCRM_MINIMUM_PHP.' '.'or later to run. Your current version is '.PHP_VERSION);
}

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

################################################
#         Load QWCRM                           #
################################################

// Load the framework (session/user/database/template engine/system includes)
define('QFRAMEWORK_DIR', 'libraries/qframework/'); 
require(QFRAMEWORK_DIR.'includes/loader.php');
//\CMSApplication::loadQwcrm();

###################################################
# Debugging Information Start Variable Acqusition #
###################################################

// Save the start time and memory usage. (to system)
\CMSApplication::$VAR['system']['startTime'] = $startTime;
\CMSApplication::$VAR['system']['startMem'] = $startMem;

################################################
#         Initialise QWCRM                     #
################################################

/*if(!defined('QWCRM_SETUP')) {
    
    // Start the QFramework
    $app = new \Factory;
       
}*/

// Start the QFramework 
//$app = new \CMSApplication();

// Instantiate the application.
$app = \Factory::getApplication('site');

// Execute the application.
$app->execute();

############################################################
#  Finish Building the Environment and Load Page           #
############################################################

// Build and set the System Messages Store (only run once per session)
$app->system->variables->systemMessagesBuildStore(true);
    
// Set the Smarty User Variables (only run once per session)
if(!defined('QWCRM_SETUP')) {
    $app->system->variables->smarty_set_user_variables();
}

// Build and Load the page into memmory
$app->system->page->load_page('set_controller');

################################################
#         Logging                              #
################################################

if(!defined('QWCRM_SETUP')) { 
    
    // Update the Logged in User's Last Active Times
    $app->components->user->update_user_last_active($app->user->login_user_id);
    
    // Access Logging - This logs QWcrm page load details to the access log
    if(!defined('SKIP_LOGGING') && $app->config->get('qwcrm_access_log')) {    
        $app->system->general->write_record_to_access_log();    
    }

}

################################################
#         Headers                              #
################################################

// Send optional Headers if 'print' mode is not set (print does: email, pdf and onscreen)
if(!isset($VAR['themeVar']) || $VAR['themeVar'] !== 'print') { 

    // Compress page payload and send compression headers
    if (\Factory::getConfig()->get('gzip')) {
        \CMSApplication::$BuildPage = $app->system->page->compress_page_output(\CMSApplication::$BuildPage);
    }
        
}

################################################
#         Display the Built Page               #
################################################

echo \CMSApplication::$BuildPage;