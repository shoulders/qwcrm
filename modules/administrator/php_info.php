<?php

require(INCLUDES_DIR.'modules/administrator.php');

// Fetch the page with the phpinfo
$smarty->assign('phpinfo', getPHPInfo());
$BuildPage .= $smarty->fetch('administrator'.SEP.'phpinfo.tpl');