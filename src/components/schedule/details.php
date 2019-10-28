<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Build the page
$smarty->assign('client_details', get_client_details(get_schedule_details(\CMSApplication::$VAR['schedule_id'], 'client_id')));
$smarty->assign('schedule_details', get_schedule_details(\CMSApplication::$VAR['schedule_id']));
$smarty->assign('employee_display_name', get_user_details(get_schedule_details(\CMSApplication::$VAR['schedule_id'], 'employee_id'), 'display_name')  );