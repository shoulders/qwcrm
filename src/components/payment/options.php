<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/payment.php');

// If changes submited
if(isset($VAR['submit'])) {
    
    // Update enabled payment methods (checkboxes)
    update_active_payment_accepted_methods($VAR);
    
    // Update Payment details
    update_payment_options($VAR);

    // Assign success message    
    $smarty->assign('information_msg', _gettext("Payment Options Updated.") );
    
    // Log activity 
    write_record_to_activity_log(_gettext("Payment Options Updated."));
    
}

// Build the page
$smarty->assign('payment_accepted_methods',   get_payment_accepted_methods() );
$smarty->assign('payment_options',          get_payment_options()        );
$BuildPage .= $smarty->fetch('payment/options.tpl');
