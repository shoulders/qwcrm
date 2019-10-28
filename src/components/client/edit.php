<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    force_page('client', 'search');
}

if(isset(\CMSApplication::$VAR['submit'])) {    
        
    // Update the Client's Details
    update_client(\CMSApplication::$VAR['qform']);
    
    // Load the client's details page
    systemMessagesWrite('success', _gettext("The Client's information was updated."));
    force_page('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);

} else {    

    // Build the page
    $smarty->assign('client_types',   get_client_types());
    $smarty->assign('client_details', get_client_details(\CMSApplication::$VAR['client_id']));
    
}