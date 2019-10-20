<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a client_id
if(!isset(\QFactory::$VAR['client_id']) || !\QFactory::$VAR['client_id']) {
    force_page('client', 'search', 'warning_msg='._gettext("No Client ID supplied."));
}

// Run the delete function and return the results
if(!delete_client(\QFactory::$VAR['client_id'])) {
    
    // Reload client details apge with error message
    force_page('client', 'details&client_id='.\QFactory::$VAR['client_id'], 'warning_msg='._gettext("This client cannot be deleted."));
    
} else {
    
    // Load the Client search page
    force_page('client', 'search');
    
}