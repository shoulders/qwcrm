<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'customer.php');
require(INCLUDES_DIR.'user.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an user_id
if(!isset($VAR['user_id']) || !$VAR['user_id']) {
    force_page('user', 'search', 'warning_msg='._gettext("No User ID supplied."));
}

// Run the delete function
if(!delete_user($VAR['user_id'])) {
    
    // load the user details page
    force_page('user', 'details&user_id='.$VAR['user_id']);    
    
} else {
    
    // load the user search page
    force_page('user', 'search');
    
}