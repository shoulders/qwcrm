<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'user.php');
require(CINCLUDES_DIR.'workorder.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('workorder', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    force_page('workorder', 'search');
}

// Delete the Workorder
if(!delete_workorder(\CMSApplication::$VAR['workorder_id'])) {
    
    // load the staus page
    force_page('workorder', 'status', 'workorder_id='.\CMSApplication::$VAR['workorder_id']);
    
} else {
    
    
    // load the workorder search page
    systemMessagesWrite('success', _gettext("Work Order has been deleted."));
    force_page('workorder', 'search');
    
}
    