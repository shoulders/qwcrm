<?php

require(INCLUDES_DIR.'modules/workorder.php');
require(INCLUDES_DIR.'modules/schedule.php');

// Display the page with the workorder details from the database 
$smarty->assign('single_workorder',         display_single_open_workorder($db, $workorder_id)  );
$smarty->assign('workorder_notes',          display_workorder_notes($db, $workorder_id)        );
$smarty->assign('workorder_parts',          display_parts($db, $workorder_id)                  );             
$smarty->assign('workorder_history',        display_workorder_history($db, $workorder_id)      );
$smarty->assign('workorder_schedule',       display_workorder_schedule($db, $workorder_id)     );    
$smarty->assign('workorder_resolution',     display_resolution($db, $workorder_id)             );

$smarty->display('workorder'.SEP.'details.tpl');