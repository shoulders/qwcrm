<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'supplier.php');

// Check if we have a supplier_id
if(!isset(\CMSApplication::$VAR['supplier_id']) || !\CMSApplication::$VAR['supplier_id']) {
    systemMessagesWrite('danger', _gettext("No Supplier ID supplied."));
    force_page('supplier', 'search');
}  

// Build the page
$smarty->assign('supplier_statuses',   get_supplier_statuses()   );
$smarty->assign('supplier_types', get_supplier_types());
$smarty->assign('supplier_details', get_supplier_details(\CMSApplication::$VAR['supplier_id']));