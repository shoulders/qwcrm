<?php
require_once("include.php");

if(!xml2php("workorder")) {
    $smarty->assign('error_msg',"Error in language file");
}

/* Grab passed varibles */
$submit = $VAR['submit'];
$assign = $VAR['assign'];
$assign_val = $VAR['assign_val'];
$wo_id = $VAR['wo_id'];
$tech = $VAR['tech'];
$login_id = $VAR['login_id'];

/* Check for Open Work Orders waiting for parts */
$q = "SELECT count(*) as count  FROM ".PRFX."ORDERS WHERE WO_ID=".$db->qstr($wo_id);
if(!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
}
$smarty->assign('part', $rs->fields['count']);

/* Grab Technicians Names */
$q = "SELECT EMPLOYEE_LOGIN, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
if(!$rs = $db->execute($q)) {
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;
}
$tech=$rs->GetMenu2('assign_val', $login,$login_id);
$smarty->assign('tech', $tech);

/* Grab specified Work Order and assign the variables to smarty */
if(!$single_work_order = display_single_open_workorder($db, $VAR['wo_id'])){
    force_page('core', "error&menu=1&error_msg=The Work Order you requested was not found&type=error");
    exit;
}
$smarty->assign('single_workorder_array', $single_work_order);
$smarty->assign('work_order_notes', display_workorder_notes($db, $VAR['wo_id']));
$smarty->assign('order', display_parts($db,$VAR['wo_id'])                );             
$smarty->assign('work_order_status', display_workorder_status($db, $VAR['wo_id']));
$smarty->assign('work_order_sched', get_work_order_schedule ($db,$VAR['wo_id']));    
$smarty->assign('resolution', display_resolution($db,$VAR['wo_id']));

/* Delete a Work Order */
if (isset($VAR['delete'])) {
    $sql = "DELETE FROM ".PRFX."TABLE_WORK_ORDER WHERE WORK_ORDER_ID=".$db->qstr($wo_id) ;
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    // Write comment to log
    $month = date("M");
    $day = date("d");
    $year = date("Y");
    $time =  date("H").":".date("i").":".date("s");
    // Get environment variables
    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

    // Create entry
    $data = "Workorder ".$wo_id." has been deleted ,".$login.",".$hostname.",".$month."-".$day."-".$year.",".$time.",\n";
    // Write File
    $fp = fopen(ACCESS_LOG,'a') or die("can't open access.log: $php_errormsg");
    fwrite($fp, $data);
    fclose($fp);

    // Redirect to the Open Work Orders Page
    echo('        
            <script type="text/javascript">
                window.location = "index.php?page=workorder:open&page_title=Work Orders"
            </script>
        ');
} else {
        $smarty->display('workorder'.SEP.'details.tpl');
}
// $smarty->assign('submit', $submit); 26-07-16 - I dont think this is needed

/* Assign Work Order to another employee and log it */
if (isset($VAR['assign'])) {
    
        $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET WORK_ORDER_ASSIGN_TO=".$db->qstr($assign_val).", WORK_ORDER_CURRENT_STATUS=2 WHERE WORK_ORDER_ID=".$db->qstr($wo_id) ;
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }        

    // Write comment to log
    $month = date("M");
    $day = date("d");
    $year = date("Y");
    $time =  date("H").":".date("i").":".date("s");
    
    // Get environment variables
    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

    // Create entry
    $data = "Workorder ".$wo_id." has been assigned to ".$assign_val." by ".$login.",".$hostname.",".$month."-".$day."-".$year.",".$time.",\n";
    
    // Write File
    $fp = fopen(ACCESS_LOG,'a') or die("can't open access.log: $php_errormsg");
    fwrite($fp, $data);
    fclose($fp);

    // Redirect to the homepage
    echo('        
            <script type="text/javascript">
                <!-- window.location = "index.php" -->
            </script>
        ');    
}
$smarty->assign('assign', $assign);

/* Re-Assign Work Order to another employee and log it */
if (isset($VAR['assign2'])) {
    //UPDATE trunk.myit_table_work_order SET `WORK_ORDER_CREATE_BY` = 1 WHERE `WORK_ORDER_ID` = 15;
    $sql = "UPDATE ".PRFX."TABLE_WORK_ORDER SET WORK_ORDER_ASSIGN_TO=".$db->qstr($assign2).", WORK_ORDER_CURRENT_STATUS=2 WHERE WORK_ORDER_ID=".$db->qstr($wo_id) ;
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }

    //Write comment to log
    $month = date("M");
    $day = date("d");
    $year = date("Y");
    $time =  date("H").":".date("i").":".date("s");
    //get environment variables
    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);

    // Create entry
    $data = "Workorder ".$wo_id." has been assigned to ".$assign2." by ".$login.",".$hostname.",".$month."-".$day."-".$year.",".$time.",\n";
    
    // Write File
    $fp = fopen(ACCESS_LOG,'a') or die("can't open access.log: $php_errormsg");
    fwrite($fp, $data);
    fclose($fp);

    // Redirect to the homepage
    echo('        
            <script type="text/javascript">
                <!-- window.location = "index.php" -->
            </script>
        ');
}
$smarty->assign('assign2', $assign2);