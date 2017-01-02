<?php

require_once('includes/modules/core_theme.php');

/* Display Date and Time */
$smarty->assign('todays_display_date', date('l, j F Y'));

/* Add a welcome message based on time */
$smarty->assign('greeting_msg', greeting_message_based_on_time($login_display_name));

$BuildPage .= $smarty->fetch('core/blocks/theme_header_block.tpl');

