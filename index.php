<?php

################################################
#   Minimum PHP Version                        #
################################################

/*
 * Define the application's minimum supported PHP version as a constant so it can be referenced within the application.
 */

define('QWCRM_MINIMUM_PHP', '5.3.10');

if (version_compare(PHP_VERSION, QWCRM_MINIMUM_PHP, '<')){
    die('Your host needs to use PHP ' . QWCRM_MINIMUM_PHP . ' or higher to run this version of QWCRM!');
}

#################################################
# Debuging Information Start Varible Acqusition #
#################################################

// Saves the start time and memory usage.
$startTime = microtime(1);
$startMem  = memory_get_usage();

################################################
#         Error reporting and headers          #
################################################

/* Used to suppress PHP error Notices - this will overide php.ini settings */

// Turn off all error reporting
//error_reporting(0);

// Report simple running errors
//error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Reporting E_NOTICE can be good too (to report uninitialized
// variables or catch variable name misspellings ...)
//error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);

// Report all errors except E_NOTICE
error_reporting(E_ALL & ~E_NOTICE); // This will only show major errors (default)

// Report all PHP errors (see changelog)
//error_reporting(E_ALL);

// Report all PHP errors
//error_reporting(-1);

// Same as error_reporting(E_ALL);
//ini_set('error_reporting', E_ALL);

// sme as one of the above
//ini_set('track_errors', 1); 

################################################
#          Header                              #
################################################

// Added to eliminate special characters
header('Content-type: text/html; charset=utf-8');

################################################
#         Initialise QWCRM                     #
################################################

require('configuration.php');
require('includes/defines.php');
require(INCLUDES_DIR.'include.php');
require(INCLUDES_DIR.'session.php');
require(INCLUDES_DIR.'auth.php');
require(INCLUDES_DIR.'smarty.php');

################################################
#    Verify QWcrm is installed correctly       #
################################################

verify_qwcrm_is_installed_correctly($db); // works well
 
################################################
#          Authentication                      #
################################################

$auth = new Auth($db, 'index.php', $strKey);

$login_id           = $_SESSION['login_id'];
$login_usr          = $_SESSION['login_usr'];

// If there is no account type details, set to Guest
if(!isset($_SESSION['login_account_type_id'])){
    $login_account_type_id = 8;
} else {
    $login_account_type_id = $_SESSION['login_account_type_id'];   
}

$login_display_name = $_SESSION['login_display_name'];

$smarty->assign('login_id',             $login_id           );
$smarty->assign('login_usr',            $login_usr          );
$smarty->assign('login_account_type_id',   $login_account_type_id );
$smarty->assign('login_display_name',   $login_display_name );

/* If logout is set, then log user off */
if (isset($_GET['action']) && $_GET['action'] == 'logout') {    
    $auth->logout('index.php');
}

################################################
#   Grab &_POST and $_GET values               #
################################################

/*
 * These are used to set varibles that are also used elsewhere (sort of global) not just in index.php
 */

// Merge the $_GET, $_POST for legacy code
$VAR            = array_merge($_GET, $_POST);

// These are used globally but mainly for the menu !!
$wo_id          = $VAR['wo_id'];
$customer_id    = $VAR['customer_id'];
$employee_id    = $VAR['employee_id'];
$expense_id     = $VAR['expense_id'];
$refund_id      = $VAR['refund_id'];
$supplier_id    = $VAR['supplier_id'];
$schedule_id    = $VAR['schedule_id'];

// Get the page number if it exists or set to page number to 1
if(isset($VAR['page_no'])){
    $page_no = $VAR['page_no'];
} else {
    $page_no = 1;
}

##########################################################################
#   Assign variables into smarty for use by all native module templates  #
##########################################################################

// QWcrm System Directory Template Variables
$smarty->assign('root_media_dir',   QWROOT_MEDIA_DIR    );      // set QWcrm root JS directory

// QWcrm Theme Directory Template Variables
$smarty->assign('theme_dir',        THEME_DIR           );      // set theme directory
$smarty->assign('theme_images_dir', THEME_IMAGES_DIR    );      // set theme images directory
$smarty->assign('theme_css_dir',    THEME_CSS_DIR       );      // set theme CSS directory
$smarty->assign('theme_js_dir',     THEME_JS_DIR        );      // set theme JS directory

// These are used globally but mainly for the menu !!
$smarty->assign('wo_id',        $wo_id          );
$smarty->assign('customer_id',  $customer_id    );
$smarty->assign('employee_id',  $employee_id    );              // This is the same as $login_id at some points - when used globally - check
$smarty->assign('expense_id',   $expense_id     );
$smarty->assign('refund_id',    $refund_id      );
$smarty->assign('supplier_id',  $supplier_id    );
$smarty->assign('schedule_id',  $schedule_id    );

// Used throughout the site - could combine these functions into one passing the required field
$smarty->assign('company_logo', get_company_logo($db)       );        
$smarty->assign('currency_sym', get_currency_symbol($db)    );
$smarty->assign('date_format',  get_date_format($db)        );

// Information Message (Green)
if(isset($VAR['information_msg'])){
    $smarty->assign('information_msg', $VAR['information_msg']);
}

// Warning Message (Red)
if(isset($VAR['warning_msg'])){
    $smarty->assign('warning_msg', $VAR['warning_msg']);
}

//-------------schedule------------------------------

// used only in schedule and menu - make neater - sort
// add this one possible to the sections above to keep things in order

if ( $cur_date > 0 ){
    $y1 = $VAR['y'] ;
    $m1 = $VAR['m'];
    $d1 = $VAR['d'];
} else {
    $y1 = (date('Y'));
    $m1 = (date('m'));
    $d1 = (date('d'));
}

$smarty->assign('y1',$y1);
$smarty->assign('m1',$m1);
$smarty->assign('d1',$d1);

$smarty->assign('Y',$Y);
$smarty->assign('m',$m);
$smarty->assign('d',$d);

################################################
#  Page Building logic (Not logged in)         #
################################################

/*
 * This section handles pages that are not within the 'logged in' scope
 */

if(!isset($_SESSION['login_hash'])){ 

    // Is there a page title set
    if(isset($_VAR['page']) && $_VAR['page'] != ''){
        
       // do nothing here and preceed to the next section to extract the page
        
    } else {
        
        /* This builds the landing page when not logged in */
        
        // Load Module Language Translations
        if(!xml2php('core')) {    
            $smarty->assign('error_msg', 'Error in the '.'core'.' language file');
        }

        // Set Page Header and Meta Data
        set_page_header_and_meta_data('core', 'login');
        
        // Display Header Block
        if($VAR['theme'] != 'off'){        
            require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_header_block.php');      
        } else {
            echo '<!DOCTYPE html><head></head><body>';        
        }
        
        // Display the Dashboard Block
        $smarty->display('core'.SEP.'blocks'.SEP.'home_external_dashboard_block.tpl');
        
        // Display the Login Block  
        $smarty->display('core'.SEP.'blocks'.SEP.'home_external_login_block.tpl');
        
        // Display the Footer Block
        if($VAR['theme'] != 'off'){        
            require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_footer_block.php');        
        }    
        
        // Display the Debug Block
        if($qwcrm_debug === 'on'){
            require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_debug_block.php');
            echo '</body></html>';
        } else {
            echo '</body></html>';
        }

        // Skip the rest of the code and goto the logging_section
        goto logging_section;
        
    }
    
}

#############################################
#  Extract Page Parameters and Validate     #
#  the page exists ready for building       #
#############################################

if(isset($VAR['page'])){
    
        // Explode the URL so we can get the module and page
        list($module, $page)        = explode(':', $VAR['page']);
        $page_display_controller    = 'modules'.SEP.$module.SEP.$page.'.php';

        // Check to see if the page exists and set it, otherwise send them to the 404 page
        if (file_exists($page_display_controller)){
            $page_display_controller = 'modules'.SEP.$module.SEP.$page.'.php';
        } else {
            
            $page_display_controller = 'modules'.SEP.'core'.SEP.'404.php';
            
            // Currently these are set to the unknown page and the page request will be denied by the the ACL.
            // This will change these values to the 404 page allowing it to be loaded.          
            $module = 'core';
            $page   = '404';
        }
    } else {
        // If no page is supplied then go to the main page (this assumes you are logged in)
        $page_display_controller = 'modules'.SEP.'core'.SEP.'home.php';
        $module = 'core';
        $page   = 'home';
    }
   
###############################################
#    Build and Display the page (as required) #
#    If the user has the correct permissions  #
###############################################

/* Check the requested page with 'logged in' user against the ACL for authorisation - if allowed, display */
if(check_acl($db, $login_account_type_id, $module, $page)){
    
    // Guests (not logged in) will not see the menu
    
    // Load Module Language Translations
    if(!xml2php($module)) {    
        $smarty->assign('error_msg', 'Error in the '.$module.' language file');
    }
    
    // Set Page Header and Meta Data
    set_page_header_and_meta_data($module, $page, $VAR['page_title']);    
    
    // Display Header Block
    if($VAR['theme'] != 'off'){        
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_header_block.php');      
    } else {
        echo '<!DOCTYPE html><head></head><body>';        
    }
    
    // Display Header Legacy Template Code and Menu Block
    if($VAR['theme'] != 'off' && $login_account_type_id != 8){       
        $smarty->display('core'.SEP.'blocks'.SEP.'theme_header_legacy_supplement_block.tpl');
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_menu_block.php');        
    }    

    // Display the Page Content
    require($page_display_controller);    
  
    // Display Footer Legacy Template code Block (closes content table)
    if($VAR['theme'] != 'off' && $login_account_type_id != 8){
        $smarty->display('core'.SEP.'blocks'.SEP.'theme_footer_legacy_supplement_block.tpl');             
    }
    
    // Display the Footer Block
    if($VAR['theme'] != 'off'){        
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_footer_block.php');        
    }    
    
    // Display the Debug Block
    if($qwcrm_debug === 'on'){
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_debug_block.php');
        echo '</body></html>';
    } else {
        echo '</body></html>';
    }
}

################################################
#        Access Logging                        #
################################################

// Defines the Logging Section
logging_section:
    
// This logs access details to the stats tracker table in the database
if($qwcrm_tracker === 'on'){
    write_record_to_tracker_table($db, $page_display_controller, $module, $page);
}

// This logs access details to the access log
if($qwcrm_access_log === 'on'){
    write_record_to_access_log($login_usr);
}