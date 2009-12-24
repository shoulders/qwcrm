<?php
require("conf.php");

$smarty->assign('page_title', 'Submit Support Request Form');

#####################################
#	Display Any Errors				#
##################################### 

if(isset($_GET["error_msg"]))
{
	$smarty->assign('error_msg', $_GET["error_msg"]);
}


#####################################
#	Display the pages				#
#####################################
/* CRM Version */
$smarty->assign('VERSION', MYIT_CRM_VERSION);
$smarty->display('core'.SEP.'submit.tpl');

?>
