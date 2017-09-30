<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');
require(INCLUDES_DIR.'modules/invoice.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice parts_id
if($VAR['parts_id'] == '') {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice Parts ID supplied."));
    exit;
}

// Get Invoice ID before deletion
$invoice_id = get_invoice_parts_item_details($db, $VAR['parts_id'], 'invoice_id');

// Delete Invoice Labour item
delete_invoice_parts_item($db, $VAR['parts_id']);

// recalculate the invoice totals and update them
recalculate_invoice_totals($db, $invoice_id);

// load the edit invoice page
force_page('invoice' , 'edit&invoice_id='.$invoice_id);
exit;