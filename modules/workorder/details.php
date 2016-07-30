<?php

require_once('include.php');

/* Grab passed varibles */
$wo_id  = $VAR['wo_id'];

/* Assign Varibles to smarty */
$smarty->assign('single_workorder',         display_single_open_workorder($db, $wo_id)  );
$smarty->assign('workorder_notes',          display_workorder_notes($db, $wo_id)        );
$smarty->assign('workorder_parts',          display_parts($db, $wo_id)                  );             
$smarty->assign('workorder_status',         display_workorder_status($db, $wo_id)       );
$smarty->assign('workorder_schedule',       display_work_order_schedule ($db, $wo_id)   );    
$smarty->assign('workorder_resolution',     display_resolution($db, $wo_id)             );

$smarty->display('workorder'.SEP.'details.tpl');