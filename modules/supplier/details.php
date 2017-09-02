<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/supplier.php');

// Check if we have a supplier_id
if($supplier_id == '') {
    force_page('supplier', 'search', 'warning_msg='.gettext("No Supplier ID supplied."));
    exit;
}  

// Build the page
$smarty->assign('supplier_types', get_supplier_types($db));
$smarty->assign('supplier_details', get_supplier_details($db, $supplier_id));
$BuildPage .= $smarty->fetch('supplier/details.tpl');