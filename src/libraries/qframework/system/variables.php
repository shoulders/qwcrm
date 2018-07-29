<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

################################################
#  Load and set the System's global variables  #
################################################

function load_system_variables(&$VAR) {
    
    $smarty = QFactory::getSmarty();

    // Acquire variables from classes examples
    // $user->login_user_id;                                    // This is a public variable defined in the class
    // QFactory::getUser()->login_user_id;                      // Static method to get variable
    // QFactory::getConfig()->get('sef')                        // This is a variable stored in the registry
    // $config = QFactory::getConfig();  | $config->get('sef')  // Get teh config into a variable and then you can call the config settings
    // $QConfig->sef                                            // Only works for the QConfig class I made in the root

    ##########################################################
    #   Assign Global PHP Variables                          #
    ##########################################################

    // Merge the $_GET, $_POST and emulated $_POST - 1,2,3   1 is overwritten by 2, 2 is overwritten by 3.
    if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
        $VAR = array_merge($_POST, $_GET, $VAR, postEmulationReturnStore());    
    } else {
        $VAR = array_merge($_POST, $_GET, $VAR);
    }

    // Set Date Format - If there are DATABASE ERRORS, they will present here (white screen) when verify QWcrm function is not on
    if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
        define('DATE_FORMAT', get_company_details('date_format'));
    }

    ##########################################################################
    #   Assign variables into Smarty for use by component templates          #
    ##########################################################################

    // QWcrm System Folders
    $smarty->assign('base_path',                QWCRM_BASE_PATH             );      // set base path, useful for javascript links i.e. 404.tpl
    $smarty->assign('media_dir',                QW_MEDIA_DIR                );      // set media directory

    // QWcrm Theme Directory Template Variables
    $smarty->assign('theme_dir',                THEME_DIR                   );      // set theme directory
    $smarty->assign('theme_images_dir',         THEME_IMAGES_DIR            );      // set theme images directory
    $smarty->assign('theme_css_dir',            THEME_CSS_DIR               );      // set theme CSS directory
    $smarty->assign('theme_js_dir',             THEME_JS_DIR                );      // set theme JS directory

    // QWcrm Theme Directory Template Smarty File Include Path Variables
    $smarty->assign('theme_js_dir_finc',        THEME_JS_DIR_FINC           );
    
    // This assigns framework globals to smarty and also prevents undefined variable errors (mainly for the menu)
    isset($VAR['user_id'])      ? $smarty->assign('user_id', $VAR['user_id'])           : $smarty->assign('user_id', null);
    isset($VAR['employee_id'])  ? $smarty->assign('employee_id', $VAR['employee_id'])   : $smarty->assign('employee_id', null);
    isset($VAR['client_id'])    ? $smarty->assign('client_id', $VAR['client_id'])       : $smarty->assign('client_id', null);
    isset($VAR['workorder_id']) ? $smarty->assign('workorder_id', $VAR['workorder_id']) : $smarty->assign('workorder_id', null);
    isset($VAR['invoice_id'])   ? $smarty->assign('invoice_id', $VAR['invoice_id'])     : $smarty->assign('invoice_id', null);
    isset($VAR['payment_id'])   ? $smarty->assign('payment_id', $VAR['payment_id'])     : $smarty->assign('payment_id', null);
    isset($VAR['giftcert_id'])  ? $smarty->assign('giftcert_id', $VAR['giftcert_id'])   : $smarty->assign('giftcert_id', null);
    isset($VAR['expense_id'])   ? $smarty->assign('expense_id', $VAR['expense_id'])     : $smarty->assign('expense_id', null);
    isset($VAR['refund_id'])    ? $smarty->assign('refund_id', $VAR['refund_id'])       : $smarty->assign('refund_id', null);
    isset($VAR['supplier_id'])  ? $smarty->assign('supplier_id', $VAR['supplier_id'])   : $smarty->assign('supplier_id', null);
    isset($VAR['schedule_id'])  ? $smarty->assign('schedule_id', $VAR['schedule_id'])   : $smarty->assign('schedule_id', null);
   
    // Used throughout the site
    if(!defined('QWCRM_SETUP') || QWCRM_SETUP != 'install') {
        $smarty->assign('currency_sym', get_company_details('currency_symbol')     );
        $smarty->assign('company_logo', QW_MEDIA_DIR . get_company_details('logo') );
        $smarty->assign('date_format',  DATE_FORMAT                                );
    }

    #############################
    #        Messages           #
    #############################

    // Information Message (Green)
    $VAR['information_msg'] = isset($VAR['information_msg']) ? $VAR['information_msg'] : null;
    $smarty->assign('information_msg', $VAR['information_msg']);
            
    // Warning Message (Red)
    $VAR['warning_msg'] = isset($VAR['warning_msg']) ? $VAR['warning_msg'] : null;
    $smarty->assign('warning_msg', $VAR['warning_msg']);
    
    #############################
    #        Exit Function      #
    #############################
    
    return;
    
}

#####################################
#  Set the User's Smarty Variables  #  // Empty if not logged in or installing (except for usergroup)
#####################################

function set_user_smarty_variables() {
    
    $smarty = QFactory::getSmarty();
    $user = QFactory::getUser();    
    
    $smarty->assign('login_user_id',            $user->login_user_id          );
    $smarty->assign('login_username',           $user->login_username         );
    $smarty->assign('login_usergroup_id',       $user->login_usergroup_id     );
    $smarty->assign('login_display_name',       $user->login_display_name     );
    $smarty->assign('login_token',              $user->login_token            );
    $smarty->assign('login_is_employee',        $user->login_is_employee      );
    $smarty->assign('login_client_id',          $user->login_client_id        );
    
    return;
    
}