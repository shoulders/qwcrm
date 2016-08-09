<?php

require_once (__DIR__.'/../include.php');

/* Display Date and Time */
$smarty->assign('today', Date('l, j F Y'));

/* Add a welcome message based on time */
$smarty->assign('greeting_msg', greeting_message_based_on_time($login_usr));

$smarty->display('core'.SEP.'blocks'.SEP.'theme_header_block.tpl');