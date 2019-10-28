<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a voucher_id
if(!isset(\CMSApplication::$VAR['voucher_id']) || !\CMSApplication::$VAR['voucher_id']) {
    systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    force_page('voucher', 'search');
}

// Update Voucher Status
if(isset(\CMSApplication::$VAR['change_status'])){
    update_voucher_status(\CMSApplication::$VAR['voucher_id'], \CMSApplication::$VAR['assign_status']);    
    force_page('voucher', 'status&voucher_id='.\CMSApplication::$VAR['voucher_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     check_voucher_status_can_be_changed(\CMSApplication::$VAR['voucher_id'])       );
$smarty->assign('voucher_status',              get_voucher_details(\CMSApplication::$VAR['voucher_id'], 'status')             );
$smarty->assign('voucher_statuses',            get_voucher_statuses() );
$smarty->assign('allowed_to_delete',            check_voucher_can_be_deleted(\CMSApplication::$VAR['voucher_id'])              );
$smarty->assign('voucher_selectable_statuses',     get_voucher_statuses(true) );