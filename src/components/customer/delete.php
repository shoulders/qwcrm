<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a customer_id
if(!isset($VAR['customer_id']) || !$VAR['customer_id']) {
    force_page('customer', 'search', 'warning_msg='._gettext("No Customer ID supplied."));
}

// Run the delete function and return the results
if(!delete_customer($VAR['customer_id'])) {
    
    // Reload customer details apge with error message
    force_page('customer', 'details&customer_id='.$VAR['customer_id']);
    
} else {
    
    // Load the Customer search page
    force_page('customer', 'search');
    
}