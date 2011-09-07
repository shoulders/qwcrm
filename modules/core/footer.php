<?php

$smarty->display('core'.SEP.'footer.tpl');
//$smarty->assign('VERSION', MYIT_CRM_VERSION);
if(debug == 'yes')	{
	echo 'PHP script executed in: ' . (getMicroTime() - $start .' secs<br>');
       	
	unset($VAR);
{
if (!empty($_SERVER['HTTP_CLIENT_IP'])) //check ip from share internet
{
 $ip=$_SERVER['HTTP_CLIENT_IP'];
}
elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
//to check ip is pass from proxy
{
  $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
}
else
{
 $ip=$_SERVER['REMOTE_ADDR'];
}
 echo ('My real IP is:'.$ip);
}
}

?>