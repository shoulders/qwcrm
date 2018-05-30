<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

##########################################################
#   Assign the User's Variables to PHP and Smarty        #
##########################################################

// Load current user object (empty if not logged in or installing)
if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
    $user = QFactory::getUser();
}

// User Profile Examples
// $user->login_user_id; - this is a variable defined in the calss
// QFactory::getUser()->login_user_id;
// QFactory::getConfig()->get('sef') - this is a variable stored in the registry
// $config = QFactory::getConfig();  | $config->get('sef')
// $QConfig->sef - only works for the QConfig class I made in the root

// Assign User varibles to smarty
$smarty->assign('login_user_id',            $user->login_user_id          );
$smarty->assign('login_username',           $user->login_username         );
$smarty->assign('login_usergroup_id',       $user->login_usergroup_id     );
$smarty->assign('login_display_name',       $user->login_display_name     );
$smarty->assign('login_token',              $user->login_token            );
$smarty->assign('login_is_employee',        $user->login_is_employee      );
$smarty->assign('login_customer_id',        $user->login_customer_id      );

################################################
#   Update Last Active Times                   #
################################################

// Logged in Users
if($user->login_user_id) { update_user_last_active($db, $user->login_user_id); }

################################
#   Set Global PHP Values      #
################################ 

// Make sure $VAR exist as an array, $VAR is not always created upstream.
if(!isset($VAR)) { $VAR = array(); }

// Merge the $_GET, $_POST and emulated $_POST - 1,2,3   1 is overwritten by 2, 2 is overwritten by 3.
if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
    $VAR = array_merge($_POST, $_GET, $VAR, postEmulationReturnStore());    
} else {
    $VAR = array_merge($_POST, $_GET, $VAR);
}

// These are used globally and also a workaround for undefined indexes
$VAR['workorder_id']   =   isset($VAR['workorder_id']) ? $VAR['workorder_id']  : null;
$VAR['customer_id']    =   isset($VAR['customer_id'])  ? $VAR['customer_id']   : null;
$VAR['expense_id']     =   isset($VAR['expense_id'])   ? $VAR['expense_id']    : null;
$VAR['refund_id']     =   isset($VAR['refund_id'])    ? $VAR['refund_id']     : null;
$VAR['supplier_id']    =   isset($VAR['supplier_id'])  ? $VAR['supplier_id']   : null;
$VAR['invoice_id']    =   isset($VAR['invoice_id'])   ? $VAR['invoice_id']    : null;
$VAR['schedule_id']    =   isset($VAR['schedule_id'])  ? $VAR['schedule_id']   : null;
$VAR['giftcert_id']    =   isset($VAR['giftcert_id'])  ? $VAR['giftcert_id']   : null;
$VAR['user_id']       =   isset($VAR['user_id'])      ? $VAR['user_id']       : null;
$VAR['employee_id']    =   isset($VAR['employee_id'])  ? $VAR['employee_id']   : null;
$VAR['page_no']        =   isset($VAR['page_no'])      ? $VAR['page_no']       : '1';

// These are used globally and also a workaround for undefined indexes
$workorder_id   =   isset($VAR['workorder_id']) ? $VAR['workorder_id']  : null;
$customer_id    =   isset($VAR['customer_id'])  ? $VAR['customer_id']   : null;
$expense_id     =   isset($VAR['expense_id'])   ? $VAR['expense_id']    : null;
$refund_id      =   isset($VAR['refund_id'])    ? $VAR['refund_id']     : null;
$supplier_id    =   isset($VAR['supplier_id'])  ? $VAR['supplier_id']   : null;
$invoice_id     =   isset($VAR['invoice_id'])   ? $VAR['invoice_id']    : null;
$schedule_id    =   isset($VAR['schedule_id'])  ? $VAR['schedule_id']   : null;
$giftcert_id    =   isset($VAR['giftcert_id'])  ? $VAR['giftcert_id']   : null;
$user_id        =   isset($VAR['user_id'])      ? $VAR['user_id']       : null;
$employee_id    =   isset($VAR['employee_id'])  ? $VAR['employee_id']   : null;
$page_no        =   isset($VAR['page_no'])      ? $VAR['page_no']       : '1';

$skip_logging   =   isset($skip_logging)        ? $skip_logging         : null;

##########################################
#   Set Global PHP Values from QWcrm     #
##########################################

// Set Date Format
if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
    define('DATE_FORMAT', get_company_details($db, 'date_format'));             // If there are DATABASE ERRORS, they will present here (white screen) when verify QWcrm function is not on 
}

##########################################################################
#   Assign variables into smarty for use by component templates          #
##########################################################################

// QWcrm System Folders
$smarty->assign('media_dir',                QW_MEDIA_DIR                   );      // set media directory

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
if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
    $smarty->assign('currency_sym', get_company_details($db, 'currency_symbol')     );
    $smarty->assign('company_logo', QW_MEDIA_DIR . get_company_details($db, 'logo')    );
    $smarty->assign('date_format',  DATE_FORMAT                                     );
}

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