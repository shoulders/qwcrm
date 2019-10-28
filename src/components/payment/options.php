<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'payment.php');

/* Prevent undefined variable errors (does the same as below)
 * 
\CMSApplication::$VAR['bank_transfer']['send'] = isset(\CMSApplication::$VAR['bank_transfer']['send']) ? \CMSApplication::$VAR['bank_transfer']['send'] : null;
\CMSApplication::$VAR['bank_transfer']['receive'] = isset(\CMSApplication::$VAR['bank_transfer']['receive']) ? \CMSApplication::$VAR['bank_transfer']['receive'] : null;
\CMSApplication::$VAR['bank_transfer']['active'] = isset(\CMSApplication::$VAR['bank_transfer']['active']) ? \CMSApplication::$VAR['bank_transfer']['active'] : null;
\CMSApplication::$VAR['card']['send'] = isset(\CMSApplication::$VAR['card']['send']) ? \CMSApplication::$VAR['card']['send'] : null;
\CMSApplication::$VAR['card']['receive'] = isset(\CMSApplication::$VAR['card']['receive']) ? \CMSApplication::$VAR['card']['receive'] : null;
\CMSApplication::$VAR['card']['active'] = isset(\CMSApplication::$VAR['card']['active']) ? \CMSApplication::$VAR['card']['active'] : null;
\CMSApplication::$VAR['cash']['send'] = isset(\CMSApplication::$VAR['cash']['send']) ? \CMSApplication::$VAR['cash']['send'] : null;
\CMSApplication::$VAR['cash']['receive'] = isset(\CMSApplication::$VAR['cash']['receive']) ? \CMSApplication::$VAR['cash']['receive'] : null;
\CMSApplication::$VAR['cash']['active'] = isset(\CMSApplication::$VAR['cash']['active']) ? \CMSApplication::$VAR['cash']['active'] : null;
\CMSApplication::$VAR['cheque']['send'] = isset(\CMSApplication::$VAR['cheque']['send']) ? \CMSApplication::$VAR['cheque']['send'] : null;
\CMSApplication::$VAR['cheque']['receive'] = isset(\CMSApplication::$VAR['cheque']['receive']) ? \CMSApplication::$VAR['cheque']['receive'] : null;
\CMSApplication::$VAR['cheque']['active'] = isset(\CMSApplication::$VAR['cheque']['active']) ? \CMSApplication::$VAR['cheque']['active'] : null;
\CMSApplication::$VAR['direct_debit']['send'] = isset(\CMSApplication::$VAR['direct_debit']['send']) ? \CMSApplication::$VAR['direct_debit']['send'] : null;
\CMSApplication::$VAR['direct_debit']['receive'] = isset(\CMSApplication::$VAR['direct_debit']['receive']) ? \CMSApplication::$VAR['direct_debit']['receive'] : null;
\CMSApplication::$VAR['direct_debit']['active'] = isset(\CMSApplication::$VAR['direct_debit']['active']) ? \CMSApplication::$VAR['direct_debit']['active'] : null;
\CMSApplication::$VAR['voucher']['send'] = isset(\CMSApplication::$VAR['voucher']['send']) ? \CMSApplication::$VAR['voucher']['send'] : null;
\CMSApplication::$VAR['voucher']['receive'] = isset(\CMSApplication::$VAR['voucher']['receive']) ? \CMSApplication::$VAR['voucher']['receive'] : null;
\CMSApplication::$VAR['voucher']['active'] = isset(\CMSApplication::$VAR['voucher']['active']) ? \CMSApplication::$VAR['voucher']['active'] : null;
\CMSApplication::$VAR['other']['send'] = isset(\CMSApplication::$VAR['other']['send']) ? \CMSApplication::$VAR['other']['send'] : null;
\CMSApplication::$VAR['other']['receive'] = isset(\CMSApplication::$VAR['other']['receive']) ? \CMSApplication::$VAR['other']['receive'] : null;
\CMSApplication::$VAR['other']['active'] = isset(\CMSApplication::$VAR['other']['active']) ? \CMSApplication::$VAR['other']['active'] : null;
\CMSApplication::$VAR['paypal']['send'] = isset(\CMSApplication::$VAR['paypal']['send']) ? \CMSApplication::$VAR['paypal']['send'] : null;
\CMSApplication::$VAR['paypal']['receive'] = isset(\CMSApplication::$VAR['paypal']['receive']) ? \CMSApplication::$VAR['paypal']['receive'] : null;
\CMSApplication::$VAR['paypal']['active'] = isset(\CMSApplication::$VAR['paypal']['active']) ? \CMSApplication::$VAR['paypal']['active'] : null;
*/

// Prevent undefined variable errors (from checkboxes)
$checkboxes = array('bank_transfer', 'card', 'cash', 'cheque', 'direct_debit', 'voucher', 'other', 'paypal');
foreach($checkboxes as $checkbox) {     
    \CMSApplication::$VAR['qform'][$checkbox]['send']     = isset(\CMSApplication::$VAR['qform'][$checkbox]['send'])    ? \CMSApplication::$VAR['qform'][$checkbox]['send']    : '0';
    \CMSApplication::$VAR['qform'][$checkbox]['receive']  = isset(\CMSApplication::$VAR['qform'][$checkbox]['receive']) ? \CMSApplication::$VAR['qform'][$checkbox]['receive'] : '0';
    \CMSApplication::$VAR['qform'][$checkbox]['enabled']  = isset(\CMSApplication::$VAR['qform'][$checkbox]['enabled']) ? \CMSApplication::$VAR['qform'][$checkbox]['enabled'] : '0';     
}

// If changes submited
if(isset(\CMSApplication::$VAR['submit'])) {
    
    // Update enabled payment methods (checkboxes)
    update_payment_methods_statuses(\CMSApplication::$VAR['qform']['payment_methods']);
    
    // Update Payment details
    update_payment_options(\CMSApplication::$VAR['qform']);

    // Assign success message    
    systemMessagesWrite('success', _gettext("Payment Options Updated.") );
    
    // Log activity 
    write_record_to_activity_log(_gettext("Payment Options Updated."));
    
}

// Build the page
$smarty->assign('payment_methods',          get_payment_methods() );
$smarty->assign('payment_options',          get_payment_options() );
