<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');

// Check if we have a customer_id
if(!isset($VAR['customer_id']) || !$VAR['customer_id']) {
    force_page('customer', 'search', 'warning_msg='._gettext("No Customer ID supplied."));
}

if(isset($VAR['submit'])) {    
        
    // Update the Customer's Details
    update_customer($VAR);
    
    // Load the customer's details page
    force_page('customer', 'details&customer_id='.$VAR['customer_id'], 'information_msg='._gettext("The Customer's information was updated."));

} else {    

    // Build the page
    $smarty->assign('customer_types',   get_customer_types());
    $smarty->assign('customer_details', get_customer_details($VAR['customer_id']));
    $BuildPage .= $smarty->fetch('customer/edit.tpl');
    
}