<?php

// Send 404 header
header('HTTP/1.1 404 Not Found');

//require('includes'.SEP.'modules'.SEP.'core_404.php'); not needed
$smarty->display('core'.SEP.'404.tpl');