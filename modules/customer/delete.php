<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(gettext("No Direct Access Allowed."));
}

// Check if we have a customer_id
if($customer_id == '') {
    force_page('customer', 'search', 'warning_msg='.gettext("No Customer ID supplied."));
    exit;
}

// Run the delete function and return the results
if(!delete_customer($db, $customer_id)) {
    
    // Reload customer details apge with error message
    force_page('customer', 'details&customer_id='.$customer_id);
    exit;
    
} else {
    
    // Load the Customer search page
    force_page('customer', 'search');
    exit;
    
}