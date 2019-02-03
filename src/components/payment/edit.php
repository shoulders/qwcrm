<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'user.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');
require(INCLUDES_DIR.'payment.php');

// Check if we have a payment_id
if(!isset($VAR['payment_id']) || !$VAR['payment_id']) {
    force_page('payment', 'search', 'warning_msg='._gettext("No Payment ID supplied."));
}

// Check if payment can be edited
if(!check_payment_can_be_edited($VAR['payment_id'])) {
    force_page('payment', 'details&payment_id='.$VAR['payment_id'], 'warning_msg='._gettext("You cannot edit this payment because its status does not allow it."));
}

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
        update_payment($VAR);        
        force_page('payment', 'details', 'payment_id='.$VAR['payment_id'].'&information_msg='._gettext("Payment updated successfully.")); 

} else {
    
    $payment_details = get_payment_details($VAR['payment_id']);
    
    // Build the page
    $smarty->assign('client_display_name',      get_client_details($payment_details['client_id'], 'display_name'));
    $smarty->assign('employee_display_name',    get_user_details($payment_details['employee_id'], 'display_name'));
    $smarty->assign('payment_types',            get_payment_types()    );
    $smarty->assign('payment_methods',          get_payment_methods('receive'));
    $smarty->assign('payment_statuses',         get_payment_statuses() );
    $smarty->assign('payment_details', $payment_details);
    $BuildPage .= $smarty->fetch('payment/edit.tpl');
    
}