<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'otherincome.php');
require(CINCLUDES_DIR.'report.php');

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    force_page('otherincome', 'search');
}

// Update Voucher Status
if(isset(\CMSApplication::$VAR['change_status'])){
    update_otherincome_status(\CMSApplication::$VAR['otherincome_id'], \CMSApplication::$VAR['assign_status']);    
    force_page('otherincome', 'status&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     false       );
$smarty->assign('otherincome_status',              get_otherincome_details(\CMSApplication::$VAR['otherincome_id'], 'status')             );
$smarty->assign('otherincome_statuses',            get_otherincome_statuses() );
$smarty->assign('allowed_to_cancel',            check_otherincome_can_be_cancelled(\CMSApplication::$VAR['otherincome_id'])    );
$smarty->assign('allowed_to_delete',            check_otherincome_can_be_deleted(\CMSApplication::$VAR['otherincome_id'])              );
$smarty->assign('otherincome_selectable_statuses',     get_otherincome_statuses(true));