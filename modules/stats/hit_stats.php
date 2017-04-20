<?php

require(INCLUDES_DIR.'modules/stats.php');

// Set start and and end date - see schedule
$today_start    = mktime(0,0,0,date("m"), date("d"), date("Y"));
$today_end      = mktime(23,59,59,date("m"), date("d"), date("Y"));

// Set month start and end
$month_start    = mktime(0,0,0,date("m"), 1, date("Y"));
$month_end      = mktime(0,0,0,date("m")+1, 0, date("Y"));

// IP addressesto exlude from the results
$filter_ips = array('71.32.223.153');
foreach($filter_ips as $ip) {
    $where .=" AND ip !='$ip' ";
}

// Hits for the day
$smarty->assign('daily_total', day_hits($db, $today_start, $today_end, $where));

// All stats for the day
$smarty->assign('hits', get_day_all_stats($db, $today_start, $today_end, $where));

// Hits for the month
$smarty->assign('month_hits', get_month_hits($db, $month_start, $month_end, $where));

// fetch and build the page
$BuildPage .= $smarty->fetch('stats/hit_stats.tpl');