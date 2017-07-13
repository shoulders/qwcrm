<?php

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/user.php');
require(INCLUDES_DIR.'modules/workorder.php');

// Fetch the page with the user details from the database 
$smarty->assign('open_workorders', display_workorders($db, 'DESC', false, $page_no, '25', null, null, '2', $user_id));
$smarty->assign('user_details', get_user_details($db, $user_id));
$smarty->assign('usergroups', get_usergroups($db));
$BuildPage .= $smarty->fetch('user/details.tpl');