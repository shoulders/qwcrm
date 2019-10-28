<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require_once(CINCLUDES_DIR.'client.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    force_page('client', 'search');
}

// Run the delete function and return the results
if(!delete_client(\CMSApplication::$VAR['client_id'])) {
    
    // Reload client details page with error messages
    systemMessagesWrite('danger', _gettext("This client cannot be deleted."));
    force_page('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);
    
} else {
    
    // Load the Client search page
    systemMessagesWrite('success', _gettext("The client has been deleted."));
    force_page('client', 'search');
    
}