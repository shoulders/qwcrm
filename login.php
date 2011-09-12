<?php
#####################################################
# This program is distributed under the terms and 	#
# conditions of the GPL								#
# login.php											#
# Version 0.0.1	Fri Sep 30 09:30:10 PDT 2005		#
#													#
#####################################################

#####################################
#	Load all Configs				#
#####################################
require("conf.php");

#####################################
#	Initial Authorization				#
#####################################

$smarty->assign('page_title', 'Login');

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
/*$smarty->assign('VERSION', VERSION_NAME);*/
$smarty->display('core'.SEP.'login.tpl');

?>
