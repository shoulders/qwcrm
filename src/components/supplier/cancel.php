<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'supplier.php');

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm('supplier', 'status')) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Check if we have a supplier_id
if(!isset(\QFactory::$VAR['supplier_id']) || !\QFactory::$VAR['supplier_id']) {
    force_page('supplier', 'search', 'warning_msg='._gettext("No Supplier ID supplied."));
}  

// Cancel the supplier function call
cancel_supplier(\QFactory::$VAR['supplier_id']);

// Load the supplier search page
force_page('supplier', 'search', 'information_msg='._gettext("Supplier cancelled successfully."));
