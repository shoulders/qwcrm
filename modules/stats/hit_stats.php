<?php

require(INCLUDES_DIR.'modules/supplier.php');

/* set start date */

// add this to the other time sttements for refernce? jon
$today_start = mktime(0,0,0,date("m"), date("d"), date("Y"));
$today_end      = mktime(23,59,59,date("m"), date("d"), date("Y"));

$month_start = mktime(0,0,0,date("m"), 1, date("Y"));
$month_end     = mktime(0,0,0,date("m")+1, 0, date("Y"));

/* local ip's we do not want to watch*/
$filter_ips = array('71.32.223.153');

/* build and */
foreach($filter_ips as $ip) {
    $where .=" AND ip !='$ip' ";
}
//print $where;

/* total Hits for the day */
$q= "SELECT count(*) as count FROM ".PRFX."TRACKER WHERE date >= '$today_start' AND date <= '$today_end' ".$where;
if(!$rs = $db->Execute($q)) {
    echo 'Error: '. $db->ErrorMsg();
}
$count = $rs->fields['count'];
$smarty->assign('daily_total', $count);

/* load all stats for the day */
$q = "SELECT   date, uagent, count(*) as count, ip FROM ".PRFX."TRACKER WHERE date >= '$today_start' AND date <= '$today_end' ".$where." GROUP BY ip ORDER BY date  ";
if(!$rs = $db->Execute($q)){
    echo 'Error: '. $db->ErrorMsg();
    die;
}
$arr = $rs->GetArray();
$smarty->assign('hit', $arr);


/* load stats for the month */
$q= "SELECT count(*) as count FROM ".PRFX."TRACKER WHERE date >= '$month_start' AND date <= '$month_end' ".$where;
if(!$rs = $db->Execute($q)) {
    echo 'Error: '. $db->ErrorMsg();
}

$count = $rs->fields['count'];
$smarty->assign('month_hit', $count);


//print_r($arr);

$BuildPage .= $smarty->fetch('stats'.SEP.'hit_stats.tpl');