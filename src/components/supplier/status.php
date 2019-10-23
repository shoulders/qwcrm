<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'supplier.php');

// Check if we have a supplier_id
if(!isset(\QFactory::$VAR['supplier_id']) || !\QFactory::$VAR['supplier_id']) {
    force_page('supplier', 'search', 'msg_danger='._gettext("No Supplier ID supplied."));
}

// Update Supplier Status
if(isset(\QFactory::$VAR['change_status'])){
    update_supplier_status(\QFactory::$VAR['supplier_id'], \QFactory::$VAR['assign_status']);    
    force_page('supplier', 'status&supplier_id='.\QFactory::$VAR['supplier_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     false       );
$smarty->assign('supplier_status',              get_supplier_details(\QFactory::$VAR['supplier_id'], 'status')             );
$smarty->assign('supplier_statuses',            get_supplier_statuses() );
$smarty->assign('allowed_to_cancel',            false      );
$smarty->assign('allowed_to_delete',            check_supplier_can_be_deleted(\QFactory::$VAR['supplier_id'])              );
$smarty->assign('supplier_selectable_statuses',     get_supplier_statuses(true) );