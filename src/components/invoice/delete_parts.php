<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'report.php');
require(INCLUDES_DIR.'voucher.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice parts_id
if(!isset(\QFactory::$VAR['parts_id']) || !\QFactory::$VAR['parts_id']) {
    force_page('invoice', 'search', 'warning_msg='._gettext("No Invoice Parts ID supplied."));
}

// Get Invoice ID before deletion
\QFactory::$VAR['invoice_id'] = get_invoice_parts_item_details(\QFactory::$VAR['parts_id'], 'invoice_id');

// Delete Invoice Labour item
delete_invoice_parts_item(\QFactory::$VAR['parts_id']);

// Load the edit invoice page
force_page('invoice' , 'edit&invoice_id='.\QFactory::$VAR['invoice_id']);
exit;