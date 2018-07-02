<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'customer.php');
require(INCLUDES_DIR.'giftcert.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have an giftcert_id
if(!isset($VAR['giftcert_id']) || !$VAR['giftcert_id']) {
    force_page('giftcert', 'search', 'warning_msg='._gettext("No Gift Certificate ID supplied."));
}

// Delete the Gift Certificate - the giftcert is only deactivated
delete_giftcert($VAR['giftcert_id']);
    
// Reload the customers details page
force_page('giftcert', 'search', 'information_msg='._gettext("Gift Certificate deleted(blocked) successfully."));
exit;