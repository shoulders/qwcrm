<?php
function check_acl($db,$module,$page	) {
	$uid = $_SESSION['login_id'];
	
	/* get group id */
	$q = 'SELECT '.PRFX.'CONFIG_EMPLOYEE_TYPE.TYPE_NAME
			FROM '.PRFX.'TABLE_EMPLOYEE,'.PRFX.'CONFIG_EMPLOYEE_TYPE 
			WHERE '.PRFX.'TABLE_EMPLOYEE.EMPLOYEE_TYPE  = '.PRFX.'CONFIG_EMPLOYEE_TYPE.TYPE_ID AND EMPLOYEE_ID='.$db->qstr($uid);
	if(!$rs = $db->execute($q)) {
		force_page('core','error&error_msg=Could not get Group ID for user');
		exit;
	} else {
		$gid = $rs->fields['TYPE_NAME'];
	}

	/* check page to see if we have access */
	if(!isset($module)) {
		$page= "core:main";
	} else {
		$page= $module.":".$page;
	}

	$q = 'SELECT '.$gid.' as ACL FROM '.PRFX.'ACL WHERE page='.$db->qstr($page);

	if(!$rs = $db->execute($q)) {
		force_page('core','error&error_msg=Could not get Page ACL');
		exit;
	} else {
		$acl = $rs->fields['ACL'];
		if($acl != 1) {
			return false;	
		} else {
			return true;	
		}
	}
}
function encrypt($strString, $strKey) {
$deresult = '';
for($i=0; $i<strlen($strString); $i++) {
$char = substr($strString, $i, 1);
$keychar = substr($strKey, ($i % strlen($strKey))-1, 1);
$char = chr(ord($char)+ord($keychar));
$deresult.=$char;
}

return base64_encode($deresult);
}
/*
function encrypt ($strString, $strKey) {

	if ($strString=="") {
		return $strString;
	}
	$iv = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$enString=mcrypt_ecb(MCRYPT_BLOWFISH, $strKey, $strString, MCRYPT_ENCRYPT, $iv);
	$enString=bin2hex($enString);

	return ($enString);
	
}

function decrypt ($strString, $strKey) {
	
	if ($strString=="") {
		return $strString;
	}
	$iv = mcrypt_create_iv (mcrypt_get_iv_size (MCRYPT_BLOWFISH, MCRYPT_MODE_ECB), MCRYPT_RAND);
	$strString=hex2bin($strString);
	$deString=mcrypt_ecb(MCRYPT_BLOWFISH, $strKey, $strString, MCRYPT_DECRYPT, $iv);

	return ($deString);

}

 */
 function decrypt($strString, $strKey) {
$deresult = '';
$strString = base64_decode($strstring);

for($i=0; $i<strlen($strString); $i++) {
$char = substr($strString, $i, 1);
$keychar = substr($strKey, ($i % strlen($strKey))-1, 1);
$char = chr(ord($char)-ord($keychar));
$deresult.=$char;
}

return $deresult;
}

?>