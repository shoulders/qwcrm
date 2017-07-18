<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/administrator.php');

// Build the page with the phpinfo
$smarty->assign('phpinfo', getPHPInfo());
$BuildPage .= $smarty->fetch('administrator/phpinfo.tpl');