<?php
##################################
# create_acl								#
##################################
if(!create_acl($db) ) {
echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."ACL</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."ACL</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
##################################
# update customer DB field lengths								#
##################################
if(!update_table_customer($db) ) {
echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."CUSTOMER</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."CUSTOMER</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

function create_acl($db) {
        
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (76, 'supplier:supplier_details', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }


        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (77, 'expense:expense_details', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }


}
function update_table_customer($db){

	$q="ALTER TABLE `".PRFX."TABLE_CUSTOMER`
            CHANGE `CUSTOMER_ADDRESS` `CUSTOMER_ADDRESS` text default NULL,
		CHANGE `CUSTOMER_CITY` `CUSTOMER_CITY` varchar(40) default NULL,
		CHANGE `CUSTOMER_STATE` `CUSTOMER_STATE` varchar(40) default NULL,
		CHANGE `CUSTOMER_ZIP` `CUSTOMER_ZIP` varchar(20) default NULL,
		CHANGE `CUSTOMER_PHONE` `CUSTOMER_PHONE` varchar(20) default NULL,
		CHANGE `CUSTOMER_WORK_PHONE` `CUSTOMER_WORK_PHONE` varchar(20) NOT NULL default '',
		CHANGE `CUSTOMER_MOBILE_PHONE` `CUSTOMER_MOBILE_PHONE` varchar(20) NOT NULL default '',
		CHANGE `CUSTOMER_EMAIL` `CUSTOMER_EMAIL` varchar(80) default NULL,
                CHANGE `CREDIT_TERMS` `CREDIT_TERMS` text default NULL" ;

	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}

?>

