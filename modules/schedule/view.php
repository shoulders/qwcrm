<?php

require(INCLUDES_DIR.'modules/schedule.php');

$smarty->assign('single_schedule', get_schedule_details($db, $schedule_id));

$BuildPage .= $smarty->fetch('schedule/view.tpl');