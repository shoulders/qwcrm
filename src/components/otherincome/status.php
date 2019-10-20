<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'otherincome.php');
require(INCLUDES_DIR.'report.php');

// Check if we have a otherincome_id
if(!isset($VAR['otherincome_id']) || !$VAR['otherincome_id']) {
    force_page('otherincome', 'search', 'warning_msg='._gettext("No Voucher ID supplied."));
}

// Update Voucher Status
if(isset($VAR['change_status'])){
    update_otherincome_status($VAR['otherincome_id'], $VAR['assign_status']);    
    force_page('otherincome', 'status&otherincome_id='.$VAR['otherincome_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',     false       );
$smarty->assign('otherincome_status',              get_otherincome_details($VAR['otherincome_id'], 'status')             );
$smarty->assign('otherincome_statuses',            get_otherincome_statuses() );
$smarty->assign('allowed_to_cancel',            check_otherincome_can_be_cancelled($VAR['otherincome_id'])    );
$smarty->assign('allowed_to_delete',            check_otherincome_can_be_deleted($VAR['otherincome_id'])              );
$smarty->assign('otherincome_selectable_statuses',     get_otherincome_statuses(true));

\QFactory::$BuildPage .= $smarty->fetch('otherincome/status.tpl');