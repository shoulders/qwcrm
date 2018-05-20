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

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice labour_id
if($VAR['labour_id'] == '') {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice Labour ID supplied."));
    exit;
}

// Get invoice ID before deletion
$VAR['invoice_id'] = get_invoice_labour_item_details($db, $VAR['labour_id'], 'invoice_id');

// Delete Invoice Labour item
delete_invoice_labour_item($db, $VAR['labour_id']);

// recalculate the invoice totals and update them
recalculate_invoice_totals($db, $VAR['invoice_id']);

// load the edit invoice page
force_page('invoice' , 'edit&invoice_id='.$VAR['invoice_id']);
exit;
