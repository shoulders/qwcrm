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
 * 
\QFactory::$VAR['bank_transfer']['send'] = isset(\QFactory::$VAR['bank_transfer']['send']) ? \QFactory::$VAR['bank_transfer']['send'] : null;
\QFactory::$VAR['bank_transfer']['receive'] = isset(\QFactory::$VAR['bank_transfer']['receive']) ? \QFactory::$VAR['bank_transfer']['receive'] : null;
\QFactory::$VAR['bank_transfer']['active'] = isset(\QFactory::$VAR['bank_transfer']['active']) ? \QFactory::$VAR['bank_transfer']['active'] : null;
\QFactory::$VAR['card']['send'] = isset(\QFactory::$VAR['card']['send']) ? \QFactory::$VAR['card']['send'] : null;
\QFactory::$VAR['card']['receive'] = isset(\QFactory::$VAR['card']['receive']) ? \QFactory::$VAR['card']['receive'] : null;
\QFactory::$VAR['card']['active'] = isset(\QFactory::$VAR['card']['active']) ? \QFactory::$VAR['card']['active'] : null;
\QFactory::$VAR['cash']['send'] = isset(\QFactory::$VAR['cash']['send']) ? \QFactory::$VAR['cash']['send'] : null;
\QFactory::$VAR['cash']['receive'] = isset(\QFactory::$VAR['cash']['receive']) ? \QFactory::$VAR['cash']['receive'] : null;
\QFactory::$VAR['cash']['active'] = isset(\QFactory::$VAR['cash']['active']) ? \QFactory::$VAR['cash']['active'] : null;
\QFactory::$VAR['cheque']['send'] = isset(\QFactory::$VAR['cheque']['send']) ? \QFactory::$VAR['cheque']['send'] : null;
\QFactory::$VAR['cheque']['receive'] = isset(\QFactory::$VAR['cheque']['receive']) ? \QFactory::$VAR['cheque']['receive'] : null;
\QFactory::$VAR['cheque']['active'] = isset(\QFactory::$VAR['cheque']['active']) ? \QFactory::$VAR['cheque']['active'] : null;
\QFactory::$VAR['direct_debit']['send'] = isset(\QFactory::$VAR['direct_debit']['send']) ? \QFactory::$VAR['direct_debit']['send'] : null;
\QFactory::$VAR['direct_debit']['receive'] = isset(\QFactory::$VAR['direct_debit']['receive']) ? \QFactory::$VAR['direct_debit']['receive'] : null;
\QFactory::$VAR['direct_debit']['active'] = isset(\QFactory::$VAR['direct_debit']['active']) ? \QFactory::$VAR['direct_debit']['active'] : null;
\QFactory::$VAR['voucher']['send'] = isset(\QFactory::$VAR['voucher']['send']) ? \QFactory::$VAR['voucher']['send'] : null;
\QFactory::$VAR['voucher']['receive'] = isset(\QFactory::$VAR['voucher']['receive']) ? \QFactory::$VAR['voucher']['receive'] : null;
\QFactory::$VAR['voucher']['active'] = isset(\QFactory::$VAR['voucher']['active']) ? \QFactory::$VAR['voucher']['active'] : null;
\QFactory::$VAR['other']['send'] = isset(\QFactory::$VAR['other']['send']) ? \QFactory::$VAR['other']['send'] : null;
\QFactory::$VAR['other']['receive'] = isset(\QFactory::$VAR['other']['receive']) ? \QFactory::$VAR['other']['receive'] : null;
\QFactory::$VAR['other']['active'] = isset(\QFactory::$VAR['other']['active']) ? \QFactory::$VAR['other']['active'] : null;
\QFactory::$VAR['paypal']['send'] = isset(\QFactory::$VAR['paypal']['send']) ? \QFactory::$VAR['paypal']['send'] : null;
\QFactory::$VAR['paypal']['receive'] = isset(\QFactory::$VAR['paypal']['receive']) ? \QFactory::$VAR['paypal']['receive'] : null;
\QFactory::$VAR['paypal']['active'] = isset(\QFactory::$VAR['paypal']['active']) ? \QFactory::$VAR['paypal']['active'] : null;
*/

// Prevent undefined variable errors (from checkboxes)
$checkboxes = array('bank_transfer', 'card', 'cash', 'cheque', 'direct_debit', 'voucher', 'other', 'paypal');
foreach($checkboxes as $checkbox) {     
    \QFactory::$VAR['qform'][$checkbox]['send']     = isset(\QFactory::$VAR['qform'][$checkbox]['send'])    ? \QFactory::$VAR['qform'][$checkbox]['send']    : '0';
    \QFactory::$VAR['qform'][$checkbox]['receive']  = isset(\QFactory::$VAR['qform'][$checkbox]['receive']) ? \QFactory::$VAR['qform'][$checkbox]['receive'] : '0';
    \QFactory::$VAR['qform'][$checkbox]['enabled']  = isset(\QFactory::$VAR['qform'][$checkbox]['enabled']) ? \QFactory::$VAR['qform'][$checkbox]['enabled'] : '0';     
}

// If changes submited
if(isset(\QFactory::$VAR['submit'])) {
    
    // Update enabled payment methods (checkboxes)
    update_payment_methods_statuses(\QFactory::$VAR['qform']['payment_methods']);
    
    // Update Payment details
    update_payment_options(\QFactory::$VAR['qform']);

    // Assign success message    
    $smarty->assign('information_msg', _gettext("Payment Options Updated.") );
    
    // Log activity 
    write_record_to_activity_log(_gettext("Payment Options Updated."));
    
}

// Build the page
$smarty->assign('payment_methods',          get_payment_methods() );
$smarty->assign('payment_options',          get_payment_options() );
