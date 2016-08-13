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

################################################
#   Debuging Information  - page load speed    #
################################################

// should this be further at the top
function getMicroTime(){  
    list($usec, $sec) = explode(" ", microtime()); 
    return (float)$usec + (float)$sec;
} 

$start = getMicroTime();

// Saves the start time and memory usage.
//$startTime = microtime(1);
//$startMem  = memory_get_usage();

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

//verify_qwcrm_is_installed_correctly($db); // works well
 
################################################
#          Enable Authentication               #
################################################

$auth = new Auth($db, 'login.php', $strKey); // need to chase this, should i be using a nonce / random string

$login_id           = $_SESSION['login_id'];
$login_usr          = $_SESSION['login_usr'];
$login_account_type = $_SESSION['login_account_type'];
$login_display_name = $_SESSION['login_display_name'];

$smarty->assign('login_id',             $login_id           );
$smarty->assign('login_usr',            $login_usr          );
$smarty->assign('login_account_type',   $login_account_type );
$smarty->assign('login_display_name',   $login_display_name );


################################################
#   Grab &_POST and $_GET values               #
################################################

// do these need to be here? (array merge does)

$VAR            = array_merge($_GET, $_POST);
$page_title     = $VAR['page_title'];
//$page           = $VAR['page'];

// These are used globally but mainly for the menu !!
$wo_id          = $VAR['wo_id'];
$customer_id    = $VAR['customer_id'];
$employee_id    = $VAR['employee_id'];
$expense_id     = $VAR['expense_id'];
$refund_id      = $VAR['refund_id'];
$supplier_id    = $VAR['supplier_id'];

################################################
#   Should I log off                           # // if this is before array merege chang the $_post / $_GET etc to make it work
################################################

// If log off is set then log user off
if (isset($VAR['action']) && $VAR['action'] == 'logout') {    
    $auth->logout('login.php');
}

##########################################################################
#   Assign variables into smarty for use by all native module templates  #
##########################################################################

// These are used globally but mainly for the menu !!
$smarty->assign('wo_id',        $wo_id          );
$smarty->assign('customer_id',  $customer_id    );
$smarty->assign('employee_id',  $employee_id    ); // This is the same as $login_id at some points - when used globally - check
$smarty->assign('expense_id',   $expense_id     );
$smarty->assign('refund_id',    $refund_id      );
$smarty->assign('supplier_id',  $supplier_id    );

// Used Throughout the site
$smarty->assign('company_logo', get_company_logo($db)       );        
$smarty->assign('currency_sym', get_currency_symbol($db)    );
$smarty->assign('date_format',  get_date_format($db)        );

/* Work Order ID 
if(isset($_GET['wo_id'])){
    $smarty->assign('wo_id', $_GET['wo_id']);
    global $wo_id;
} else {
    $smarty->assign('wo_id','0');
}

if ($VAR['wo_id'] == '' || $VAR['wo_id'] < "1" )
{
$wo_id = 0 ;
} else {
$wo_id = $VAR['wo_id'] ;
$woid = $VAR['wo_id'] ;
}

/*if ($VAR['woid'] == '' || $VAR['woid'] < "1" )
{
$wo_id = 0 ;
} else {
$wo_id = $VAR['woid'] ;
}
 * 
 */
/* customer ID 
if(isset($_GET['customer_id'])){
    $smarty->assign('customer_id', $_GET['customer_id']);
    global $customer_id;
} else {
    $smarty->assign('customer_id','0');
}
*/


/*
 * taken from url build
 * 
// remove page from the $_GET array we dont want it to pass the options
unset($VAR['page']);

// Define the global options for each page
foreach($VAR as $key=>$val){
    define($key, $val);
}
 *
 */


/*
// from theme_header_block.php


  



// theme_header_block.php


$sch_id = $VAR['sch_id'];
$today2 = (Date("d")); 
if ( $cur_date > 0 )
{
$y1 = $VAR['y'] ;
$m1 = $VAR['m'];
$d1 = $VAR['d'];
} else {
$y1 =    (Date("Y"));
$m1 =    (Date("m"));
$d1 =    (Date("d"));
}
$smarty->assign('y1',$y1);
$smarty->assign('m1',$m1);
$smarty->assign('d1',$d1);
$smarty->assign('Y',$Y);
$smarty->assign('m',$m);
$smarty->assign('d',$d);
$smarty->assign('today2',$today2);


// Get the page number we are on if first page set to 1 - should i se this here rather than loads
if(!isset($VAR['page_no'])){
    $page_no = 1;
} else {
    $page_no = $VAR['page_no'];
}
    

*/






/* Message - Legacy Message Feature - Possibly will use it in future*/
if(isset($VAR['msg'])){
    $smarty->assign('msg', $VAR['msg']);
}

//////////////

#####################################
#    Set the Page Title             #
#####################################  

/* Page Title */

// i could write a function to build all page titles here and remove from the url
if(isset($page_title)){
    $smarty->assign('page_title', $page_title); 
} else {
    $page_title = 'Home';
    $smarty->assign('page_title', $page_title);
}  

#############################################
#  Extract Page Parameters and Validate     #
#  the page exists ready for building       #
#############################################

if(isset($VAR['page'])){
    
        // Explode the URL so we can get the module and page
        list($module, $page)        = explode(':', $VAR['page']);
        $page_display_controller    = 'modules'.SEP.$module.SEP.$page.'.php';

        // Check to see if the page exists and set it, other wise send them to the 404 page
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
        // If no page is supplied then go to the main page
        $page_display_controller = 'modules'.SEP.'core'.SEP.'home.php';
        $module = 'core';
        $page   = 'home';
    }
   
###############################################
#    Build and Display the page (as required) #
#    If the user has the correct permissions  #
###############################################

/* Check ACL for page request - if ok display */
if(!check_acl($db, $login_id, $module, $page)){    
    force_page('core','error','error_msg=You do not have permission to access this '.$module.':'.$page.'&menu=1');
} else {    
   
    // Display Header and Menu
    if($VAR['theme'] != 'off'){        
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_header_block.php');
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_menu_block.php');        
    }

    // Display the Page Content
    require($page_display_controller);    
  
    // Display the Footer
    if($VAR['theme'] != 'off'){
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_footer_block.php');
        if ($qwcrm_debug != 'on'){
            echo '</body></html>';
        }
    }
    
    // Display the Debug
    if($qwcrm_debug === 'on'){
        require('modules'.SEP.'core'.SEP.'blocks'.SEP.'theme_debug_block.php');
        echo '</body></html>';
    }
}

################################################
#         Logging                              #
################################################

/* This records access details to the stats tracker table in the database */
if($qwcrm_tracker === 'on'){
    write_record_to_tracker_table($db, $page_display_controller, $page, $module);
}

/* This records access details to the access log */
if($qwcrm_access_log === 'on'){
    write_record_to_access_log($login_usr);
}