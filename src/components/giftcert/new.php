<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/giftcert.php');
require(INCLUDES_DIR.'components/payment.php');

// Check if we have a customer_id
if($VAR['customer_id'] == '') {
    force_page('customer', 'search', 'warning_msg='._gettext("No Customer ID supplied."));
}

// Check if giftcert payment method is enabled
if(!check_payment_method_is_active($db, 'gift_certificate')) {
    force_page('index.php', null, 'warning_msg='._gettext("Gift Certificate payment method is not enabled. Goto Payment Options and enable Gift Certificates there."));
}

// if information submitted - add new gift certificate
if(isset($VAR['submit'])) {   
        
    // Create a new gift certificate
    $VAR['giftcert_id'] = insert_giftcert($db, $VAR['customer_id'], date_to_timestamp($VAR['date_expires']), $VAR['amount'], $VAR['status'], $VAR['note']);

    // Load the new Gift Certificate's Details page
    force_page('giftcert', 'details&giftcert_id='.$VAR['giftcert_id']);

} else {
    
    // Build the page
    $smarty->assign('customer_details', get_customer_details($db, $VAR['customer_id']));
    $BuildPage .= $smarty->fetch('giftcert/new.tpl');
}