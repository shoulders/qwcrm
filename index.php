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

if (version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '<')){
    die('Your host needs to use PHP ' . QWCRM_MINIMUM_PHP . ' or higher to run this version of QWCRM!');
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

if(is_file('configuration.php')) {
    require('configuration.php');    
} else {
    //verify_qwcrm_is_installed_correctly($db); // I do not need this twice ?
}

// Create config object for global scope (temp + for error reporting)
$GConfig = new GConfig;

// need to add error control here ie skipt strait to
require('includes/defines.php');
require(INCLUDES_DIR.'security.php');
require(INCLUDES_DIR.'include.php');
require(INCLUDES_DIR.'adodb.php');
require(INCLUDES_DIR.'smarty.php');
require(FRAMEWORK_DIR.'qframework.php');

################################################
#         Error Reporting                      #
################################################

// Set the error_reporting
switch ('verysimple')
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
        error_reporting($config->error_reporting);
        ini_set('display_errors', 1);
        break;
}

// remove this variable
//unset($config);

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

// Load Language Specifc Settings from language file
//if(!xml2php('settings')){$smarty->assign('error_msg', 'Error in system language file');}

// Load Language Translations
if(!load_language()) {$smarty->assign('error_msg', 'Error in system language file');}

################################################
#    Verify QWcrm is installed correctly       #
################################################

//verify_qwcrm_is_installed_correctly($db);

################################################
#         Framework                            #
################################################

// Initiate QFramework
$app = new QFactory();

################################################
#           Authentication                     #
################################################

// $user->login_token cannot be used for login validation routines below

// sort where I load this library from (Do i want to load it on every page load? maybe build into my new framework)
require(LIBRARIES_DIR.'recaptchalib.php');

// Get variables in correct format for login()
if(isset($_POST['login_usr'])) { $credentials['username'] = $_POST['login_usr']; }
if(isset($_POST['login_pwd'])) { $credentials['password'] = $_POST['login_pwd']; }
if(isset($_POST['remember'])) { $options['remember'] = $_POST['remember']; }

// If captcha is enabled
if($GConfig->captcha && !isset($user->login_token) && $_POST['action'] === 'login') {
    
    $reCaptcha = new ReCaptcha($recaptcha_secret_key);
    
    // Get response from Google and if successfull authenticate
    $response = $reCaptcha->verifyResponse($_SERVER['REMOTE_ADDR'], $_POST['g-recaptcha-response']);    
    if ($response != null && $response->success) {
        $app->login();
    } else {        
        force_page('core', 'login', 'warning_msg=Google ReCaptcha Verification Failed');
        exit;
    }  

// Normal login with no captcha
} elseif (!isset($user->login_token) && $_POST['action'] === 'login') {    
    $app->login($credentials, $options);
}

// remove credentials
unset($credentials);



/* Assign Logged in User's Variables to PHP and Smarty */

$user = QFactory::getUser();
//print_r(QFactory::$user->login_display_name);
//print_r($user->login_display_name);
// unset the user object as no longer needed?
//unset($user);

//$login_id   = $user->login_id;
//$login_usr  = $user->login_usr;

// If there is no account type details, set to Public (This can caus elooping - invvestigate)
if(!isset($user->login_account_type_id)){
    $login_account_type_id = 9;
} else {
    $login_account_type_id = $user->login_account_type_id;   
}

$login_display_name = $user->login_display_name;

$smarty->assign('login_id',                 $login_id               );
$smarty->assign('login_usr',                $login_usr              );
$smarty->assign('login_account_type_id',    $login_account_type_id  );
$smarty->assign('login_display_name',       $login_display_name     );

// If logout is set, then log user off
if (isset($_GET['action']) && $_GET['action'] == 'logout') {    
    $app->logout();
}

################################
#   Set Global PHP Values      #
################################

// Prevents errors if there is no $_SESSION varibles set via force_page
if(!is_array($_SESSION['post_emulation'])){$_SESSION['post_emulation'] = array();}
 
// Merge the $_GET, $_POST and emulated $_POST
$VAR = array_merge($_GET, $_POST, $_SESSION['post_emulation']);

// Delete the post_emulation array as varibles stored there are no longer needed
unset($_SESSION['post_emulation']);

// These are used globally
$workorder_id       = $VAR['workorder_id'];
$customer_id        = $VAR['customer_id'];
$expense_id         = $VAR['expense_id'];
$refund_id          = $VAR['refund_id'];
$supplier_id        = $VAR['supplier_id'];
$invoice_id         = $VAR['invoice_id'];
$schedule_id        = $VAR['schedule_id'];
$giftcert_id        = $VAR['giftcert_id'];

// If no schedule year set, use today's year
if(isset($VAR['schedule_start_year'])) {$schedule_start_year = $VAR['schedule_start_year'];} else {$schedule_start_year = date('Y');}

// If no schedule month set, use today's month
if(isset($VAR['schedule_start_month'])) {$schedule_start_month = $VAR['schedule_start_month'];} else {$schedule_start_month = date('m');}

// If no schedule day set, use today's day
if(isset($VAR['schedule_start_day'])) {$schedule_start_day = $VAR['schedule_start_day'];} else {$schedule_start_day = date('d');}

// Make sure an employee is always set - if no employee is set use the logged in user
//if(isset($VAR['employee_id'])) {$employee_id = $VAR['employee_id'];} else {$employee_id = $_SESSION['login_id'];}  // this might not be required
$employee_id        = $VAR['employee_id'];

// Get the page number if it exists or set to page number to 1 if not
if(isset($VAR['page_no'])) {$page_no = $VAR['page_no'];} else {$page_no = 1;}

##########################################
#   Set Global PHP Values from QWcrm     #
##########################################

// Set Date Format
define('DATE_FORMAT', get_company_details($db, 'DATE_FORMAT'));

##########################################################################
#   Assign variables into smarty for use by all native module templates  #
##########################################################################

// QWcrm System Directory Variables
//$smarty->assign('media_dir',   MEDIA_DIR                );      // not currently used

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
$smarty->assign('refund_id',                $refund_id                  );
$smarty->assign('supplier_id',              $supplier_id                );
$smarty->assign('invoice_id',               $invoice_id                 );
$smarty->assign('schedule_id',              $schedule_id                );
$smarty->assign('schedule_start_year',      $schedule_start_year        );
$smarty->assign('schedule_start_month',     $schedule_start_month       );
$smarty->assign('schedule_start_day',       $schedule_start_day         );
$smarty->assign('giftcert_id',              $giftcert_id                );

// Used throughout the site
$smarty->assign('currency_sym', get_company_details($db,    'CURRENCY_SYMBOL')  );
$smarty->assign('company_logo', get_company_details($db,    'LOGO')             );
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

if($GConfig->maintenance == true){
    
    // set to the maintenance page    
    $page_display_controller = 'modules'.SEP.'core'.SEP.'maintenance.php'; 
    $module     = 'core';
    $page_tpl   = 'maintenance';
    $VAR['theme'] = 'off';   
    
    // If user logged in, then log user off    
    if(isset($user->login_token)) {    
        $app->logout();
    }
    
}

// If there is a page set, verify it 
elseif(isset($VAR['page']) && $VAR['page'] != ''){ 

    // Explode the URL so we can get the module and page_tpl
    list($module, $page_tpl)    = explode(':', $VAR['page']);
    $page_display_controller    = 'modules'.SEP.$module.SEP.$page_tpl.'.php';

    // Check to see if the page exists and set it, otherwise send them to the 404 page
    if (file_exists($page_display_controller)){
        $page_display_controller = 'modules'.SEP.$module.SEP.$page_tpl.'.php';            
    } else {
        
        // set to the 404 error page 
        $page_display_controller = 'modules'.SEP.'core'.SEP.'404.php'; 
        $module     = 'core';
        $page_tpl   = '404';
        
        // Send 404 header
        $VAR['theme'] = 'off';
        header('HTTP/1.1 404 Not Found');
        
    }        

// if no page specified load a default landing page   
} else {        

    if(isset($user->login_token)){
        // If logged in
        $page_display_controller    = 'modules'.SEP.'core'.SEP.'home.php';
        $module                     = 'core';
        $page_tpl                   = 'home';       
    } else {
        // If NOT logged in
        $page_display_controller    = 'modules'.SEP.'core'.SEP.'login.php';
        $module                     = 'core';
        $page_tpl                   = 'login';            
    }

}

###############################################
#    Build and Display the page (as required) #
#    if the user has the correct permissions  #
###############################################

// This varible holds the page as it is built
$BuildPage = '';

/* Check the requested page with 'logged in' user against the ACL for authorisation - if allowed, display */
if(QAuthentication::check_acl($db, $login_account_type_id, $module, $page_tpl)){
    
    // If theme is set to Print mode then fetch the Page Content - Print system will output with its own format without need for headers and footers here
    if ($VAR['theme'] === 'print'){        
        require($page_display_controller);
        goto page_build_end;
    }

    // Set Page Header and Meta Data
    set_page_header_and_meta_data($module, $page_tpl, $VAR['page_title']);    

    // Fetch Header Block
    if($VAR['theme'] != 'off'){        
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_header_block.php');
    } else {
        //echo '<!DOCTYPE html><head></head><body>';
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_header_theme_off_block.php');
    }

    // Fetch Header Legacy Template Code and Menu Block - Customers, Guests and Public users will not see the menu
    if($VAR['theme'] != 'off' && isset($user->login_token) && $login_account_type_id != 7 && $login_account_type_id != 8 && $login_account_type_id != 9){       
        $BuildPage .= $smarty->fetch('core'.SEP.'blocks'.SEP.'theme_header_legacy_supplement_block.tpl');
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_menu_block.php');        
    }    

    // Fetch the Page Content
    require($page_display_controller);    

    // Fetch Footer Legacy Template code Block (closes content table)
    if($VAR['theme'] != 'off' && isset($user->login_token) && $login_account_type_id != 7 && $login_account_type_id != 8 && $login_account_type_id != 9){
        $BuildPage .= $smarty->fetch('core'.SEP.'blocks'.SEP.'theme_footer_legacy_supplement_block.tpl');             
    }

    // Fetch the Footer Block
    if($VAR['theme'] != 'off'){        
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_footer_block.php');        
    }    

    // Fetch the Debug Block
    if($qwcrm_debug == true){
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_debug_block.php');        
        $BuildPage .= "\r\n</body>\r\n</html>";
    } else {
        $BuildPage .= "\r\n</body>\r\n</html>";
    }
    
    page_build_end:
    
}

################################################
#        Access Logging                        #
################################################

// This logs access details to the stats tracker table in the database
if($GConfig->qwcrm_tracker == true){
    write_record_to_tracker_table($db, $page_display_controller, $module, $page_tpl);
}

// This logs access details to the access log
if($GConfig->qwcrm_access_log == true){
    write_record_to_access_log($login_usr);
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
if ($GConfig->gzip == true && $VAR['theme'] !== 'print') {

    $BuildPage = compress_page_output($BuildPage);
    
}
    
################################################
#    Display the Built Page                    #
################################################

echo $BuildPage;