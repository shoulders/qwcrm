<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/refund.php');
require(INCLUDES_DIR.'modules/payment.php');

// Predict the next refund_id
$new_record_id = last_refund_id_lookup($db) +1;

// If details submitted insert record, if non submitted load new.tpl and populate values
if((isset($VAR['submit'])) || (isset($VAR['submitandnew']))) {

    // insert the refund and get the refund_id
    $refund_id = insert_refund($db, $VAR);
        
    if (isset($VAR['submitandnew'])){

        // Load New Refund page
        force_page('refund', 'new', 'information_msg='._gettext("Refund added successfully.")); 
        exit;

    } else {

        // Load Refund Details page
        force_page('refund', 'details', 'refund_id='.$refund_id.'&information_msg='._gettext("Refund added successfully."));        
        exit;

    }
         
}

// Build the page
$smarty->assign('refund_types', get_refund_types($db));
$smarty->assign('payment_methods', get_payment_manual_methods($db));
$smarty->assign('new_record_id', $new_record_id);
$smarty->assign('tax_rate', get_company_details($db, 'tax_rate'));
$BuildPage .= $smarty->fetch('refund/new.tpl');