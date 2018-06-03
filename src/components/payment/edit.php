<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/payment.php');

// Check if we have an expense_id
if($VAR['payment_id'] == '') {
    force_page('payment', 'search', 'warning_msg='._gettext("No Payment ID supplied."));
    exit;
}

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
        update_transaction($db, $VAR);        
        force_page('payment', 'details', 'payment_id='.$VAR['payment_id'].'&information_msg='._gettext("Payment updated successfully.")); 
        exit;    

} else {
    
    // Build the page    
    $smarty->assign('payment_methods', get_payment_manual_methods($db));
    $smarty->assign('transaction_details', get_transaction_details($db, $VAR['payment_id']));
    $BuildPage .= $smarty->fetch('expense/edit.tpl');
    
}