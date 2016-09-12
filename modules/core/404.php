<?php

//require('includes'.SEP.'modules'.SEP.'core_404.php'); not needed

$smarty->assign('page_title', 'ERROR: 404');
$smarty->assign('pagename', $_SERVER['REQUEST_URI']);
$smarty->assign('admin_email', ADMIN_EMAIL);

$smarty->display('core'.SEP.'404.tpl');