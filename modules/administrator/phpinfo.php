<?php

$smarty->assign('phpinfo', getPHPInfo());

$smarty->display('administrator'.SEP.'phpinfo.tpl');