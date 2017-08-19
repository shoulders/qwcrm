<?php
/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

################################################
#   Minimum PHP Version                        #
################################################

// Define the application's minimum supported PHP version as a constant so it can be referenced within the application.
define('QWCRM_MINIMUM_PHP', '5.4.0');

// Check the PHP version is high enough to run QWcrm
if (version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '<')){
    die(gettext("QWcrm requires PHP").' '.QWCRM_MINIMUM_PHP.' '.'or later to run.'.' '.gettext("Your current version is").' '.PHP_VERSION);
}

#################################################
#   PHP enviromental settings                   #
#################################################

// disable magic quotes
ini_set('magic_quotes_runtime', 0);

#################################################
# Debuging Information Start Varible Acqusition #
#################################################

// Saves the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

################################################
#    Get Root Folder and Physical path info    #
################################################

// could i use realpath() here in anyway?

// QWcrm Physical Path  - D:\websites\htdocs\develop\qwcrm\
define('QWCRM_PHYSICAL_PATH', __DIR__.DIRECTORY_SEPARATOR);

// QWcrm Protocol - http:// || https://
define('QWCRM_PROTOCOL', 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://');

// QWcrm Domain - quantumwarp.com
define('QWCRM_DOMAIN', $_SERVER['HTTP_HOST']);

// QWcrm Path - /develop/qwcrm/
define('QWCRM_PATH', str_replace('index.php', '', $_SERVER['PHP_SELF']));

################################################
#         Initialise QWCRM                     #
################################################

// Constant that is checked in included files to prevent direct access
define('_QWEXEC', 1);

// load the config file if it exists
if(is_file('configuration.php')) {
    require('configuration.php');    
}

// Create config object for global scope / settings
$QConfig = new QConfig;

// need to add error control here ie skip straight to
require('includes/defines.php');
require(INCLUDES_DIR.'security.php');
require(INCLUDES_DIR.'include.php');
//require(INCLUDES_DIR.'mpdf.php');
require(INCLUDES_DIR.'email.php');
require(INCLUDES_DIR.'adodb.php');
require(INCLUDES_DIR.'smarty.php');
require(FRAMEWORK_DIR.'qwframework.php');

################################################
#         Error Reporting                      #
################################################

// Set the error_reporting
switch ($QConfig->error_reporting)
{
    case 'default':
    case '-1':
        break;

    case 'none':
    case '0':
        error_reporting(0);
        break;
            
    case 'verysimple':
        error_reporting(E_ERROR | E_WARNING | E_PARSE | ~E_NOTICE);
        ini_set('display_errors', 1);
        break;            

    case 'simple':
        error_reporting(E_ERROR | E_WARNING | E_PARSE);
        ini_set('display_errors', 1);
        break;

    case 'maximum':
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        break;

    case 'development':
        error_reporting(-1);
        ini_set('display_errors', 1);
        break;

    default:
        error_reporting($QConfig->error_reporting);
        ini_set('display_errors', 1);
        break;
}

#################################################
#          Security                             #
#################################################

// it is called by including the file - security php will have some auto run code aswell as functions - this section might not be needed

// should this be run before smarty?

// force ssl - this needs to load the config
// add security routines here

// url checking, dont forget htaccess single point, post get varible sanitisation

################################################
#         Load Language                        #
################################################

/* new system */

// Autodetect Language - I18N support information here
if($QConfig->autodetect_language) {
    if(!$language = locale_accept_from_http($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $language = $QConfig->default_language; 
    }
} else {
    $language = $QConfig->default_language; 
}

// here we define the global system locale given the found language
putenv("LANG=$language");

// this might be useful for date functions (LC_TIME) or money formatting (LC_MONETARY), for instance
setlocale(LC_ALL, $language);

// Set the text domain
$textdomain = 'site';

// this will make Gettext look for ../language/<lang>/LC_MESSAGES/site.mo
bindtextdomain($textdomain, 'language');

// indicates in what encoding the file should be read
bind_textdomain_codeset($textdomain, 'UTF-8');

// here we indicate the default domain the gettext() calls will respond to
textdomain($textdomain);

################################################
#    Verify QWcrm is installed correctly       #
################################################

verify_qwcrm_is_installed_correctly($db);

################################################
#         Framework                            #
################################################

// Initiate QFramework
$app = new QFactory;

##########################################################
#   Assign the User's Variables to PHP and Smarty        #
##########################################################

// Load current user object (empty if not logged in)
$user = QFactory::getUser();

// Set User PHP variables
$login_user_id          = $user->login_user_id;         // QFactory::getUser()->login_user_id; - this also works exactly the same
$login_username         = $user->login_username;
$login_display_name     = $user->login_display_name;
$login_token            = $user->login_token;           // could this be replaced
$login_is_employee      = $user->login_is_employee;
$login_customer_id      = $user->login_customer_id;     // is only set when there is a customer_id in the user account

// If there is no logged in user, set usergroup to Public (This can cause looping if not present)
if(!isset($login_token )){
    $login_usergroup_id = 9;
} else {
    $login_usergroup_id = $user->login_usergroup_id;   
}

// Remove User object as no longer needed (for security)
unset($user);

// Assign User varibles to smarty
$smarty->assign('login_user_id',            $login_user_id          );
$smarty->assign('login_username',           $login_username         );
$smarty->assign('login_usergroup_id',       $login_usergroup_id     );
$smarty->assign('login_display_name',       $login_display_name     );
$smarty->assign('login_token',              $login_token            );
$smarty->assign('login_is_employee',        $login_is_employee      );
$smarty->assign('login_customer_id',        $login_customer_id      );

################################################
#   Update Last Active Times                   #
################################################

// Logged in Users
if($login_user_id) {update_user_last_active($db, $login_user_id);}

################################
#   Set Global PHP Values      #
################################ 

// Merge the $_GET, $_POST and emulated $_POST
$VAR = array_merge($_GET, $_POST, postEmulationReturnStore());

// These are used globally
$workorder_id       = $VAR['workorder_id'];
$customer_id        = $VAR['customer_id'];
$expense_id         = $VAR['expense_id'];
$refund_id          = $VAR['refund_id'];
$supplier_id        = $VAR['supplier_id'];
$invoice_id         = $VAR['invoice_id'];
$schedule_id        = $VAR['schedule_id'];
$giftcert_id        = $VAR['giftcert_id'];
$user_id            = $VAR['user_id'];

// Make sure an employee_id is always set - if no user is set use the logged in user
//if(isset($VAR['employee_id'])) {$employee_id = $VAR['employee_id'];} else {$employee_id = QFactory::getUser()->login_user_id;}  // this might not be required
$employee_id        = $VAR['employee_id'];

// Get the page number if it exists or set to page number to 1 if not
if(isset($VAR['page_no'])) {$page_no = $VAR['page_no'];} else {$page_no = 1;}

##########################################
#   Set Global PHP Values from QWcrm     #
##########################################

// Set Date Format
define('DATE_FORMAT', get_company_details($db, 'date_format'));                 // If there are DATABASE ERRORS, they will present here (white screen) when verify QWcrm function is not on 

##########################################################################
#   Assign variables into smarty for use by all native module templates  #
##########################################################################

// QWcrm System Folders
$smarty->assign('includes_dir',             INCLUDES_DIR                );      // set includes directory  //do i need this one
$smarty->assign('media_dir',                MEDIA_DIR                   );      // set media directory

// QWcrm Theme Directory Template Variables
$smarty->assign('theme_dir',                THEME_DIR                   );      // set theme directory
$smarty->assign('theme_images_dir',         THEME_IMAGES_DIR            );      // set theme images directory
$smarty->assign('theme_css_dir',            THEME_CSS_DIR               );      // set theme CSS directory
$smarty->assign('theme_js_dir',             THEME_JS_DIR                );      // set theme JS directory

// QWcrm Theme Directory Template Smarty File Include Path Variables
$smarty->assign('theme_js_dir_finc',        THEME_JS_DIR_FINC           );

// These are used globally but mainly for the menu !!
$smarty->assign('workorder_id',             $workorder_id               );
$smarty->assign('customer_id',              $customer_id                );
$smarty->assign('employee_id',              $employee_id                );
$smarty->assign('expense_id',               $expense_id                 );
$smarty->assign('giftcert_id',              $giftcert_id                );
$smarty->assign('invoice_id',               $invoice_id                 );
$smarty->assign('refund_id',                $refund_id                  );
$smarty->assign('supplier_id',              $supplier_id                );
$smarty->assign('schedule_id',              $schedule_id                );
$smarty->assign('start_year',               $start_year                 );
$smarty->assign('start_month',              $start_month                );
$smarty->assign('start_day',                $start_day                  );
$smarty->assign('user_id',                  $user_id                    );

// Used throughout the site
$smarty->assign('currency_sym', get_company_details($db,    'currency_symbol')  );
$smarty->assign('company_logo', get_company_details($db,    'logo')             );
$smarty->assign('date_format',  DATE_FORMAT                                     );

#############################
#        Messages           #
#############################

// Information Message (Green)
if(isset($VAR['information_msg'])){
    $smarty->assign('information_msg', $VAR['information_msg']);
}

// Warning Message (Red)
if(isset($VAR['warning_msg'])){
    $smarty->assign('warning_msg', $VAR['warning_msg']);
}

############################################
#  Page Preperation Logic                  #
#  Extract Page Parameters and Validate    #
#  the page exists ready for building      #
############################################   

if($QConfig->maintenance == true){
    
    // Set to the maintenance page    
    $page_display_controller = 'modules/core/maintenance.php'; 
    $module     = 'core';
    $page_tpl   = 'maintenance';
    $VAR['theme'] = 'off';   
    
    // If user logged in, then log user off (Hard logout, no logging)
    if(isset($login_token)) {    
        QFactory::getAuth()->logout(); 
    }    

// If there is a page set, verify it 
} elseif(isset($VAR['page']) && $VAR['page'] != '') { 

    // Explode the URL so we can get the module and page_tpl
    list($module, $page_tpl)    = explode(':', $VAR['page']);
    $page_display_controller    = 'modules/'.$module.'/'.$page_tpl.'.php';

    // Check to see if the page exists and set it, otherwise send them to the 404 page
    if (file_exists($page_display_controller)){
        $page_display_controller = 'modules/'.$module.'/'.$page_tpl.'.php';            
    } else {
        
        // set to the 404 error page 
        $page_display_controller = 'modules/core/404.php'; 
        $module     = 'core';
        $page_tpl   = '404';
        
        // Send 404 header
        $VAR['theme'] = 'off';
        header('HTTP/1.1 404 Not Found');
        
    }        

// If no page specified load a default landing page   
} else {        

    if(isset($login_token)){
        // If logged in
        $page_display_controller    = 'modules/core/dashboard.php';
        $module                     = 'core';
        $page_tpl                   = 'dashboard';       
    } else {
        // If NOT logged in
        $page_display_controller    = 'modules/core/home.php';
        $module                     = 'core';
        $page_tpl                   = 'home';            
    }

}

###############################################
#    Build and Display the page (as required) #
#    if the user has the correct permissions  #
###############################################

// This varible holds the page as it is built
$BuildPage = '';

/* Check the requested page with 'logged in' user against the ACL for authorisation - if allowed, display */
if(check_acl($db, $login_usergroup_id, $module, $page_tpl)){
    
    // If theme is set to Print mode then fetch the Page Content - Print system will output with its own format without need for headers and footers here
    if ($VAR['theme'] === 'print'){        
        require($page_display_controller);
        goto page_build_end;
    }

    // Set Page Header and Meta Data
    set_page_header_and_meta_data($module, $page_tpl, $VAR['page_title']);    

    // Fetch Header Block
    if($VAR['theme'] != 'off'){        
        require('modules/core/blocks/theme_header_block.php');
    } else {
        //echo '<!DOCTYPE html><head></head><body>';
        require('modules/core/blocks/theme_header_theme_off_block.php');
    }

    // Fetch Header Legacy Template Code and Menu Block - Customers, Guests and Public users will not see the menu
    if($VAR['theme'] != 'off' && isset($login_token) && $login_usergroup_id != 7 && $login_usergroup_id != 8 && $login_usergroup_id != 9){       
        $BuildPage .= $smarty->fetch('core/blocks/theme_header_legacy_supplement_block.tpl');
        require('modules/core/blocks/theme_menu_block.php');        
    }    

    // Fetch the Page Content
    require($page_display_controller);    

    // Fetch Footer Legacy Template code Block (closes content table)
    if($VAR['theme'] != 'off' && isset($login_token) && $login_usergroup_id != 7 && $login_usergroup_id != 8 && $login_usergroup_id != 9){
        $BuildPage .= $smarty->fetch('core/blocks/theme_footer_legacy_supplement_block.tpl');             
    }

    // Fetch the Footer Block
    if($VAR['theme'] != 'off'){        
        require('modules/core/blocks/theme_footer_block.php');        
    }    

    // Fetch the Debug Block
    if($QConfig->qwcrm_debug == true){
        require('modules/core/blocks/theme_debug_block.php');        
        $BuildPage .= "\r\n</body>\r\n</html>";
    } else {
        $BuildPage .= "\r\n</body>\r\n</html>";
    }
    
    page_build_end:
    
}

################################################
#        Access Logging                        #
################################################

if(!$skip_logging) {
    
    // This logs access details to the access log
    if($QConfig->qwcrm_access_log == true){
        write_record_to_access_log($login_username);
    }
    
}
################################################
#         Content Plugins                      #
################################################

// You can add plugins here that change the page content
// $BuildPage

################################################
#         Headers                              #
################################################

// Send Headers if 'print' mode is not set
if ($VAR['theme'] !== 'print') {        
    
}

################################################
#         Page Compression                     #
################################################

// Compress page and send correct compression headers
if ($QConfig->gzip == true && $VAR['theme'] !== 'print') {

    $BuildPage = compress_page_output($BuildPage);
    
}
    
################################################
#    Display the Built Page                    #
################################################

echo $BuildPage;