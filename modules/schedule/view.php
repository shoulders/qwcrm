<?php

require(INCLUDES_DIR.'modules/schedule.php');

$smarty->assign('single_schedule', display_single_schedule($db, $schedule_id));

$BuildPage .= $smarty->fetch('schedule/view.tpl');