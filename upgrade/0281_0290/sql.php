<?php
##################################
# create_table_company				#
##################################
if(!create_table_company($db)){
	echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."TABLE_COMPANY</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."TABLE_COMPANY</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
###############################
# UPDATE CUSTOMER TABLE				#
###############################
if(!create_table_customer($db)) {
	echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_CUSTOMER</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_CUSTOMER</td>
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}
###############################
# UPDATE EMPLOYEE TABLE				#
###############################
if(!update_employee_address($db)) {
	echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_EMPLOYEE</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_EMPLOYEE</td>
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}


##################################
# create_setup							#
##################################
if(!create_setup($db) ) {
echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."SETUP</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."SETUP</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}


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
# create expense					#
##################################
if(!create_expense($db) ) {
echo("<tr>\n
			<td>CREATED TABLE ".PRFX."TABLE_EXPENSE</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATED TABLE ".PRFX."TABLE_EXPENSE</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
##################################
# create refund								#
##################################
if(!create_refund($db) ) {
echo("<tr>\n
			<td>CREATED TABLE ".PRFX."TABLE_REFUND</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATED TABLE ".PRFX."TABLE_REFUND</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
##################################
# create_supplier							#
##################################
if(!create_supplier($db) ) {
echo("<tr>\n
			<td>CREATED TABLE ".PRFX."TABLE_SUPPLIER</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATED TABLE ".PRFX."TABLE_SUPPLIER</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
/* START SQL STUFF FOR UPGRADE */

function create_table_company($db){
	   $q="ALTER TABLE `".PRFX."TABLE_COMPANY`
            CHANGE `COMPANY_ADDRESS` `COMPANY_ADDRESS` text default NULL,
            ADD `COMPANY_FAX` varchar(30) default NULL,
            ADD `COMPANY_CURRENCY_SYMBOL` varchar(30) default NULL,
            ADD `COMPANY_CURRENCY_CODE` varchar(30) default NULL,
            ADD `COMPANY_DATE_FORMAT` varchar(10) default NULL ;" ;
            $rs = $db->Execute($q);
			if(!$rs) {
				return false;
			} else {
				return true;
			}
}
function create_table_customer($db){
	$q="ALTER TABLE `".PRFX."TABLE_CUSTOMER`
            CHANGE `CUSTOMER_ADDRESS` `CUSTOMER_ADDRESS` text default NULL,
            ADD `CREDIT_TERMS` varchar(80) default NULL,
            ADD `CUSTOMER_WWW` varchar(80) default NULL,
            ADD `CUSTOMER_NOTES` text default NULL ;" ;

	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}

function create_setup($db) {
  $q = "ALTER TABLE `".PRFX."SETUP`,
  ADD `INVOICE_NUMBER_START` varchar(10) default NULL,
  ADD `PAYMATE_LOGIN` varchar(50) default NULL,
  ADD `PAYMATE_PASSWORD` varchar(50) default NULL,
  ADD `PAYMATE_FEES` decimal(10,2) NOT NULL default '1.5',
  CHANGE `INVOICE_TAX` `INVOICE_TAX` decimal(10,2) NOT NULL default '0.00';";
    	$rs = $db->Execute($q);
			if(!$rs) {
				return false;
			} else {
				return true;
			}
}

function create_acl($db) {
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (67, 'billing:proc_paymate', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (68, 'control:backup', 0, 0, 0, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (69, 'customer:email', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (70, 'expense:new', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (71, 'expense:search', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (72, 'refund:new', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (73, 'refund:search', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (74, 'supplier:new', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
        $q = "INSERT IGNORE INTO `".PRFX."ACL` VALUES (75, 'supplier:search', 1, 1, 1, 1, 0)";

                                $rs = $db->Execute($q);
                                if(!$rs) {
                                        return false;
                                } else {
                                        return true;
                                }
}
function update_assets($db) {
  $q = "ALTER TABLE `".PRFX."TABLE_ASSET` CHANGE `ASSEST_NUMBER` `ASSET_NUMBER`,
  ;";
$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}
function update_employee_address($db) {
  $q = "ALTER TABLE `".PRFX."TABLE_EMPLOYEE` CHANGE `EMPLOYEE_ADDRESS` `EMPLOYEE_ADDRESS` TEXT  ;";
$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}

}


function create_expense($db) {
   $q = "CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_EXPENSE` (
  `EXPENSE_ID` int(10) NOT NULL AUTO_INCREMENT,
  `EXPENSE_PAYEE` varchar(80) DEFAULT NULL,
  `EXPENSE_DATE` int(20) DEFAULT NULL,
  `EXPENSE_TYPE` varchar(20) DEFAULT NULL,
  `EXPENSE_PAYMENT_METHOD` varchar(20) DEFAULT NULL,
  `EXPENSE_NET_AMOUNT` decimal(10,2) DEFAULT '0.00',
  `EXPENSE_TAX_RATE` decimal(3,1) DEFAULT '0.0',
  `EXPENSE_TAX_AMOUNT` decimal(10,2) DEFAULT '0.00',
  `EXPENSE_GROSS_AMOUNT` decimal(10,2) DEFAULT '0.00',
  `EXPENSE_NOTES` text,
  `EXPENSE_ITEMS` text,
  PRIMARY KEY (`EXPENSE_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
if(!$rs = $db->Execute($q)) {
				return false;
			} else {
				return true;
			}
}
function create_refund($db) {
   $q = "CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_REFUND` (
  `REFUND_ID` int(10) NOT NULL AUTO_INCREMENT,
  `REFUND_PAYEE` varchar(80) DEFAULT NULL,
  `REFUND_DATE` int(20) DEFAULT NULL,
  `REFUND_TYPE` varchar(20) DEFAULT NULL,
  `REFUND_PAYMENT_METHOD` varchar(20) DEFAULT NULL,
  `REFUND_NET_AMOUNT` decimal(10,2) DEFAULT '0.00',
  `REFUND_TAX_RATE` decimal(3,1) DEFAULT '0.0',
  `REFUND_TAX_AMOUNT` decimal(10,2) DEFAULT '0.00',
  `REFUND_GROSS_AMOUNT` decimal(10,2) DEFAULT '0.00',
  `REFUND_NOTES` text,
  `REFUND_ITEMS` text,
  PRIMARY KEY (`REFUND_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ";
if(!$rs = $db->Execute($q)) {
				return false;
			} else {
				return true;
			}
}
function create_supplier($db) {
   $q = "CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_SUPPLIER` (
  `SUPPLIER_ID` int(10) NOT NULL AUTO_INCREMENT,
  `SUPPLIER_NAME` varchar(80) DEFAULT NULL,
  `SUPPLIER_CONTACT` varchar(80) DEFAULT NULL,
  `SUPPLIER_TYPE` varchar(20) DEFAULT NULL,
  `SUPPLIER_PHONE` varchar(20) DEFAULT NULL,
  `SUPPLIER_FAX` varchar(20) DEFAULT NULL,
  `SUPPLIER_MOBILE` varchar(20) DEFAULT NULL,
  `SUPPLIER_WWW` varchar(80) DEFAULT NULL,
  `SUPPLIER_EMAIL` varchar(80) DEFAULT NULL,
  `SUPPLIER_ADDRESS` text,
  `SUPPLIER_CITY` varchar(40) DEFAULT NULL,
  `SUPPLIER_STATE` varchar(40) DEFAULT NULL,
  `SUPPLIER_ZIP` varchar(20) DEFAULT NULL,
  `SUPPLIER_NOTES` text,
  `SUPPLIER_DESCRIPTION` text,
  PRIMARY KEY (`SUPPLIER_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 " ;
if(!$rs = $db->Execute($q)) {
				return false;
			} else {
				return true;
			}
}

?>

