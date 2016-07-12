<?php
##################################
# create_version table				#
##################################
if(!create_version($db)) {
	echo("<tr>\n
					<td>CREATE TABLE IF NOT EXISTS ".PRFX."VERSION</td>\n
					<td><font color=\"red\"><b>Failed </b> </font> ".$db->ErrorMsg() ."</td>\n
			</tr>\n");
			$error_flag = true;
} else {
	echo("<tr>\n
					<td>CREATE TABLE IF NOT EXISTS ".PRFX."VERSION</td>\n
					<td><font color=\"green\"><b>OK</b></font></td>\n
			<tr>\n");
}

##################################
# create_billing_options				#
##################################
if(!create_billing_options($db)) {
	echo("<tr>\n
					<td>CREATE TABLE IF NOT EXISTS ".PRFX."BILLING_OPTIONS</td>\n
					<td><font color=\"red\"><b>Failed </b> </font> ".$db->ErrorMsg() ."</td>\n
			</tr>\n");
			$error_flag = true;
} else {
	echo("<tr>\n
					<td>CREATE TABLE IF NOT EXISTS ".PRFX."BILLING_OPTIONS</td>\n
					<td><font color=\"green\"><b>OK</b></font></td>\n
			<tr>\n");
}

##################################
# create_config_cc_cards				#
##################################
if(!create_config_cc_cards($db)) {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."CONFIG_CC_CARDS</td>\n
				<td><font color=\"red\"><b>Failed</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
			$error_flag = true;
} else {
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."CONFIG_CC_CARDS</td>\n
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}

##################################
# create_config_work_order_status	#
##################################
if(!create_config_work_order_status($db)) {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."CONFIG_WORK_ORDER_STATUS</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
			$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."CONFIG_WORK_ORDER_STATUS</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# insert_workorder_status			#
##################################
if(!insert_workorder_status($db)){
	echo("<tr>\n
				<td>Insert values for ".PRFX."CONFIG_WORK_ORDER_STATUS</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;
}else {
	echo("<tr>\n
				<td>Insert values for ".PRFX."CONFIG_WORK_ORDER_STATUS</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			<tr>\n");
}
##################################
# drop_table_company				#
##################################
if(!drop_table_company($db)){
	echo("<tr>\n
				<td>DELETED TABLE ".PRFX."TABLE_COMPANY TO REPLACE WITH NEW DATA</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;	
} else {
	echo("<tr>\n
				<td>DELETED TABLE ".PRFX."TABLE_COMPANY TO REPLACE WITH NEW DATA</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_table_company				#
##################################
if(!create_table_company($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_COMPANY</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;	
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_COMPANY</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_table_customer				#
##################################
if(!create_table_customer($db)){
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_CUSTOMER</td>
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_CUSTOMER</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

if(!create_table_customer_memo($db)){
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."CUSTOMER_MEMO</td>
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."CUSTOMER_MEMO</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}


##################################
# create_table_employee				#
##################################
if(!create_table_employee($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_CUSTOMER</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;
}else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_CUSTOMER</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

###############################
# create_table_invoice			#
###############################
if(!create_table_invoice($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."INVOICE</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
}else {
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."INVOICE</td>\n
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}

##################################
# create_table_invoice_labor		#
##################################
if(!create_table_invoice_labor($db)){
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."INVOICE_LABOR</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>");
	$error_flag = true;
}else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."INVOICE_LABOR</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_table_invoice_parts		#
##################################
if(!create_table_invoice_parts($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."INVOICE_PARTS</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
}else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."INVOICE_PARTS</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>");
}

###############################
# create_labor_rate				#
###############################
if(!create_labor_rate($db)) {
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."LABOR_RATE</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."LABOR_RATE</td>
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}

##################################
# create_table_schedule				#
##################################
if(!rename_table_schedule($db)){
	echo("<tr>\n
				<td>RENEAMED TABLE ".PRFX."schedule TO ".PRFX."SCHEDULE</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;
}else {
	echo("<tr>\n
			<td>RENEAMED TABLE ".PRFX."schedule TO ".PRFX."SCHEDULE</td>\n
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}


##################################
# create_table_schedule				#
##################################
if(!create_table_schedule($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."SCHEDULE</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;
}else {
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."SCHEDULE</td>\n
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}

##################################
# create_table_software				#
##################################
if(!create_table_software($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."SOFTWARE</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;
}else {
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."SOFTWARE</td>\n
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}

##################################
# create_table_assets				#
##################################
if(!create_table_asset($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."ASSET</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;
}else {
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."ASSET</td>\n
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}
##################################
# create_table_transactions			#
##################################
if(!create_table_transactions($db)) {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TRANSACTIONS</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TRANSACTIONS</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_table_workorder				#
##################################
if(!create_table_workorder($db)){
	echo("<tr>
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."WORKORDER</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
}else {
	echo("<tr>
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."WORKORDER</td>\n
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}

##################################
# create_workorder_notes				#
##################################
if(!create_workorder_notes($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."WORK_ORDER_NOTES</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
}else {
	echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."WORK_ORDER_NOTES</td>\n
			<td><font color=\"green\"><b>OK</b></font></td>\n
		</tr>\n");
}

##################################
# creat_workorder_status				#
##################################
if(!creat_workorder_status($db)){
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."WORKORDER_STATUS</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
}else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."WORKORDER_STATUS</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_table_tracker				#
##################################
if(!create_table_tracker($db)) {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TRACKER</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TRACKER</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
##################################
# drop_setup          			 #
##################################
if(!drop_setup($db)){
	echo("<tr>\n
				<td>DELETED TABLE ".PRFX."SETUP TO REPLACE WITH NEW DATA</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>");
	$error_flag = true;	
} else {
	echo("<tr>\n
				<td>DELETED TABLE ".PRFX."SETUP TO REPLACE WITH NEW DATA</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
##################################
# create_setup							#
##################################
if(!create_setup($db) ) {
echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."SETUP</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."SETUP</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}


##################################
# create_acl								#
##################################
if(!create_acl($db) ) {
echo("<tr>\n
			<td>CREATE TABLE IF NOT EXISTS ".PRFX."ACL</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
		</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."ACL</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}	

##################################
# create_employee_type				#
##################################
if(!create_employee_type($db) ) {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."EMPLOYEE_TYPE</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."EMPLOYEE_TYPE</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_cart							#
##################################
if(!create_cart($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."CART</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."CART</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_cat								#
##################################
if(!create_cat($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."CAT</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."CAT</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_orders							#
##################################
if(!create_orders($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."ORDERS</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."ORDERS</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_order_details				#
##################################
if(!create_order_details($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."ORDERS_DETAILS</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."ORDERS_DETAILS</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# create_sub_cat						#
##################################
if(!create_sub_cat($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."SUB_CAT</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."SUB_CAT</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

if(!create_country($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."COUNTRY</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."COUNTRY</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
if(!create_gift($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."GIFT_CERT</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."GIFT_CERT</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
##################################
# create_TABLE_EXPENSE				#
##################################
if(!create_expense($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_EXPENSE</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_EXPENSE</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
##################################
# create_refund				#
##################################
if(!create_refund($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_REFUND</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_REFUND</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
##################################
# create_supplier				#
##################################
if(!create_supplier($db)) {
echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_SUPPLIER</td>\n
				<td><font color=\"red\"><b>Failed:</b></font> ". $db->ErrorMsg() ."</td>\n
			</tr>\n");
	$error_flag = true;
} else {
	echo("<tr>\n
				<td>CREATE TABLE IF NOT EXISTS ".PRFX."TABLE_SUPPLIER</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

##################################
# Functions								#
##################################
function create_gift($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."GIFT_CERT` (
	`GIFT_ID` int(20) NOT NULL auto_increment,
	`MEMO` text NOT NULL,
	`DATE_CREATE` int(20) NOT NULL default '0',
	`EXPIRE` int(20) NOT NULL default '0',
	`GIFT_CODE` varchar(32) NOT NULL default '',
	`CUSTOMER_ID` int(20) NOT NULL default '0',
	`AMOUNT` decimal(6,2) NOT NULL default '0.00',
	`ACTIVE` int(4) NOT NULL default '0',
	`DATE_REDEMED` int(20) NOT NULL default '0',
	`INVOICE_ID` int(20) NOT NULL default '0',
	PRIMARY KEY  (`GIFT_ID`),
	KEY `GIFT_CODE` (`GIFT_CODE`,`CUSTOMER_ID`,`ACTIVE`)
	) ENGINE=MyISAM ";
	if(!$rs = $db->execute($q)) {
			return false;
	} else {
		return true;
	}
}
// ADDING VERSION NUMBER TO DATABASE
function create_version($db) {
    $q="CREATE TABLE IF NOT EXISTS `".PRFX."VERSION` (
    `VERSION_ID` INT NOT NULL ,
    `VERSION_NAME` VARCHAR( 10 ) NOT NULL ,
    `VERSION_INSTALLED` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=MyISAM ";
    if(!$rs = $db->execute($q)) {
			return false;
	} else {
        //Insert New Records for version table
        $q = "INSERT INTO `".PRFX."VERSION` (`VERSION_ID`, `VERSION_NAME`) VALUES ('293', '0.2.9.3')";

    if(!$rs = $db->execute($q) ) {
			return false;
		} else {
			return true;
		}
	}
}

function create_billing_options($db) {

$q = "CREATE TABLE IF NOT EXISTS `".PRFX."CONFIG_BILLING_OPTIONS` (
		`ID` int(11) NOT NULL auto_increment,
		`BILLING_OPTION` varchar(64) NOT NULL default '',
		`BILLING_NAME` varchar(64) NOT NULL default '',
		`ACTIVE` int(1) NOT NULL default '1',
		PRIMARY KEY  (`ID`)
	) ENGINE=MyISAM ";
	
	if(!$rs = $db->execute($q)) {
		return false;
	} else {
	
		$q = "INSERT IGNORE INTO `".PRFX."CONFIG_BILLING_OPTIONS` VALUES (1,'cc_billing','Credit Card',0),(2,'cheque_billing','Cheque',1),(3,'cash_billing','Cash',1),(4,'gift_billing','Gift Certificate',0),(5,'paypal_billing','Pay Pal',0),(6,'deposit_billing','Direct Deposit',0)";
	
		if(!$rs = $db->execute($q) ) {
			return false;
		} else {
			return true;
		}
	}
}

function create_config_cc_cards($db){

	$q="CREATE TABLE IF NOT EXISTS `".PRFX."CONFIG_CC_CARDS` (
		`ID` int(11) NOT NULL auto_increment,
		`CARD_TYPE` varchar(64) NOT NULL default '',
		`CARD_NAME` varchar(64) NOT NULL default '',
		`ACTIVE` int(1) NOT NULL default '0',
		PRIMARY KEY  (`ID`)
		) ENGINE=MyISAM ";
		
	if(!$rs = $db->execute($q)) {
		return false;
	} else { 
		$q="REPLACE INTO `".PRFX."CONFIG_CC_CARDS` VALUES (1,'visa','Visa',1),(2,'mc','Master Card',1),(3,'amex','Amex',0),(4,'discover','Discover',1),(5,'delta','Delta',0),(6,'solo','Solo',0),(7,'switch','Switch',0),(8,'jcb','JCB',0),(9,'diners','Diners',0),(10,'carteblanche','Carta Blanche',0),(11,'enroute','Enroute',0)";
		if(!$rs=$db->execute($q)) {
			return false;
		} else {
			return true;
		}	
	}

}

function create_config_work_order_status($db)
{
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."CONFIG_WORK_ORDER_STATUS` (
		`CONFIG_WORK_ORDER_STATUS_ID` int(11) NOT NULL auto_increment,
		`CONFIG_WORK_ORDER_STATUS` varchar(64) NOT NULL default '',
		`DISPLAY` int(1) NOT NULL default '0',
		PRIMARY KEY  (`CONFIG_WORK_ORDER_STATUS_ID`)
		) ENGINE=MyISAM ";
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}

function create_employee_type($db) {
	$q = "CREATE TABLE IF NOT EXISTS `".PRFX."CONFIG_EMPLOYEE_TYPE` (
	`TYPE_ID` int(11) NOT NULL auto_increment,
	`TYPE_NAME` varchar(255) NOT NULL default '',
	PRIMARY KEY  (`TYPE_ID`),
	KEY `TYPE_NAME` (`TYPE_NAME`)
	) ENGINE=MyISAM ";
	if(!$rs = $db->Execute($q)) {
		return false;
	} else {
		$q = "REPLACE INTO `".PRFX."CONFIG_EMPLOYEE_TYPE` VALUES (1, 'Manager'),(2, 'Supervisor'),(3, 'Technician'),(4, 'Admin')";
		if(!$rs = $db->execute($q)) {
			return false;
		} else {
			return true;
		}
	}

}

function insert_workorder_status($db) 
{
	$q="
		REPLACE INTO `".PRFX."CONFIG_WORK_ORDER_STATUS` VALUES (1,'Created',0),(2,'Assigned',1),(3,'Waiting For Parts',1),(6,'Closed',0),(7,'Awaiting Payment',0),(8,'Payment Made',0),(9,'Pending',0),(10,'Open',0)";
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}

function drop_table_company($db)
{
	$q="DROP TABLE IF EXISTS`".PRFX."TABLE_COMPANY`";

	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}

function create_table_company($db)
{
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_COMPANY` (
  `COMPANY_NAME` varchar(60) NOT NULL default '',
  `COMPANY_ADDRESS` text default NULL,
  `COMPANY_ABN` varchar(60) NOT NULL default '',
  `COMPANY_CITY` varchar(60) NOT NULL default '',
  `COMPANY_STATE` varchar(60) NOT NULL default '',
  `COMPANY_ZIP` varchar(20) NOT NULL default '',
  `COMPANY_COUNTRY` char(3) NOT NULL default '',
  `COMPANY_PHONE` varchar(40) NOT NULL default '',
  `COMPANY_MOBILE` varchar(40) NOT NULL default '',
  `COMPANY_FAX` varchar(40) NOT NULL default '',
  `COMPANY_EMAIL` varchar(100) NOT NULL default '',
  `COMPANY_CURRENCY_SYMBOL` varchar(30) default NULL,
  `COMPANY_CURRENCY_CODE` varchar(30) default NULL,
  `COMPANY_DATE_FORMAT` varchar(10) default NULL,
  PRIMARY KEY  (`COMPANY_NAME`)
) ENGINE=MyISAM;";

	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}
// Changes made for version 0.2.8
function create_table_customer($db){

	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_CUSTOMER` (
		`CUSTOMER_ID` int(11) NOT NULL auto_increment,
		`CUSTOMER_DISPLAY_NAME` varchar(80) NOT NULL default '',
		`CUSTOMER_ADDRESS` text default NULL,
		`CUSTOMER_CITY` varchar(40) default NULL,
		`CUSTOMER_STATE` varchar(40) default NULL,
		`CUSTOMER_ZIP` varchar(20) default NULL,
		`CUSTOMER_PHONE` varchar(40) default NULL,
		`CUSTOMER_WORK_PHONE` varchar(40) NOT NULL default '',
		`CUSTOMER_MOBILE_PHONE` varchar(40) NOT NULL default '',
		`CUSTOMER_EMAIL` varchar(80) default NULL,
        `CUSTOMER_WWW` varchar(80) default NULL,
        `CREDIT_TERMS` text default NULL,
        `CUSTOMER_NOTES` text default NULL,
		`CUSTOMER_TYPE` varchar(20) default NULL,
		`CUSTOMER_FIRST_NAME` varchar(39) default NULL,
		`CUSTOMER_LAST_NAME` varchar(39) default NULL,
		`CREATE_DATE` int(20) NOT NULL default '0',
		`LAST_ACTIVE` int(20) NOT NULL default '0',
		`DISCOUNT`  decimal(10,2) NOT NULL default '0.00',
		PRIMARY KEY  (`CUSTOMER_ID`)
		) ENGINE=MyISAM " ;

	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}

function create_table_customer_memo($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."CUSTOMER_NOTES` (
	`ID` INT( 20 ) NOT NULL AUTO_INCREMENT ,
	`CUSTOMER_ID` INT( 20 ) NOT NULL ,
	`DATE` INT( 20 ) NOT NULL ,
	`NOTE` TEXT NOT NULL ,
	PRIMARY KEY ( `ID` ) ,
	INDEX ( `CUSTOMER_ID` )
	) ENGINE=MyISAM ";
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}

}

function create_table_employee($db) {

	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_EMPLOYEE` (
		`EMPLOYEE_ID` int(11) NOT NULL auto_increment,
		`EMPLOYEE_LOGIN` varchar(50) NOT NULL default '',
		`EMPLOYEE_PASSWD` varchar(50) NOT NULL default '',
		`EMPLOYEE_EMAIL` varchar(80) NOT NULL default '',
		`EMPLOYEE_FIRST_NAME` varchar(40) NOT NULL default '',
		`EMPLOYEE_LAST_NAME` varchar(40) NOT NULL default '',
		`EMPLOYEE_DISPLAY_NAME` varchar(80) NOT NULL default '',
		`EMPLOYEE_SSN` int(9) NOT NULL default '0',
		`EMPLOYEE_ADDRESS` text default NULL,
		`EMPLOYEE_CITY` varchar(40) NOT NULL default '',
		`EMPLOYEE_STATE` char(60) NOT NULL default '',
		`EMPLOYEE_ZIP` varchar(11) NOT NULL ,
		`EMPLOYEE_TYPE` varchar(60) NOT NULL default '',
		`EMPLOYEE_WORK_PHONE` varchar(13) NOT NULL default '',
		`EMPLOYEE_HOME_PHONE` varchar(13) NOT NULL default '',
		`EMPLOYEE_MOBILE_PHONE` varchar(13) NOT NULL default '',
		`EMPLOYEE_START_TIME` int(20) NOT NULL default '0',
		`EMPLOYEE_END_TIME` int(20) NOT NULL default '0',
		`EMPLOYEE_BASED` varchar(10) NOT NULL default '1' ,
		`EMPLOYEE_ACL` int(11) NOT NULL default '0',
		`EMPLOYEE_STATUS` varchar(20) NOT NULL default '',
		PRIMARY KEY  (`EMPLOYEE_ID`),
		UNIQUE KEY `EMPLOYEE_LOGIN` (`EMPLOYEE_LOGIN`)
		) ENGINE=MyISAM " ;

	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 

function create_table_invoice($db)
{
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_INVOICE` (
  `INVOICE_ID` int(11) NOT NULL auto_increment,
  `INVOICE_DATE` varchar(30) default NULL,
  `CUSTOMER_ID` int(11) NOT NULL default '0',
  `WORKORDER_ID` int(11) NOT NULL default '0',
  `EMPLOYEE_ID` int(11) default NULL,
  `INVOICE_PAID` int(1) default '0',
  `INVOICE_AMOUNT` decimal(10,2) default '0.00',
  `INVOICE_DUE` int(20) NOT NULL default '0',
  `PAID_DATE` int(20) NOT NULL default '0',
  `PAID_AMOUNT` decimal(10,2) NOT NULL default '0.00',
  `BALANCE` decimal(10,2) NOT NULL default '0.00',
  `TAX_RATE` decimal(10,3) NOT NULL default '0.000',
  `DISCOUNT_APPLIED` decimal(10,3) NOT NULL default '0.000',
  `TAX` decimal(10,3) NOT NULL default '0.000',
  `SHIPPING` decimal(10,2) NOT NULL default '0.00',
  `DISCOUNT` decimal(10,2) NOT NULL default '0.00',
  `SUB_TOTAL` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`INVOICE_ID`),
  KEY `EMPLOYEE_ID` (`EMPLOYEE_ID`),
  KEY `WORKORDER_ID` (`WORKORDER_ID`)
) ENGINE=MyISAM";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 

function create_table_invoice_labor($db)
{
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_INVOICE_LABOR` (
		`INVOICE_LABOR_ID` int(11) NOT NULL auto_increment,
		`INVOICE_ID` int(11) NOT NULL default '0',
		`EMPLOYEE_ID` int(11) NOT NULL default '0',
		`INVOICE_LABOR_DESCRIPTION` text,
		`INVOICE_LABOR_RATE` decimal(10,2) NOT NULL default '0.00',
		`INVOICE_LABOR_UNIT` varchar(4) NOT NULL default '',
		`INVOICE_LABOR_SUBTOTAL` decimal(10,2) NOT NULL default '0.00',
		PRIMARY KEY  (`INVOICE_LABOR_ID`),
		KEY `INVOICE_ID` (`INVOICE_ID`)
		) ENGINE=MyISAM ";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 

function create_table_invoice_parts($db)
{
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_INVOICE_PARTS` (
  `INVOICE_PARTS_ID` int(11) NOT NULL auto_increment,
  `INVOICE_ID` int(11) NOT NULL default '0',
  `INVOICE_PARTS_MANUF` varchar(60) NOT NULL default '',
  `INVOICE_PARTS_MFID` varchar(30) NOT NULL default '',
  `INVOICE_PARTS_DESCRIPTION` varchar(60) NOT NULL default '',
  `INVOICE_PARTS_WARRANTY` varchar(30) NOT NULL default '',
  `INVOICE_PARTS_AMOUNT` decimal(10,2) NOT NULL default '0.00',
  `INVOICE_PARTS_SUBTOTAL` decimal(10,2) NOT NULL default '0.00',
  `SHIPPING` decimal(10,2) NOT NULL default '0.00',
  `INVOICE_PARTS_COUNT` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`INVOICE_PARTS_ID`),
  KEY `INVOICE_ID` (`INVOICE_ID`)
) ENGINE=MyISAM ";

	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 

function create_labor_rate($db) {

	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_LABOR_RATE` (
		`LABOR_RATE_ID` int(11) NOT NULL auto_increment,
                  `LABOR_RATE_NAME` varchar(32) NOT NULL default '',
                  `LABOR_RATE_AMOUNT` decimal(10,2) NOT NULL default '0.00',
                  `LABOR_RATE_COST` decimal(10,2) NOT NULL default '0.00',
                  `LABOR_RATE_ACTIVE` int(1) NOT NULL default '1',
                  `LABOR_TYPE` varchar(20)default NULL,
                  `LABOR_MANUF` varchar(30) default NULL,
                  PRIMARY KEY  (`LABOR_RATE_ID`)
                ) ENGINE=MyISAM ";

		
	 if(!$rs = $db->Execute($q)) {
			return false;
		} else {
		
			$q="REPLACE INTO `".PRFX."TABLE_LABOR_RATE` VALUES (1,'Basic Labor',45,0,1,'Service',''),(2,'Commercial',55,0,1,'Service',''),(3,'Virus Removal',65,0,1,'Service',''),(4,'Hard Drive',130,115,1,'Parts','Maxtor')";
			
			if(!$rs = $db->Execute($q)) {
				return false;
			} else {
				return true;
			}	
		}
		
		
}
##########################################
# RENAME TABLE IF EXISTS                 #
##########################################
function rename_table_schedule($db) {
	$q="ALTER TABLE `".PRFX."TABLE_schedule` RENAME TO `".PRFX."TABLE_SCHEDULE`";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return true;
		} else {
			return true;
		}
} 	



function create_table_schedule($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_SCHEDULE` (
  `SCHEDULE_ID` int(11) NOT NULL auto_increment,
  `SCHEDULE_START` int(20) NOT NULL default '0',
  `SCHEDULE_END` int(20) NOT NULL default '0',
  `WORK_ORDER_ID` int(11) NOT NULL default '0',
  `EMPLOYEE_ID` varchar(32) NOT NULL default '',
  `SCHEDULE_NOTES` text NOT NULL,
  `SCHEDULE_SYNC` int(5) NOT NULL default '0',
  `SCHEDULE_REMINDER` int(5) NOT NULL default '0',
  PRIMARY KEY  (`SCHEDULE_ID`),
  KEY `WORK_ORDER_ID` (`WORK_ORDER_ID`,`EMPLOYEE_ID`)
) ENGINE=MyISAM";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 

function create_table_software($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_SOFTWARE` (
  `SOFTWARE_ID` int(11) NOT NULL auto_increment,
  `SOFTWARE_TYPE` varchar(32) NOT NULL default '',
  `SOFTWARE_NAME` varchar(32) NOT NULL default '',
  `SOFTWARE_USERNAME` varchar(32) NOT NULL default '',
  `SOFTWARE_PASSWORD` varchar(32) NOT NULL default '',
  `LICENSE_START` int(20) NOT NULL default '0',
  `LICENSE_END` int(20) NOT NULL default '0',
  `LICENSE_LENGTH` int(5) NOT NULL default '0',
  `EXPIRY_REMINDER_SENT` int(20) NOT NULL default '0',
  `EXPIRY_REMINDER_SENT_BY` varchar(32) NOT NULL default '',
  `SOFTWARE_ACTIVE` int(5) NOT NULL default '0',
  `SOFTWARE_NOTES` text NOT NULL,
  PRIMARY KEY  (`SOFTWARE_ID`)
) ENGINE=MyISAM";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 

function create_table_asset($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_ASSET` (
  `ASSET_ID` int(11) NOT NULL auto_increment,
  `ASSER_TYPE` varchar(32) NOT NULL default '',
  `ASSET_NAME` varchar(32) NOT NULL default '',
  `ASSET_NUMBER` varchar(32) NOT NULL default '',
  `ASSET_START` int(20) NOT NULL default '0',
  `ASSET_END` int(20) NOT NULL default '0',
  `ASSET_SUPPORT_LENGTH` int(5) NOT NULL default '0',
  `ASSET_ACTIVE` int(5) NOT NULL default '1',
  `ASSET_NOTES` text NOT NULL,
  PRIMARY KEY  (`ASSET_ID`)
) ENGINE=MyISAM";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 				
	
function create_table_transactions($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_TRANSACTION` (
  `TRANSACTION_ID` int(11) NOT NULL auto_increment,
  `DATE` int(20) NOT NULL default '0',
  `TYPE` int(1) NOT NULL default '0',
  `INVOICE_ID` int(11) NOT NULL default '0',
  `WORKORDER_ID` int(11) NOT NULL default '0',
  `CUSTOMER_ID` int(11) NOT NULL default '0',
  `MEMO` text NOT NULL,
  `AMOUNT` decimal(10,2) NOT NULL default '0.00',
  PRIMARY KEY  (`TRANSACTION_ID`),
  KEY `INVOICE_ID` (`INVOICE_ID`,`WORKORDER_ID`,`CUSTOMER_ID`),
  KEY `DATE` (`DATE`),
  KEY `TYPE` (`TYPE`),
  FULLTEXT KEY `MEMO` (`MEMO`)
) ENGINE=MyISAM";
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}

}

function create_table_workorder($db)
{
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_WORK_ORDER` (
  `WORK_ORDER_ID` int(4) NOT NULL auto_increment,
  `CUSTOMER_ID` int(4) NOT NULL default '0',
  `WORK_ORDER_OPEN_DATE` int(20) NOT NULL default '0',
  `WORK_ORDER_STATUS` int(2) NOT NULL default '0',
  `WORK_ORDER_CURRENT_STATUS` int(2) NOT NULL default '0',
  `WORK_ORDER_CREATE_BY` int(11) NOT NULL default '0',
  `WORK_ORDER_ASSIGN_TO` int(11) NOT NULL default '0',
  `WORK_ORDER_SCOPE` varchar(200) NOT NULL default '',
  `WORK_ORDER_DESCRIPTION` text NOT NULL,
  `WORK_ORDER_COMMENT` text,
  `WORK_ORDER_CLOSE_DATE` int(20) default NULL,
  `WORK_ORDER_RESOLUTION` text,
  `WORK_ORDER_CLOSE_BY` int(4) default NULL,
  `LAST_ACTIVE` int(20) NOT NULL default '0',
  PRIMARY KEY  (`WORK_ORDER_ID`),
  KEY `WORK_ORDER_STATUS` (`WORK_ORDER_STATUS`),
  KEY `WORK_ORDER_CURRENT_STATUS` (`WORK_ORDER_CURRENT_STATUS`),
  KEY `WORK_ORDER_ASSIGN_TO` (`WORK_ORDER_ASSIGN_TO`),
  KEY `WORK_ORDER_CREATE_BY` (`WORK_ORDER_CREATE_BY`),
  KEY `WORK_ORDER_CLOSE_BY` (`WORK_ORDER_CLOSE_BY`),
  KEY `CUSTOMER_ID` (`CUSTOMER_ID`)
) ENGINE=MyISAM";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 	
	

function create_workorder_notes($db)
{
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_WORK_ORDER_NOTES` (
		`WORK_ORDER_NOTES_ID` int(11) NOT NULL auto_increment,
		`WORK_ORDER_ID` int(11) NOT NULL default '0',
		`WORK_ORDER_NOTES_DESCRIPTION` text NOT NULL,
		`WORK_ORDER_NOTES_ENTER_BY` varchar(128) NOT NULL default '',
		`WORK_ORDER_NOTES_DATE` varchar(255) NOT NULL default '',
		PRIMARY KEY  (`WORK_ORDER_NOTES_ID`),
		KEY `WORK_ORDER_ID` (`WORK_ORDER_ID`),
		KEY `WORK_ORDER_NOTES_ENTER_BY` (`WORK_ORDER_NOTES_ENTER_BY`)
		) ENGINE=MyISAM ";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 	

function creat_workorder_status($db)
{
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TABLE_WORK_ORDER_STATUS` (
		`WORK_ORDER_STATUS_ID` int(11) NOT NULL auto_increment,
		`WORK_ORDER_ID` int(11) NOT NULL default '0',
		`WORK_ORDER_STATUS_DATE` varchar(30) NOT NULL default '',
		`WORK_ORDER_STATUS_NOTES` text NOT NULL,
		`WORK_ORDER_STATUS_ENTER_BY` varchar(32) NOT NULL default '',
		PRIMARY KEY  (`WORK_ORDER_STATUS_ID`),
		KEY `WORK_ORDER_ID` (`WORK_ORDER_ID`),
		KEY `WORK_ORDER_STATUS_ENTER_BY` (`WORK_ORDER_STATUS_ENTER_BY`)
		) ENGINE=MyISAM ";
	
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
} 	

function create_table_tracker($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."TRACKER` (
		`id` int(20) NOT NULL auto_increment,
		`date` int(20) NOT NULL default '0',
		`ip` varchar(32) NOT NULL default '',
		`uagent` varchar(255) NOT NULL default '',
		`page` varchar(255) NOT NULL default '',
		`module` varchar(64) NOT NULL default '',
		`full_page` varchar(64) NOT NULL default '',
		`referer` varchar(64) NOT NULL default '',
		PRIMARY KEY  (`id`),
		KEY `date` (`date`,`ip`,`page`),
		KEY `module` (`module`,`full_page`)
		) ENGINE=MyISAM ";
	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
	
}
function drop_setup($db)
{
	$q="DROP TABLE IF EXISTS`".PRFX."SETUP`";

	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			return true;
		}
}

function create_setup($db) {
	$q = "CREATE TABLE IF NOT EXISTS `".PRFX."SETUP` (
  `OFFICE_HOUR_START` int(2) NOT NULL default '0',
  `OFFICE_HOUR_END` int(2) NOT NULL default '0',
  `AN_LOGIN_ID` varchar(255)NOT NULL default '',
  `AN_PASSWORD` varchar(255) NOT NULL default '',
  `AN_TRANS_KEY` varchar(255) NOT NULL default '',
  `PP_ID` varchar(255) NOT NULL default '',
  `HTML_PRINT` int(1) NOT NULL default '0',
  `PDF_PRINT` int(1) NOT NULL default '0',
  `INVOICE_TAX` decimal(10,3) NOT NULL default '0.000',
  `INV_THANK_YOU` varchar(255) NOT NULL default '',
  `WELCOME_NOTE` varchar(255) NOT NULL default '',
  `PARTS_LO` varchar(10) NOT NULL default '',
  `PARTS_LOGIN` varchar(60) NOT NULL default '',
  `PARTS_PASSWORD` varchar(60) NOT NULL default '',
  `SERVICE_CODE` varchar(10) NOT NULL default '',
  `PARTS_MARKUP` decimal(2,2) NOT NULL default '0.00',
  `PAYMATE_LOGIN` varchar(50) NOT NULL,
  `PAYMATE_PASSWORD` varchar(50) NOT NULL,
  `PAYMATE_FEES` decimal(2,1) NOT NULL default '1.5',
  `CHECK_PAYABLE` varchar(30) default NULL,
  `DD_NAME` varchar(50) default NULL,
  `DD_BANK` varchar(50) default NULL,
  `DD_BSB` varchar(50) default NULL,
  `DD_ACC` varchar(50) default NULL,
  `DD_INS` text default NULL,
  `INVOICE_NUMBER_START` varchar(10) default NULL,
  KEY `OFFICE_HOUR_START` (`OFFICE_HOUR_START`,`OFFICE_HOUR_END`)
) ENGINE=MyISAM ";
    $rs = $db->Execute($q);
        if(!$rs) {
            return false;
        } else {
            $q = "REPLACE INTO `".PRFX."SETUP` VALUES (7, 19, '', '', '', '', 1, 0,'0.0','','','','','','03','0.00','','','1.5','','','','','','Please use invoice number as transactions details. This helps us to determine who has paid in a timely manner.','')";
        
			if(!$rs = $db->Execute($q)) {
				return false;
			} else {
				return true;
		   }
		}

}	

function create_acl($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."ACL` (
 		 `ACL_ID` int(20) NOT NULL auto_increment,
  		`page` varchar(100),
  `Manager` int(2) NOT NULL default '1',
  `Supervisor` int(2) NOT NULL default '1',
  `Technician` int(2) NOT NULL default '1',
  `Admin` int(2) NOT NULL default '1',
  `Client` int(2) NOT NULL default '0',
   PRIMARY KEY  (`ACL_ID`),
   KEY `page` (`page`)
   ) ENGINE=MyISAM";

	$rs = $db->Execute($q);
		if(!$rs) {
			return false;
		} else {
			$q = "REPLACE INTO `".PRFX."ACL` VALUES 
(1, 'core:main', 1, 1, 1, 1, 0),
(2, 'customer:view', 1, 1, 1, 1, 0),
(3, 'customer:customer_details', 1, 1, 1, 1, 0),
(4, 'customer:edit', 1, 1, 1, 1, 0),
(5, 'customer:new', 1, 1, 1, 1, 0),
(6, 'core:error', 1, 1, 1, 1, 0),
(7, 'workorder:main', 1, 1, 1, 1, 0),
(8, 'workorder:new', 1, 1, 1, 1, 0),
(9, 'workorder:view', 1, 1, 1, 1, 0),
(10, 'workorder:new_note', 1, 1, 1, 1, 0),
(11, 'workorder:close', 1, 1, 1, 1, 0),
(12, 'workorder:print', 1, 1, 1, 1, 0),
(13, 'workorder:new_status', 1, 1, 1, 1, 0),
(14, 'schedule:main', 1, 1, 1, 1, 0),
(15, 'schedule:new', 1, 1, 1, 1, 0),
(16, 'invoice:new', 1, 1, 1, 1, 0),
(17, 'employees:main', 1, 1, 1, 1, 0),
(18, 'employees:employee_details', 1, 1, 1, 1, 0),
(19, 'employees:edit', 0, 0, 0, 1, 0),
(21, 'billing:new', 1, 1, 1, 1, 0),
(22, 'billing:proc_cash', 1, 1, 1, 1, 0),
(23, 'proc_check', 1, 1, 1, 1, 0),
(24, 'billing:proc_cc', 1, 1, 1, 1, 0),
(25, 'invoice:view', 1, 1, 1, 1, 0),
(26, 'invoice:print', 1, 1, 1, 1, 0),
(27, 'control:main', 0, 0, 0, 1, 0),
(28, 'control:company_edit', 0, 0, 0, 1, 0),
(29, 'control:hours_edit', 0, 0, 0, 1, 0),
(30, 'employees:new', 0, 0, 0, 1, 0),
(31, 'stats:hit_stats', 0, 0, 0, 1, 0),
(32, 'stats:main', 1, 1, 0, 1, 0),
(33, 'control:payment_options', 0, 0, 0, 1, 0),
(34, 'stats:hit_stats_view', 0, 0, 0, 1, 0),
(35, 'billing:proc_check', 1, 1, 1, 1, 0),
(36, 'invoice:view_paid', 1, 1, 1, 1, 0),
(37, 'invoice:view_unpaid', 1, 1, 1, 1, 0),
(38, 'workorder:view_closed', 1, 1, 1, 1, 0),
(39, 'control:acl', 0, 0, 0, 1, 0),
(40, 'control:import_parts', 0, 0, 0, 1, 0),
(41, 'parts:main', 1, 1, 1, 1, 0),
(42, 'parts:checkout', 1, 1, 1, 1, 0),
(43, 'schedule:sync', 1, 1, 1, 1, 0),
(44, 'schedule:view', 1, 1, 1, 1, 0),
(45, 'schedule:print', 1, 1, 1, 1, 0),
(46, 'schedule:edit ', 1, 1, 1, 1, 0),
(47, 'schedule:delete', 1, 1, 1, 1, 0),
(48, 'customer:delete', 1, 0, 0, 1, 0),
(49, 'control:edit_rate', 0, 0, 0, 1, 0),
(50, 'billing:proc_paypal', 1, 1, 1, 1, 0),
(51, 'billing:pp_complete', 1, 1, 1, 1, 0),
(52, 'parts:status', 1, 1, 1, 1, 0),
(53, 'parts:view', 1, 1, 1, 1, 0),
(54, 'parts:update', 1, 1, 1, 1, 0),
(55, 'billing:new_gift', 1, 1, 1, 1, 0),
(56, 'billing:proc_gift', 1, 1, 1, 1, 0),
(57, 'workorder:edit_description', 1, 1, 1, 1, 0),
(58, 'workorder:edit_comment', 1, 1, 1, 1, 0),
(59, 'control:check_updates', 0, 0, 0, 1, 0),
(60, 'parts:print_results', 1, 1, 1, 1, 0),
(61, 'customer:memo ', 1, 1, 1, 1, 0),
(62, 'control:backup ', 0, 0, 0, 1, 0),
(63, 'customer:directions', 1, 1, 1, 1, 0),
(64, 'invoice:epdf', 1, 1, 1, 1, 0),
(65, 'invoice:pdf', 1, 1, 1, 1, 0),
(66, 'billing:proc_deposit', 1, 1, 1, 1, 0),
(67, 'billing:paymate_deposit', 1, 1, 1, 1, 0),
(68, 'control:backup', 0, 0, 0, 1, 0),
(69, 'customer:email', 1, 1, 1, 1, 0),
(70, 'expense:new', 1, 1, 1, 1, 0),
(71, 'expense:search', 1, 1, 1, 1, 0),
(72, 'refund:new', 1, 1, 1, 1, 0),
(73, 'refund:search', 1, 1, 1, 1, 0),
(74, 'supplier:new', 1, 1, 1, 1, 0),
(75, 'supplier:search', 1, 1, 1, 1, 0),
(76, 'supplier:supplier_details', 1, 1, 1, 1, 0),
(77, 'expense:expense_details', 1, 1, 1, 1, 0),
(78, 'invoice:delete', 1, 1, 1, 1, 0),
(79, 'refund:refund_details', 1, 1, 1, 1, 0),
(80, 'refund:edit', 1, 1, 1, 1, 0),
(81, 'expense:edit', 1, 1, 1, 1, 0),
(82, 'supplier:edit', 1, 1, 1, 1, 0)";
        $rs = $db->Execute($q);
			if(!$rs) {
				return false;
			} else {
				return true;
			}

		}

}

function create_cart($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."CART` (
		`ID` int(20) NOT NULL auto_increment,
		`SKU` varchar(20) NOT NULL default '',
		`AMOUNT` int(5) NOT NULL default '0',
		`DESCRIPTION` varchar(255) NOT NULL default '',
		`VENDOR` varchar(255) NOT NULL default '',
		`ITEMID` varchar(100) NOT NULL default '',
		`PRICE` decimal(6,2) NOT NULL default '0.00',
		`Weight` varchar(20) NOT NULL default '',
		`SUB_TOTAL` double(6,2) NOT NULL default '0.00',
		`LAST` int(20) NOT NULL default '0',
		`ZIP` varchar(15) NOT NULL default '',
		`WO_ID` int(11) NOT NULL default '0',
		PRIMARY KEY  (`ID`),
		KEY `SKU` (`SKU`)
		) ENGINE=MyISAM";
	if(!$rs = $db->Execute($q)) {
		return false;
	} else {
		return true;
	}
}

function create_cat($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."CAT` (
	`ID` varchar(10) NOT NULL default '',
	`DESCRIPTION` varchar(100) NOT NULL default '',
	PRIMARY KEY  (`ID`),
	KEY `DESCRIPTION` (`DESCRIPTION`)
	) ENGINE=MyISAM";
	if(!$rs = $db->Execute($q)) {
		return false;
	} else {
		$q="INSERT IGNORE INTO ".PRFX."CAT VALUES
			('AC', 'Accessories'),
			('CB', 'Cables'),
			('CC', 'ControllerCards'),
			('CM', 'Cameras'),
			('CP', 'CPUs'),
			('CS', 'Cases'),
			('FD', 'FloppyDrives'),
			('FN', 'Fans'),
			('GP', 'GPS'),
			('HD', 'HardDrives'),
			('KB', 'Keyboards'),
			('MB', 'Motherboards'),
			('MC', 'Mice'),
			('MD', 'Modem'),
			('ME', 'Memory'),
			('MF', 'MemoryDevice'),
			('MM', 'Multimedia/MP3'),
			('MN', 'Monitors/LCD'),
			('NB', 'Notebooks/PDA'),
			('NT', 'Networking'),
			('OD', 'OpticalDrive'),
			('OM', 'OpticalMedia'),
			('PO', 'POSEquipment'),
			('PJ', 'Projector'),
			('PR', 'Printers'),
			('PS', 'PowerSupply'),
			('RD', 'RemovableDriveBay'),
			('RM', 'RemovableMedia'),
			('SC', 'Scanners'),
			('SF', 'Software'),
			('SO', 'SoundCards'),
			('SP', 'Speakers'),
			('SY', 'BareboneSystems'),
			('TB', 'TapeBack-up'),
			('UP', 'UPS'),
			('VC', 'VGACards'),
			('ZP', 'ZipDrive')";
			if(!$rs = $db->Execute($q)) {
				return false;
			} else {
			return true;
			}
	}
}

function create_orders($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."ORDERS` (
  `ORDER_ID` int(20) NOT NULL auto_increment,
  `INVOICE_ID` int(20) NOT NULL default '0',
  `WO_ID` int(20) NOT NULL default '0',
  `DATE_CREATE` int(20) NOT NULL default '0',
  `DATE_LAST` int(20) NOT NULL default '0',
  `SUB_TOTAL` decimal(6,2) NOT NULL default '0.00',
  `SHIPPING` decimal(6,2) NOT NULL default '0.00',
  `TOTAL` decimal(6,2) NOT NULL default '0.00',
  `WEIGHT` decimal(6,2) NOT NULL default '0.00',
  `ITEMS` int(4) NOT NULL default '0',
  `TRACKING_NO` varchar(60) NOT NULL default '',
  `STATUS` int(4) NOT NULL default '0',
  PRIMARY KEY  (`ORDER_ID`),
  KEY `INVOICE_ID` (`INVOICE_ID`,`WO_ID`,`STATUS`)
) ENGINE=MyISAM";
	if(!$rs = $db->Execute($q)) {
		return false;
	} else {
		return true;
	}
}

function create_order_details($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."ORDERS_DETAILS` (
  `DETAILS_ID` int(20) NOT NULL auto_increment,
  `ORDER_ID` int(20) NOT NULL default '0',
  `SKU` varchar(40) NOT NULL default '',
	`DESCRIPTION` VARCHAR( 255 ) NOT NULL,
	`VENDOR` VARCHAR( 100 ) NOT NULL,
  `COUNT` int(4) NOT NULL default '0',
  `PRICE` decimal(6,2) NOT NULL default '0.00',
  `SUB_TOTAL` decimal(6,2) NOT NULL default '0.00',
  PRIMARY KEY  (`DETAILS_ID`),
  KEY `ORDER_ID` (`ORDER_ID`,`SKU`)
) ENGINE=MyISAM";
	if(!$rs = $db->Execute($q)) {
		return false;
	} else {
		return true;
	}
}

function create_sub_cat($db) {
	$q="CREATE TABLE IF NOT EXISTS `".PRFX."SUB_CAT` (
  `ID` int(20) NOT NULL auto_increment,
  `CAT` varchar(10) NOT NULL default '',
  `DESCRIPTION` varchar(100) NOT NULL default '',
  `SUB_CATEGORY` varchar(10) NOT NULL default '',
  PRIMARY KEY  (`ID`),
  KEY `CAT` (`CAT`),
  KEY `SUB_CATEGORY` (`SUB_CATEGORY`)
) ENGINE=MyISAM";
	if(!$rs = $db->Execute($q)) {
		return false;
	} else {

$q="INSERT IGNORE INTO ".PRFX."SUB_CAT VALUES
(1, 'AC', 'PDADevice', 'PDA'),
(2, 'AC', 'MemoryHeatsink', 'MEH'),
(3, 'AC', 'PrinterAcces', 'PRA'),
(4, 'AC', 'Accessories', 'MBA'),
(5, 'AC', 'HardDrive', 'HDA'),
(6, 'AC', 'OtherCables', 'CB'),
(7, 'CB', 'InternalSCSI', 'CBS'),
(8, 'CB', 'InternalIDE', 'CBI'),
(9, 'CB', 'WDSecureconnect', 'CBW'),
(10, 'CB', 'ExternalUSB', 'CBU'),
(11, 'CC', 'I/OCards', 'IO'),
(12, 'CC', 'SCSIController', 'CCS'),
(13, 'CC', 'RAIDController', 'CCR'),
(14, 'CC', 'SerialATAController', 'CCA'),
(15, 'CM', 'Cameras', 'CAM'),
(16, 'CP', 'Pentium4S478', 'CPN'),
(17, 'CP', 'Pentium4S775/T', 'CPL'),
(18, 'CP', 'CeleronS478', 'CPC'),
(19, 'CP', 'CeleronS775/T', 'CPG'),
(20, 'CP', 'Xeon/XeonMP', 'CPX'),
(21, 'CP', 'Intel/BaniasMobile', 'CPB'),
(22, 'CP', 'AMDAthlon64S939/S754', 'CPF'),
(23, 'CP', 'AMDSempron', 'CPE'),
(24, 'CP', 'AMDOpteron', 'CPO'),
(25, 'CS', 'Mini-tower', 'CSM'),
(26, 'CS', 'Mid-tower', 'CST'),
(27, 'CS', 'FullTower', 'CSF'),
(28, 'CS', 'Desktop', 'CSD'),
(29, 'CS', 'Server', 'CSS'),
(30, 'CS', 'Booksize/FlexATX', 'CSB'),
(31, 'CS', 'Rackmount', 'CSR'),
(32, 'CS', 'ExternalChassis', 'CSE'),
(33, 'CS', 'SCSI-Terminators', 'TER'),
(34, 'CS', 'ExternalSCSI/NT/Print', 'CBE'),
(35, 'CS', 'HotSwapDriveKits', 'HSK'),
(36, 'FD', 'FloppyDrive', 'FD'),
(37, 'FN', 'HardDriveCooler', 'HFN'),
(38, 'FN', 'ChipsetCooler', 'CF7'),
(39, 'FN', 'XeonCPUFan', 'CFX'),
(40, 'FN', 'Pentium4Fan', 'CFN'),
(41, 'FN', 'Socket7/ACPUFan', 'CFS'),
(42, 'FN', 'Chassis/PowerSupplyFan', 'FAN'),
(43, 'FN', 'Celeron/K6Fan', 'CFC'),
(44, 'FN', 'PentiumII/IIIFan', 'CFP'),
(45, 'GP', 'GPS', 'GPS'),
(46, 'HD', 'IDEHardDisk', 'HDI'),
(47, 'HD', 'ExternalHardDisk', 'HDE'),
(48, 'HD', 'Notebook/MobileHD', 'HDM'),
(49, 'HD', 'SCSIHardDrive', 'HDS'),
(50, 'HD', 'SerialATAHardDrive', 'HDT'),
(51, 'KB', 'CordlessKeyboard', 'KBC'),
(52, 'KB', 'PS/2Keyboard', 'KBP'),
(53, 'KB', 'ATKeyboard', 'KBA'),
(54, 'KB', 'USBKeyboard', 'KBU'),
(55, 'KB', 'Keyboard/MiceCombo', 'KM'),
(56, 'MB', 'Socket775/T-P4', 'MBL'),
(57, 'MB', 'Socket478-P4', 'MBN'),
(58, 'MB', 'Socket370', 'MBC'),
(59, 'MB', 'Xeon', 'MBX'),
(60, 'MB', 'Socket939/940', 'MBF'),
(61, 'MB', 'Socket754', 'MB7'),
(62, 'MB', 'SocketA', 'MBT'),
(63, 'MB', 'Opteron', 'MBO'),
(64, 'MB', 'Accessories', 'AC'),
(65, 'MC', 'CordlessMouse', 'MCC'),
(66, 'MC', 'PS/2Mouse', 'MCP'),
(67, 'MC', 'USBMouse', 'MCU'),
(68, 'MC', 'SerialMouse', 'MCS'),
(69, 'MD', 'PCMCIA/CardBus', 'MDP'),
(70, 'MD', 'ExternalModem', 'MDE'),
(71, 'MD', 'InternalModem', 'MDI'),
(72, 'ME', 'RambusMemory', 'MER'),
(73, 'ME', 'SIMMMemory', 'MES'),
(74, 'ME', 'USBMemory', 'MEU'),
(75, 'ME', 'PCMCIA/CardBus', 'HDP'),
(76, 'ME', 'DIMMMemory', 'MED'),
(77, 'ME', 'DDRRegisteredDIMM', 'MD2'),
(78, 'ME', 'DDRUnbufferedDIMM', 'MD1'),
(79, 'ME', 'SODIMMDDR', 'MD3'),
(80, 'ME', 'SODIMMSDRAM', 'MD9'),
(81, 'ME', 'DDR2Unbuffered', 'MD4'),
(82, 'ME', 'DDR2Registered', 'MD5'),
(83, 'ME', 'DDR2SO-DIMM', 'MD6'),
(84, 'MF', 'PenDrive1.1+MP3', 'ME3'),
(85, 'MF', 'PenDrive1.1', 'ME1'),
(86, 'MF', 'PenDrive2.0', 'ME2'),
(87, 'MF', 'SmartCard', 'ME9'),
(88, 'MF', 'CompactFlash', 'ME7'),
(89, 'MF', 'SecureDigital', 'ME6'),
(90, 'MM', 'DigitalVideoConverter', 'DVC'),
(91, 'MM', 'MP3Players', 'MP3'),
(92, 'MM', 'MP3/FlashUSB1.1', 'ME3'),
(93, 'MM', 'MP4/FlashUSB2.0', 'ME4'),
(94, 'MN', 'LCD15inch', 'ML5'),
(95, 'MN', 'LCD17inch', 'ML7'),
(96, 'MN', 'LCD18inch', 'ML8'),
(97, 'MN', 'LCD19inch', 'ML9'),
(98, 'MN', 'LCD20inch+', 'ML1'),
(99, 'MN', 'LCD30inch+', 'ML3'),
(100, 'MN', 'LCD40inch+', 'ML4'),
(101, 'MN', 'LCDTV15inch', 'L15'),
(102, 'MN', 'LCDTV17inch', 'L17'),
(103, 'MN', 'LCDTV18inch', 'L18'),
(104, 'MN', 'LCDTV19inch', 'L19'),
(105, 'MN', 'LCDTV20inch', 'L20'),
(106, 'MN', 'LCDTV29inch', 'L29'),
(107, 'MN', 'LCDTV30inch+', 'L30'),
(108, 'MN', 'Monitors15inch', 'MN5'),
(109, 'MN', 'Monitors17inch', 'MN7'),
(110, 'MN', 'Monitors19inch', 'MN9'),
(111, 'MN', 'Monitors21inch', 'MN1'),
(112, 'MN', 'Monitors22inch', 'MN2'),
(113, 'MN', 'Monitors23inch+', 'MNL'),
(114, 'MN', '42inchPlasma', 'P42'),
(115, 'MN', '50inchPlasma', 'P50'),
(116, 'MN', 'MonitorAccessories', 'MNA'),
(117, 'NB', 'PentiumIIINotebook', 'NBP'),
(118, 'NB', 'Pentium4Notebook', 'NBN'),
(119, 'NB', 'NotebookAccessories', 'NBA'),
(120, 'NB', 'PentiumMNotebook', 'NBB'),
(121, 'NB', 'AMDNotebook', 'NBT'),
(122, 'NB', 'CeleronNotebook', 'NBC'),
(123, 'NB', 'TabletNotebookPC', 'TPC'),
(124, 'NB', 'K7Notebook', 'NBK'),
(125, 'NB', 'TabletNotebookPC', 'TPC'),
(126, 'NB', 'PDA', 'PDA'),
(127, 'NT', 'NetworkAtt.Storage', 'NAS'),
(128, 'NT', 'Switches', 'NTS'),
(129, 'NT', 'BluetoothWireless', 'BLT'),
(130, 'NT', 'NetworkAccessories', 'NTA'),
(131, 'NT', 'KVMProducts', 'KVM'),
(132, 'NT', 'Routers', 'NTR'),
(133, 'NT', 'Hubs', 'NTH'),
(134, 'NT', 'NetworkAdapters', 'NTC'),
(135, 'NT', 'InternetAppliance', 'IAP'),
(136, 'NT', 'WirelessNetworking', 'NTW'),
(137, 'NT', 'PCMCIA/CardBus', 'NTP'),
(138, 'NT', 'IDEController', 'CCI'),
(139, 'OD', 'DVD+/-RW', 'DVR'),
(140, 'OD', 'ComboCDRW/DVD', 'CDC'),
(141, 'OD', 'SlimDVD/RW', 'SDR'),
(142, 'OD', 'DVD-ROM', 'DVD'),
(143, 'OD', 'CDRW-Internal', 'CDW'),
(144, 'OD', 'CDRW-External', 'CDE'),
(145, 'OD', 'IDECD-ROM', 'CDI'),
(146, 'OD', 'SCSICD-ROM', 'CDS'),
(147, 'OD', 'DVDPlayer', 'DVP'),
(148, 'OD', 'DVD-RAMMedia', 'DVM'),
(149, 'OD', 'SlimCD-ROM', 'SCD'),
(150, 'OD', 'SlimCombo', 'SCO'),
(151, 'OD', 'SlimDVD-ROM', 'SDV'),
(152, 'PO', 'BarcodeHW', 'EQP'),
(153, 'PO', 'BarcodeSoftware', 'SFS'),
(154, 'PO', 'Printers', 'PRT'),
(155, 'PO', 'Scanners', 'SC'),
(156, 'PO', 'Accessories', 'AC'),
(157, 'PJ', 'Projector', 'PJ'),
(158, 'PR', 'Printers', 'PRT'),
(159, 'PS', 'PS/2PowerSupply', 'PSP'),
(160, 'PS', 'ATXPowerSupply', 'PSA'),
(161, 'PS', 'MicroATX', 'PSM'),
(162, 'PS', 'Redundant', 'PSR'),
(163, 'PS', 'ServerPowerSupply', 'PSS'),
(164, 'PS', 'NLXPowerSupply', 'PSN'),
(165, 'RD', 'ExternalRemovable', 'RME'),
(166, 'RM', 'RemovableStorageKit', 'RMM'),
(167, 'RM', 'RemovableStorage', 'RM'),
(168, 'SC', 'Scanners', 'SC'),
(169, 'SF', 'SinglePackSoftware', 'SFS'),
(170, 'SF', 'CDTitle', 'CDT'),
(171, 'SF', 'MultiplePackSoftware', 'SFP'),
(172, 'SF', 'Other', 'SF'),
(173, 'SO', 'SoundCard', 'SO'),
(174, 'SP', 'Speakers', 'SPK'),
(175, 'SP', 'Headphones', 'HED'),
(176, 'SY', 'AMDBarebone', 'SYK'),
(177, 'SY', 'PentiumIIIBarebone', 'SYP'),
(178, 'SY', 'TerminalSystems', 'SYT'),
(179, 'SY', 'Pentium4Barebone', 'SYN'),
(180, 'SY', 'CeleronBarebone', 'SYC'),
(181, 'SY', 'BoxandFoam', 'SYB'),
(182, 'SY', 'XeonBarebone', 'SYX'),
(183, 'TB', 'TapeBackupAcces', 'TBA'),
(184, 'TB', 'InternalTapeBackup', 'TBI'),
(185, 'TB', 'ExternalTapeBackup', 'TBE'),
(186, 'UP', 'UPS', 'UPS'),
(187, 'UP', 'SurgeProtection', 'SUR'),
(188, 'VC', 'VideoCard16MB', 'V16'),
(189, 'VC', 'VideoCard32MB', 'V32'),
(190, 'VC', 'VideoCard4MB', 'VC4'),
(191, 'VC', 'VideoCard256MB', 'V56'),
(192, 'VC', 'VideoCard512MB', 'V12'),
(193, 'VC', 'VideoCard128MB', 'V28'),
(194, 'VC', 'VidoeCard8MB', 'VC8'),
(195, 'VC', 'VideoCard96MB', 'V96'),
(196, 'VC', 'VideoCard64MB', 'V64'),
(197, 'VC', 'TVTunerCard', 'TVT'),
(198, 'ZP', 'IDEZipDrive', 'ZPI')";
if(!$rs = $db->Execute($q)) {
				return false;
			} else {
				return true;
			}
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ;
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1" ;
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

function create_country($db){
	$q = "CREATE TABLE IF NOT EXISTS `".PRFX."COUNTRY` (
	`code` char(3) NOT NULL default '',
	`name` varchar(80) NOT NULL default '',
	PRIMARY KEY  (`code`)
	) ENGINE=MyISAM";
	if(!$rs = $db->Execute($q)) {
		return false;
	} else {
		$q="REPLACE INTO `".PRFX."COUNTRY` VALUES
		('AF', 'Afghanistan'),
		('AL', 'Albania'),
		('DZ', 'Algeria'),
		('AS', 'American Samoa'),
		('AD', 'Andorra'),
		('AO', 'Angola'),
		('AI', 'Anguilla'),
		('AQ', 'Antarctica'),
		('AG', 'Antigua and Barbuda'),
		('AR', 'Argentina'),
		('AM', 'Armenia'),
		('AW', 'Aruba'),
		('AU', 'Australia'),
		('AT', 'Austria'),
		('AZ', 'Azerbaijan'), 
		('AP', 'Azores'), 
		('BS', 'Bahamas'), 
		('BH', 'Bahrain'), 
		('BD', 'Bangladesh'), 
		('BB', 'Barbados'), 
		('BY', 'Belarus'), 
		('BE', 'Belgium'), 
		('BZ', 'Belize'), 
		('BJ', 'Benin'), 
		('BM', 'Bermuda'), 
		('BT', 'Bhutan'), 
		('BO', 'Bolivia'), 
		('BA', 'Bosnia And Herzegowina'), 
		('XB', 'Bosnia-Herzegovina'), 
		('BW', 'Botswana'), 
		('BV', 'Bouvet Island'), 
		('BR', 'Brazil'), 
		('IO', 'British Indian Ocean Territory'), 
		('VG', 'British Virgin Islands'), 
		('BN', 'Brunei Darussalam'), 
		('BG', 'Bulgaria'), 
		('BF', 'Burkina Faso'), 
		('BI', 'Burundi'), 
		('KH', 'Cambodia'), 
		('CM', 'Cameroon'), 
		('CA', 'Canada'), 
		('CV', 'Cape Verde'), 
		('KY', 'Cayman Islands'), 
		('CF', 'Central African Republic'), 
		('TD', 'Chad'), 
		('CL', 'Chile'), 
		('CN', 'China'), 
		('CX', 'Christmas Island'), 
		('CC', 'Cocos (Keeling) Islands'), 
		('CO', 'Colombia'), 
		('KM', 'Comoros'), 
		('CG', 'Congo'), 
		('CD', 'Congo, The Democratic Republic O'), 
		('CK', 'Cook Islands'), 
		('XE', 'Corsica'), 
		('CR', 'Costa Rica'), 
		('CI', 'Cote d` Ivoire (Ivory Coast)'), 
		('HR', 'Croatia'), 
		('CU', 'Cuba'), 
		('CY', 'Cyprus'), 
		('CZ', 'Czech Republic'), 
		('DK', 'Denmark'), 
		('DJ', 'Djibouti'), 
		('DM', 'Dominica'), 
		('DO', 'Dominican Republic'), 
		('TP', 'East Timor'), 
		('EC', 'Ecuador'), 
		('EG', 'Egypt'), 
		('SV', 'El Salvador'), 
		('GQ', 'Equatorial Guinea'), 
		('ER', 'Eritrea'), 
		('EE', 'Estonia'), 
		('ET', 'Ethiopia'), 
		('FK', 'Falkland Islands (Malvinas)'), 
		('FO', 'Faroe Islands'), 
		('FJ', 'Fiji'), 
		('FI', 'Finland'), 
		('FR', 'France (Includes Monaco)'), 
		('FX', 'France, Metropolitan'), 
		('GF', 'French Guiana'), 
		('PF', 'French Polynesia'), 
		('TA', 'French Polynesia (Tahiti)'), 
		('TF', 'French Southern Territories'), 
		('GA', 'Gabon'), 
		('GM', 'Gambia'), 
		('GE', 'Georgia'), 
		('DE', 'Germany'), 
		('GH', 'Ghana'), 
		('GI', 'Gibraltar'), 
		('GR', 'Greece'), 
		('GL', 'Greenland'), 
		('GD', 'Grenada'), 
		('GP', 'Guadeloupe'), 
		('GU', 'Guam'), 
		('GT', 'Guatemala'), 
		('GN', 'Guinea'), 
		('GW', 'Guinea-Bissau'), 
		('GY', 'Guyana'), 
		('HT', 'Haiti'), 
		('HM', 'Heard And Mc Donald Islands'), 
		('VA', 'Holy See (Vatican City State)'), 
		('HN', 'Honduras'), 
		('HK', 'Hong Kong'), 
		('HU', 'Hungary'), 
		('IS', 'Iceland'), 
		('IN', 'India'), 
		('ID', 'Indonesia'), 
		('IR', 'Iran'), 
		('IQ', 'Iraq'), 
		('IE', 'Ireland'), 
		('EI', 'Ireland (Eire)'), 
		('IL', 'Israel'), 
		('IT', 'Italy'), 
		('JM', 'Jamaica'), 
		('JP', 'Japan'), 
		('JO', 'Jordan'), 
		('KZ', 'Kazakhstan'), 
		('KE', 'Kenya'), 
		('KI', 'Kiribati'), 
		('KP', 'Korea, Democratic People''S Repub'), 
		('KW', 'Kuwait'), 
		('KG', 'Kyrgyzstan'), 
		('LA', 'Laos'), 
		('LV', 'Latvia'), 
		('LB', 'Lebanon'), 
		('LS', 'Lesotho'), 
		('LR', 'Liberia'), 
		('LY', 'Libya'), 
		('LI', 'Liechtenstein'), 
		('LT', 'Lithuania'), 
		('LU', 'Luxembourg'), 
		('MO', 'Macao'), 
		('MK', 'Macedonia'), 
		('MG', 'Madagascar'), 
		('ME', 'Madeira Islands'), 
		('MW', 'Malawi'), 
		('MY', 'Malaysia'), 
		('MV', 'Maldives'), 
		('ML', 'Mali'), 
		('MT', 'Malta'), 
		('MH', 'Marshall Islands'), 
		('MQ', 'Martinique'), 
		('MR', 'Mauritania'), 
		('MU', 'Mauritius'), 
		('YT', 'Mayotte'), 
		('MX', 'Mexico'), 
		('FM', 'Micronesia, Federated States Of'), 
		('MD', 'Moldova, Republic Of'), 
		('MC', 'Monaco'), 
		('MN', 'Mongolia'), 
		('MS', 'Montserrat'), 
		('MA', 'Morocco'), 
		('MZ', 'Mozambique'), 
		('MM', 'Myanmar (Burma)'), 
		('NA', 'Namibia'), 
		('NR', 'Nauru'), 
		('NP', 'Nepal'), 
		('NL', 'Netherlands'), 
		('AN', 'Netherlands Antilles'), 
		('NC', 'New Caledonia'), 
		('NZ', 'New Zealand'), 
		('NI', 'Nicaragua'), 
		('NE', 'Niger'), 
		('NG', 'Nigeria'), 
		('NU', 'Niue'), 
		('NF', 'Norfolk Island'), 
		('MP', 'Northern Mariana Islands'), 
		('NO', 'Norway'), 
		('OM', 'Oman'), 
		('PK', 'Pakistan'), 
		('PW', 'Palau'), 
		('PS', 'Palestinian Territory, Occupied'), 
		('PA', 'Panama'), 
		('PG', 'Papua New Guinea'), 
		('PY', 'Paraguay'), 
		('PE', 'Peru'), 
		('PH', 'Philippines'), 
		('PN', 'Pitcairn'), 
		('PL', 'Poland'), 
		('PT', 'Portugal'), 
		('PR', 'Puerto Rico'), 
		('QA', 'Qatar'), 
		('RE', 'Reunion'), 
		('RO', 'Romania'), 
		('RU', 'Russian Federation'), 
		('RW', 'Rwanda'), 
		('KN', 'Saint Kitts And Nevis'), 
		('SM', 'San Marino'), 
		('ST', 'Sao Tome and Principe'), 
		('SA', 'Saudi Arabia'), 
		('SN', 'Senegal'), 
		('XS', 'Serbia-Montenegro'), 
		('SC', 'Seychelles'), 
		('SL', 'Sierra Leone'), 
		('SG', 'Singapore'), 
		('SK', 'Slovak Republic'), 
		('SI', 'Slovenia'), 
		('SB', 'Solomon Islands'), 
		('SO', 'Somalia'), 
		('ZA', 'South Africa'), 
		('GS', 'South Georgia And The South Sand'), 
		('KR', 'South Korea'), 
		('ES', 'Spain'), 
		('LK', 'Sri Lanka'), 
		('NV', 'St. Christopher and Nevis'), 
		('SH', 'St. Helena'), 
		('LC', 'St. Lucia'), 
		('PM', 'St. Pierre and Miquelon'), 
		('VC', 'St. Vincent and the Grenadines'), 
		('SD', 'Sudan'), 
		('SR', 'Suriname'), 
		('SJ', 'Svalbard And Jan Mayen Islands'), 
		('SZ', 'Swaziland'), 
		('SE', 'Sweden'), 
		('CH', 'Switzerland'), 
		('SY', 'Syrian Arab Republic'), 
		('TW', 'Taiwan'), 
		('TJ', 'Tajikistan'), 
		('TZ', 'Tanzania'), 
		('TH', 'Thailand'), 
		('TG', 'Togo'), 
		('TK', 'Tokelau'), 
		('TO', 'Tonga'), 
		('TT', 'Trinidad and Tobago'), 
		('XU', 'Tristan da Cunha'), 
		('TN', 'Tunisia'), 
		('TR', 'Turkey'), 
		('TM', 'Turkmenistan'), 
		('TC', 'Turks and Caicos Islands'), 
		('TV', 'Tuvalu'), 
		('UG', 'Uganda'), 
		('UA', 'Ukraine'), 
		('AE', 'United Arab Emirates'), 
		('UK', 'United Kingdom'), 
		('GB', 'Great Britain'), 
		('US', 'United States'), 
		('UM', 'United States Minor Outlying Isl'), 
		('UY', 'Uruguay'), 
		('UZ', 'Uzbekistan'), 
		('VU', 'Vanuatu'), 
		('XV', 'Vatican City'), 
		('VE', 'Venezuela'), 
		('VN', 'Vietnam'), 
		('VI', 'Virgin Islands (U.S.)'), 
		('WF', 'Wallis and Furuna Islands'), 
		('EH', 'Western Sahara'), 
		('WS', 'Western Samoa'), 
		('YE', 'Yemen'), 
		('YU', 'Yugoslavia'), 
		('ZR', 'Zaire'), 
		('ZM', 'Zambia'), 
		('ZW', 'Zimbabwe')";
		if(!$rs = $db->Execute($q)) {
			return false;
		} else {
			return true;
		}
	}

}
?>

