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
require(INCLUDES_DIR.'components/payment.php');
require(INCLUDES_DIR.'components/workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice parts_id
if(!isset($VAR['parts_id']) || !$VAR['parts_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice Parts ID supplied."));
}

// Get Invoice ID before deletion
$VAR['invoice_id'] = get_invoice_parts_item_details($VAR['parts_id'], 'invoice_id');

// Delete Invoice Labour item
delete_invoice_parts_item($VAR['parts_id']);

// Load the edit invoice page
force_page('invoice' , 'edit&invoice_id='.$VAR['invoice_id']);
exit;