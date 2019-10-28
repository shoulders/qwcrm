<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a payment_id
if(!isset(\CMSApplication::$VAR['payment_id']) || !\CMSApplication::$VAR['payment_id']) {
    systemMessagesWrite('danger', _gettext("No Payment ID supplied."));
    force_page('payment', 'search');
}

// Update Payment Status
if(isset(\CMSApplication::$VAR['change_status'])){
    update_payment_status(\CMSApplication::$VAR['payment_id'], \CMSApplication::$VAR['assign_status']);    
    force_page('payment', 'status&payment_id='.\CMSApplication::$VAR['payment_id']);
}

// Build the page with the current status from the database
$smarty->assign('allowed_to_change_status',        check_payment_status_can_be_changed(\CMSApplication::$VAR['payment_id'])      );
$smarty->assign('payment_status',                  get_payment_details(\CMSApplication::$VAR['payment_id'], 'status')             );
$smarty->assign('payment_statuses',                get_payment_statuses() );
$smarty->assign('allowed_to_cancel',               check_payment_can_be_cancelled(\CMSApplication::$VAR['payment_id'])   );
$smarty->assign('allowed_to_delete',               check_payment_can_be_deleted(\CMSApplication::$VAR['payment_id'])              );
$smarty->assign('payment_selectable_statuses',     get_payment_statuses(true) );