<?php

require(INCLUDES_DIR.'modules/administrator.php');

$smarty->assign('phpinfo', getPHPInfo());

$BuildPage .= $smarty->fetch('administrator'.SEP.'phpinfo.tpl');