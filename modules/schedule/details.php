<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/schedule.php');
require(INCLUDES_DIR.'modules/user.php');

// Build the page
$smarty->assign('schedule_details', get_schedule_details($db, $schedule_id));
$smarty->assign('employee_display_name', get_user_details($db, get_schedule_details($db, $schedule_id, 'employee_id'), 'display_name')  );
$BuildPage .= $smarty->fetch('schedule/details.tpl');