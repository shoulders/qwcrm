<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'user.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an user_id
if(!isset(\QFactory::$VAR['user_id']) || !\QFactory::$VAR['user_id']) {
    force_page('user', 'search', 'msg_danger='._gettext("No User ID supplied."));
}

// Run the delete function
if(!delete_user(\QFactory::$VAR['user_id'])) {
    
    // load the user details page
    force_page('user', 'details&user_id='.\QFactory::$VAR['user_id']);    
    
} else {
    
    // load the user search page
    force_page('user', 'search', 'msg_success='._gettext("User record deleted."));   
    
}