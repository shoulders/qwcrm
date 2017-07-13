<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/schedule.php');

$smarty->assign('schedule_details', get_schedule_details($db, $schedule_id));

$BuildPage .= $smarty->fetch('schedule/details.tpl');