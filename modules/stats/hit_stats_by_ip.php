<?php

require(INCLUDES_DIR.'modules/stats.php');

// Display Hits by IP
$smarty->assign('hits', display_hits_by_ip($db, $VAR['ip_address']));
$BuildPage .= $smarty->fetch('stats/hit_stats_by_ip.tpl' );