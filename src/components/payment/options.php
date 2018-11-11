<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'payment.php');

/* Prevent undefined variable errors (does the same as below)
$VAR['bank_transfer']['send'] = isset($VAR['bank_transfer']['send']) ? $VAR['bank_transfer']['send'] : null;
$VAR['bank_transfer']['receive'] = isset($VAR['bank_transfer']['receive']) ? $VAR['bank_transfer']['receive'] : null;
$VAR['bank_transfer']['active'] = isset($VAR['bank_transfer']['active']) ? $VAR['bank_transfer']['active'] : null;
$VAR['card']['send'] = isset($VAR['card']['send']) ? $VAR['card']['send'] : null;
$VAR['card']['receive'] = isset($VAR['card']['receive']) ? $VAR['card']['receive'] : null;
$VAR['card']['active'] = isset($VAR['card']['active']) ? $VAR['card']['active'] : null;
$VAR['cash']['send'] = isset($VAR['cash']['send']) ? $VAR['cash']['send'] : null;
$VAR['cash']['receive'] = isset($VAR['cash']['receive']) ? $VAR['cash']['receive'] : null;
$VAR['cash']['active'] = isset($VAR['cash']['active']) ? $VAR['cash']['active'] : null;
$VAR['cheque']['send'] = isset($VAR['cheque']['send']) ? $VAR['cheque']['send'] : null;
$VAR['cheque']['receive'] = isset($VAR['cheque']['receive']) ? $VAR['cheque']['receive'] : null;
$VAR['cheque']['active'] = isset($VAR['cheque']['active']) ? $VAR['cheque']['active'] : null;
$VAR['direct_debit']['send'] = isset($VAR['direct_debit']['send']) ? $VAR['direct_debit']['send'] : null;
$VAR['direct_debit']['receive'] = isset($VAR['direct_debit']['receive']) ? $VAR['direct_debit']['receive'] : null;
$VAR['direct_debit']['active'] = isset($VAR['direct_debit']['active']) ? $VAR['direct_debit']['active'] : null;
$VAR['gift_certificate']['send'] = isset($VAR['gift_certificate']['send']) ? $VAR['gift_certificate']['send'] : null;
$VAR['gift_certificate']['receive'] = isset($VAR['gift_certificate']['receive']) ? $VAR['gift_certificate']['receive'] : null;
$VAR['gift_certificate']['active'] = isset($VAR['gift_certificate']['active']) ? $VAR['gift_certificate']['active'] : null;
$VAR['other']['send'] = isset($VAR['other']['send']) ? $VAR['other']['send'] : null;
$VAR['other']['receive'] = isset($VAR['other']['receive']) ? $VAR['other']['receive'] : null;
$VAR['other']['active'] = isset($VAR['other']['active']) ? $VAR['other']['active'] : null;
$VAR['paypal']['send'] = isset($VAR['paypal']['send']) ? $VAR['paypal']['send'] : null;
$VAR['paypal']['receive'] = isset($VAR['paypal']['receive']) ? $VAR['paypal']['receive'] : null;
$VAR['paypal']['active'] = isset($VAR['paypal']['active']) ? $VAR['paypal']['active'] : null;
*/

// Prevent undefined variable errors (from checkboxes)
$checkboxes = array('bank_transfer', 'card', 'cash', 'cheque', 'direct_debit', 'gift_certificate', 'other', 'paypal');
foreach($checkboxes as $checkbox) {     
    $VAR[$checkbox]['send']     = isset($VAR[$checkbox]['send'])    ? $VAR[$checkbox]['send']    : '0';
    $VAR[$checkbox]['receive']  = isset($VAR[$checkbox]['receive']) ? $VAR[$checkbox]['receive'] : '0';
    $VAR[$checkbox]['enabled']  = isset($VAR[$checkbox]['enabled']) ? $VAR[$checkbox]['enabled'] : '0';     
}

// If changes submited
if(isset($VAR['submit'])) {
    
    // Update enabled payment methods (checkboxes)
    update_payment_methods_statuses($VAR['payment_methods']);
    
    // Update Payment details
    update_payment_options($VAR);

    // Assign success message    
    $smarty->assign('information_msg', _gettext("Payment Options Updated.") );
    
    // Log activity 
    write_record_to_activity_log(_gettext("Payment Options Updated."));
    
}

// Build the page
$smarty->assign('payment_methods',          get_payment_methods() );
$smarty->assign('payment_options',          get_payment_options() );
$BuildPage .= $smarty->fetch('payment/options.tpl');
