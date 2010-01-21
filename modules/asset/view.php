<?php
//Load Language File
if(!xml2php("asset")) {
	$smarty->assign('error_msg',"Error in language file");
}
$smarty->display('asset'.SEP.'view.tpl');

?>
