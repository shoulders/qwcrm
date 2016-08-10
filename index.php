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
#          Is Installed Check                  #
################################################

/* 
 * check if lock file exists, if not, we need to install
 * If installed remove the install directory
 */
if(!is_file('cache/lock')){
    echo('
        <script type="text/javascript">            
            window.location = "install"           
        </script>
        ');
} else if(is_dir('install') ) {
    echo('<a style="color: red;">The install Directory Exists!! Please Rename or remove the install directory.</a>');
    die;
}

################################################
#   Grab &_POST and $_GET values               #
################################################

// do these need to be here? (array merge does)

$VAR            = array_merge($_GET, $_POST);
$page_title     = $VAR['page_title'];



//$page           = $VAR['page'];
//$login_usr      = $_SESSION['login_usr'];
//$login_usr  = $_POST['login_id'];
/*
$wo_id          = $VAR['wo_id'];
$customer_id    = $VAR['customer_id'];
 * */

// from theme_header_block.php
/*
$ip             = $_SERVER['REMOTE_ADDR'];
$login_usr      = $_SESSION['login_usr'];
$wo_id          = $VAR['wo_id'];
$customer_id    = $VAR['customer_id'];
$expenseID      = $VAR['expenseID'];
$refundID       = $VAR['refundID'];
$supplierID     = $VAR['supplierID'];
$employee_id    = $VAR['employee_id'];
*/
 
################################################
#         Initialise QWCRM                     #
################################################

require('configuration.php');
require('includes/defines.php');
require(INCLUDES_DIR.'include.php');
require(INCLUDES_DIR.'session.php');
require(INCLUDES_DIR.'auth.php');
require(INCLUDES_DIR.'smarty.php');
require(INCLUDES_DIR.'acl.php');

################################################
#          Enable Authentication               #
################################################

$auth = new Auth($db, 'login.php', $strKey); // need to chase this, should i be using a nonce / random string

$login_usr          = $_SESSION['login_usr'];
$login_account_type = $_SESSION['login_account_type'];
$login_display_name = $_SESSION['login_display_name'];

################################################
#   should I log off                           #
################################################

// If log off is set then log user off
if (isset($VAR['action']) && $VAR['action'] == 'logout') {    
    $auth->logout('login.php');
}

##########################################################################
#   Assign variables into smarty for use by all native module templates  #
##########################################################################

/* Work Order ID 
if(isset($_GET['wo_id'])){
    $smarty->assign('wo_id', $_GET['wo_id']);
    global $wo_id;
} else {
    $smarty->assign('wo_id','0');
}

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


if(!$login_usr)
{
    $smarty->assign('login_usr', '');
} else {
    $smarty->assign('login_usr', $login_usr);
    $smarty->assign('display_login', $login_usr);
    $smarty->assign('login_id', $_SESSION['login_id']);
    $smarty->assign('wo_id', $wo_id);
    $smarty->assign('customer_id',$customer_id);
    $smarty->assign('expenseID', $expenseID);
    $smarty->assign('refundID', $refundID);
    $smarty->assign('supplierID', $supplierID);
    $smarty->assign('ip',$ip);
    $smarty->assign('employee_id',$employee_id);
}

$smarty->assign('msg', $msg);


// theme_header_block.php

//$employee_id = $VAR['employee_id'];
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
*/

//////////////

// workorder/print.php has company code in it
/* get company info for defaults */
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY, '.PRFX.'VERSION ORDER BY  '.PRFX.'VERSION.`VERSION_INSTALLED` DESC LIMIT 1';
if(!$rs = $db->execute($q)){
force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
exit;
}

$smarty->assign('version', $rs->fields['VERSION_NAME']);                // core/footer.tpl and core/submit.tpl
$smarty->assign('company_name', $rs->fields['COMPANY_NAME']);           // billing/display_gift.tpl , billing/print_gift.tpl, parts/print_result.tpl, parts/view.tpl
$smarty->assign('company_address', $rs->fields['COMPANY_ADDRESS']);     // parts/print_result.tpl, parts/view.tpl
$smarty->assign('company_city', $rs->fields['COMPANY_CITY']);           // employees/new.tpl, parts/print_result.tpl, parts/view.tpl
$smarty->assign('company_state', $rs->fields['COMPANY_STATE']);         // employees/new.tpl, parts/view.tpl
$smarty->assign('company_zip', $rs->fields['COMPANY_ZIP']);             // employees/new.tpl, parts/print_result.tpl, parts/view.tpl
$smarty->assign('company_country', $rs->fields['COMPANY_COUNTRY']);     // not used
$smarty->assign('company_phone',$rs->fields['COMPANY_PHONE']);          // billing/display_gift.tpl , billing/print_gift.tpl, parts/print_result.tpl, parts/view.tpl
$smarty->assign('company_email',$rs->fields['COMPANY_EMAIL']);          // not used
$smarty->assign('company_mobile',$rs->fields['COMPANY_MOBILE']);        // not used
$smarty->assign('company_logo',$rs->fields['COMPANY_LOGO']);            // core/login.tpl, workorder/print_customer_workorder_slip.tpl, workorder/print_job_sheet.tpl, workorder/print_technician_workorder_slip.tpl
$smarty->assign('currency_sym',$rs->fields['COMPANY_CURRENCY_SYMBOL']); // used throughout the site
$smarty->assign('currency_code',$rs->fields['COMPANY_CURRENCY_CODE']);  // only in invoice/print_html.tpl
$smarty->assign('date_format',$rs->fields['COMPANY_DATE_FORMAT']);      // used throughout the site
$smarty->assign('company_email_from',$rs->fields['COMPANY_EMAIL_FROM']);// not used
$smarty->assign('email_server',$rs->fields['COMPANY_EMAIL_SERVER']);    // only customer/email.tpl
$smarty->assign('email_server_port',$rs->fields['COMPANY_EMAIL_PORT']); // only customer/email.tpl
$smarty->assign('email_username',$rs->fields['COMPANY_SMTP_USERNAME']); // not used
$smarty->assign('email_password',$rs->fields['COMPANY_SMTP_PASSWORD']); // not used

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
            
            // even though this is set, the ACL is still checking $module and $page againt access so set them to the 404 page
            $module = 'core';
            $page   = '404';
        }
    } else {
        // If no page is supplied then go to the main page
        $page_display_controller = 'modules'.SEP.'core'.SEP.'main.php';
        $module = 'core';
        $page   = 'main';
    }
    
###############################################
#    Build and Display the page (as required) #
#    If the user has the correct permissions  #
###############################################

/* Check ACL for page request - if ok display */
if(!check_acl($db, $module, $page)){    
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

// should this have its own page? - no, single function here will suffice - main include though

$tracker_page = "$module:$page"; // what is this for - not used anywhere

/* Tracker code */
function getIP(){
    if (getenv('HTTP_CLIENT_IP')) {$ip = getenv('HTTP_CLIENT_IP');}
    elseif (getenv('HTTP_X_FORWARDED_FOR')) {$ip = getenv('HTTP_X_FORWARDED_FOR');}
    elseif (getenv('REMOTE_ADDR')) {$ip = getenv('REMOTE_ADDR');}
    else {$ip = 'UNKNOWN';}
    return $ip;
}

$q = 'INSERT into '.PRFX.'TRACKER SET
   date          = '. $db->qstr( time()                     ).',
   ip            = '. $db->qstr( getIP()                    ).',
   uagent        = '. $db->qstr( getenv('HTTP_USER_AGENT')  ).',
   full_page     = '. $db->qstr( $the_page                  ).',
   module        = '. $db->qstr( $module                    ).',
   page          = '. $db->qstr( $page                      ).',
   referer       = '. $db->qstr( getenv('HTTP_REFERER')     );

   if(!$rs = $db->Execute($q)) {
      echo 'Error inserting tracker :'. $db->ErrorMsg();
   }
