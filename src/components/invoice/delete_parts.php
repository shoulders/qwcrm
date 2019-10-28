<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an invoice parts_id
if(!isset(\CMSApplication::$VAR['parts_id']) || !\CMSApplication::$VAR['parts_id']) {
    systemMessagesWrite('danger', _gettext("No Invoice Parts ID supplied."));
    force_page('invoice', 'search');
}

// Get Invoice ID before deletion
\CMSApplication::$VAR['invoice_id'] = get_invoice_parts_item_details(\CMSApplication::$VAR['parts_id'], 'invoice_id');

// Delete Invoice Labour item
delete_invoice_parts_item(\CMSApplication::$VAR['parts_id']);

// Load the edit invoice page
force_page('invoice' , 'edit&invoice_id='.\CMSApplication::$VAR['invoice_id']);