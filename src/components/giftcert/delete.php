<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'giftcert.php');
require(INCLUDES_DIR.'invoice.php');
require(INCLUDES_DIR.'payment.php');
require(INCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an giftcert_id
if(!isset($VAR['giftcert_id']) || !$VAR['giftcert_id']) {
    force_page('giftcert', 'search', 'warning_msg='._gettext("No Gift Certificate ID supplied."));
}

// Get invoice_id before deleting
$invoice_id = get_giftcert_details($VAR['giftcert_id'], 'invoice_id');

// Delete the Gift Certificate - the giftcert is only deactivated
if(!delete_giftcert($VAR['giftcert_id'])) {
    
    // Load the relevant invoice page with failed message
    force_page('invoice', 'details&invoice_id='.$invoice_id, 'warning_msg='._gettext("Gift Certificate failed to be deleted."));
    
} else {
    
    // Load the relevant invoice page with success message
    force_page('invoice', 'details&invoice_id='.$invoice_id, 'information_msg='._gettext("Gift Certificate deleted successfully."));

}