<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/invoice.php');
require(INCLUDES_DIR.'components/workorder.php');

// Create an invoice for the supplied workorder
if($workorder_id && !get_workorder_details($db, $workorder_id, 'invoice_id')) {

    // Get Customer_id from the workorder    
    $customer_id = get_workorder_details($db, $workorder_id, 'customer_id');
    
    // Create the invoice and return the new invoice_id
    $invoice_id = insert_invoice($db, $customer_id, $workorder_id, get_customer_details($db, $customer_id, 'discount_rate'));
    
    // Update the workorder with the new invoice_id
    update_workorder_invoice_id($db, $workorder_id, $invoice_id);

    // Load the newly created invoice edit page
    force_page('invoice', 'edit&invoice_id='.$invoice_id);
    exit;
    
} 

// Invoice only
if(($customer_id != '' && $VAR['invoice_type'] == 'invoice-only')) {
    
    // Create the invoice and return the new invoice_id
    $invoice_id = insert_invoice($db, $customer_id, '', get_customer_details($db, $customer_id, 'discount_rate'));

    // Load the newly created invoice edit page
    force_page('invoice', 'edit&invoice_id='.$invoice_id);
    exit;    
}    
  
// Fallback Error Control 
force_page('workorder', 'search', 'warning_msg='._gettext("You cannot create an invoice by the method you just tried, report to admins."));
exit;
