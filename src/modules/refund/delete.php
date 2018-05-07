<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/refund.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm()) {
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a refund_id
if($refund_id == '') {
    force_page('refund', 'search', 'warning_msg='._gettext("No Refund ID supplied."));
    exit;
} 

// Delete the refund function call
delete_refund($db, $refund_id);

// Load the refund search page
force_page('refund', 'search', 'information_msg='._gettext("Refund deleted successfully."));
exit;
