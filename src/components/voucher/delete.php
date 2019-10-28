<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('voucher', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    force_page('voucher', 'search');
}

// Get invoice_id before deleting
$invoice_id = get_voucher_details(\CMSApplication::$VAR['voucher_id'], 'invoice_id');

// Delete the Voucher - The Voucher is effectively only deactivated
if(!delete_voucher(\CMSApplication::$VAR['voucher_id'])) {
    
    // Load the relevant invoice page with fail message
    systemMessagesWrite('danger', _gettext("Voucher failed to be deleted."));
    force_page('invoice', 'details&invoice_id='.$invoice_id);
    
} else {
    
    // Load the relevant invoice page with success message
    systemMessagesWrite('success', _gettext("Voucher deleted successfully."));
    force_page('invoice', 'details&invoice_id='.$invoice_id);

}