<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/refund.php');
require(INCLUDES_DIR.'components/payment.php');

// Check if we have a refund_id
if($VAR['refund_id'] == '') {
    force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied."));
} 

// If details submitted run update values, if not set load edit.tpl and populate values
if(isset($VAR['submit'])) {    
        
    // Update the refund in the database
    update_refund($db, $VAR['refund_id'], $VAR);
    
    // load details page
    force_page('refund', 'details&refund_id='.$VAR['refund_id'], 'information_msg='._gettext("Refund updated successfully.")); 
}   

// Build the page
$smarty->assign('refund_types', get_refund_types($db));
$smarty->assign('payment_methods', get_payment_purchase_methods($db));
$smarty->assign('refund_details', get_refund_details($db, $VAR['refund_id']));
$BuildPage .= $smarty->fetch('refund/edit.tpl');
