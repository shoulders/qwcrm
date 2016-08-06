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
#         error reporting and headers          #
################################################

/* Used to suppress PHP error Notices */
//error_reporting(E_ALL & ~E_NOTICE);
error_reporting(E_ERROR);

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

/*
$wo_id          = $VAR['wo_id'];
$customer_id    = $VAR['customer_id'];
 * */
 
$id             = $login_id; // this should be replaced with $login_id anyway - page tile, msg , varible loops etc..

// add varible calls here - i can sort them at a later date

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

$auth = new Auth($db, 'login.php', 'secret'); // secret possible = $strKey fron configuration.php

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

// workorder/print.php has company code in it
/* get company info for defaults */
$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY, '.PRFX.'VERSION ORDER BY  '.PRFX.'VERSION.`VERSION_INSTALLED` DESC LIMIT 1';
if(!$rs = $db->execute($q)){
force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
exit;
}

$smarty->assign('version', $rs->fields['VERSION_NAME']);
$smarty->assign('company_name', $rs->fields['COMPANY_NAME']);
$smarty->assign('company_address', $rs->fields['COMPANY_ADDRESS']);
$smarty->assign('company_city', $rs->fields['COMPANY_CITY']);
$smarty->assign('company_state', $rs->fields['COMPANY_STATE']);
$smarty->assign('company_zip', $rs->fields['COMPANY_ZIP']);
$smarty->assign('company_country', $rs->fields['COMPANY_COUNTRY']);
$smarty->assign('company_phone',$rs->fields['COMPANY_PHONE']);
$smarty->assign('company_email',$rs->fields['COMPANY_EMAIL']);
$smarty->assign('company_mobile',$rs->fields['COMPANY_MOBILE']);
$smarty->assign('company_logo',$rs->fields['COMPANY_LOGO']);
$smarty->assign('currency_sym',$rs->fields['COMPANY_CURRENCY_SYMBOL']);
$smarty->assign('currency_code',$rs->fields['COMPANY_CURRENCY_CODE']);
$smarty->assign('date_format',$rs->fields['COMPANY_DATE_FORMAT']);
$smarty->assign('company_email_from',$rs->fields['COMPANY_EMAIL_FROM']);
$smarty->assign('email_server',$rs->fields['COMPANY_EMAIL_SERVER']);
$smarty->assign('email_server_port',$rs->fields['COMPANY_EMAIL_PORT']);
$smarty->assign('email_username',$rs->fields['COMPANY_SMTP_USERNAME']);
$smarty->assign('email_password',$rs->fields['COMPANY_SMTP_PASSWORD']);

/* Others */
$smarty->assign('id', $id);

##############################################################
#    Url Builder This grabs gets and post and builds the url # 
#    conection strings ($_POST has priority)                 #
##############################################################

// This section is a real mess - alot of the suff is not needed also use $VAR

if(!isset($_POST['page'])){
    if ( $_GET['page']){
        
        // Explode the url so we can get the module and page
        list($module, $page) = explode(":", $_GET['page']);
        $the_page = 'modules'.SEP.$module.SEP.$page.'.php';

        // remove page from the $_GET array we dont want it to pass the options
        unset($_GET['page']);

        // Define the global options for each page
        foreach($_GET as $key=>$val){
            define($key, $val);
        }

        // Check to see if the page is real other wise send em a 404
        if (file_exists($the_page)){
            $the_page = 'modules'.SEP.$module.SEP.$page.'.php';
        } else {
            $the_page = 'modules'.SEP.'core'.SEP.'404.php';
        }
    } else {
        // If no page is supplied then go to the main page
        $the_page = 'modules'.SEP.'core'.SEP.'main.php';
    }
} else {
    // Explode the url so we can get the module and page
    list($module, $page) = explode(":", $_POST['page']);  //list($module, $page, $variables) would split into 3, where currenltly only split in too 2 chunks, check this
    $the_page = 'modules'.SEP.$module.SEP.$page.'.php';

    // remove page from the $_GET array we dont want it to pass the options
    unset($_POST['page']);

    // Define the global options for each page - is this needed - possibly rem it out for future use
    foreach($_POST as $key=>$val){
        define($key, $val);
    }

    // Check to see if the page is real other wise send em a 404
    if ( file_exists ($the_page) ) {
        $the_page= 'modules'.SEP.$module.SEP.$page.'.php';
    } else {
        $the_page= 'modules'.SEP.'core'.SEP.'404.php';
    }
}

$tracker_page = "$module:$page"; // what is this for - not used anywhere

#####################################
#    Display the pages              #
#####################################  

/* Work Order ID */
if(isset($_GET['wo_id'])){
    $smarty->assign('wo_id', $_GET['wo_id']);
    global $wo_id;
} else {
    $smarty->assign('wo_id','0');
}

/* customer ID */
if(isset($_GET['customer_id'])){
    $smarty->assign('customer_id', $_GET['customer_id']);
    global $customer_id;
} else {
    $smarty->assign('customer_id','0');
}

/* Gets the error details and sets a page title if error set */
//require('modules'.SEP.'core'.SEP.'error.php');

/* Page Title */

// i could write a function to build all page titles here and remove from the url
if(isset($page_title)){
    $smarty->assign('page_title', $page_title); 
} else {
    $page_title = 'Home';
    $smarty->assign('page_title', $page_title);
}  

/* Message - Legacy Message Feature - Possibly will use it in future*/
if(isset($VAR['msg'])){
    $smarty->assign('msg', $VAR['msg']);
}

//tmpl=component or tmpl=0
/* If escape=1 varible is set do not load the template wrapper - useful for printing */
if($VAR['escape'] != 1 ){
    require('modules'.SEP.'core'.SEP.'header.php');
    require('modules'.SEP.'core'.SEP.'navigation.php');
    require('modules'.SEP.'core'.SEP.'company.php');
}
    
/* check acl for page request - if ok display */
if(!check_acl($db, $module, $page)){
    force_page('core','error&error_msg=You do not have permission to access this '.$module.':'.$page.'&menu=1');
} else { 
    require($the_page);
}

// dont show the footer in templess mode - this has diagnostics in
if($VAR['escape'] != 1 ){
    require('modules'.SEP.'core'.SEP.'footer.php');
}

################################################
#         Logging                              #
################################################

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
   uagent        = '. $db->qstr( getenv(HTTP_USER_AGENT)    ).',
   full_page     = '. $db->qstr( $the_page                  ).',
   module        = '. $db->qstr( $module                    ).',
   page          = '. $db->qstr( $page                      ).',
   referer       = '. $db->qstr( getenv(HTTP_REFERER)       );

   if(!$rs = $db->Execute($q)) {
      echo 'Error inserting tracker :'. $db->ErrorMsg();
   }
   
 