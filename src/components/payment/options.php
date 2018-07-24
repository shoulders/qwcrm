<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'payment.php');

// Prevent undefined variable errors
$VAR['credit_card'] = isset($VAR['credit_card']) ? $VAR['credit_card'] : null;
$VAR['cheque'] = isset($VAR['cheque']) ? $VAR['cheque'] : null;
$VAR['cash'] = isset($VAR['cash']) ? $VAR['cash'] : null;
$VAR['gift_certificate'] = isset($VAR['gift_certificate']) ? $VAR['gift_certificate'] : null;
$VAR['paypal'] = isset($VAR['paypal']) ? $VAR['paypal'] : null;
$VAR['direct_deposit'] = isset($VAR['direct_deposit']) ? $VAR['direct_deposit'] : null;

// If changes submited
if(isset($VAR['submit'])) {
    
    // Update enabled payment methods (checkboxes)
    update_payment_accepted_methods_statuses($VAR);
    
    // Update Payment details
    update_payment_options($VAR);

    // Assign success message    
    $smarty->assign('information_msg', _gettext("Payment Options Updated.") );
    
    // Log activity 
    write_record_to_activity_log(_gettext("Payment Options Updated."));
    
}

// Build the page
$smarty->assign('payment_accepted_methods', get_payment_accepted_methods() );
$smarty->assign('payment_options',          get_payment_options()        );
$BuildPage .= $smarty->fetch('payment/options.tpl');
