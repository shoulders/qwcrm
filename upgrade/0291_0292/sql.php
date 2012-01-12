<?php
///Additions to ACL table required.
if (!create_acl($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."ACL</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."ACL</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

function create_acl($db)
{

    $q = "REPLACE INTO `".PRFX."ACL` VALUES
            (78, 'invoice:delete', 1, 1, 1, 1, 0),
            (79, 'refund:refund_details', 1, 1, 1, 1, 0),
            (80, 'refund:edit', 1, 1, 1, 1, 0),
            (81, 'expense:edit', 1, 1, 1, 1, 0),
            (82, 'supplier:edit', 1, 1, 1, 1, 0)";

    $rs = $db->Execute($q);
    if (!$rs) {
        return false;
    } else {
        return true;
    }

}

// Fixing up tax rates decimals
if (!update_invoice_tax($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_INVOICE</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."TABLE_INVOICE</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

function update_invoice_tax($db)
{

    $q = "ALTER TABLE `".PRFX."TABLE_INVOICE` CHANGE `TAX` `TAX` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0.000'";

    $rs = $db->Execute($q);
    if (!$rs) {
        return false;
    } else {
        return true;
    }

}

if (!update_invoice_tax_setup($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."SETUP</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."SETUP</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

function update_invoice_tax_setup($db)
{

    $q = "ALTER TABLE `".PRFX."SETUP` CHANGE `INVOICE_TAX` `INVOICE_TAX` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0.000'";

    $rs = $db->Execute($q);
    if (!$rs) {
        return false;
    } else {
        return true;
    }

}

if (!add_tax_and_discounted_rates_to_invoice_table($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_INVOICE</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."TABLE_INVOICE</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

function add_tax_and_discounted_rates_to_invoice_table($db)
{
    
    $q = "ALTER TABLE `".PRFX."TABLE_INVOICE` ADD `TAX_RATE` DECIMAL( 10, 3 ) NOT NULL DEFAULT '0.000' ,
            ADD `DISCOUNT_APPLIED` DECIMAL( 10, 3 ) NOT NULL";
    $rs = $db->Execute($q);
    if (!$rs) {
        return true;
    } else {
        return true;
    }

}

// Rename Tracker as this was broken before and displaying errors
if (!rename_tracker($db)) {
    echo("<tr>\n
			<td>RENAMED TABLE ".PRFX."tracker TO TRACKER</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>RENAMED TABLE ".PRFX."tracker TO TRACKER</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}

function rename_tracker($db)
{

    $q = "RENAME TABLE `".PRFX."tracker` TO `".PRFX."TRACKER_NEW`,
        `".PRFX."TRACKER_NEW` TO `".PRFX."TRACKER`";
    $rs = $db->Execute($q);
    if (!$rs) {
        return true;
    } else {
        return true;
    }

}

// Update Customers Phone Numbers to hold more then 20 characters.
// Customers Phone
if (!update_customer_phone($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_CUSTOMER_PHONE</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."TABLE_CUSTOMER_PHONE</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}


function update_customer_phone($db)
{

    $q = "ALTER TABLE `".PRFX."TABLE_CUSTOMER` CHANGE `CUSTOMER_PHONE` `CUSTOMER_PHONE` VARCHAR(40)";

    $rs = $db->Execute($q);
    if (!$rs) {
        return false;
    } else {
        return true;
    }

}

// Customers Work Phone
if (!update_customer_work_phone($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_CUSTOMER_WORK_PHONE</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."TABLE_CUSTOMER_WORK_PHONE</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}


function update_customer_work_phone($db)
{

    $q = "ALTER TABLE `".PRFX."TABLE_CUSTOMER` CHANGE `CUSTOMER_WORK_PHONE` `CUSTOMER_WORK_PHONE` VARCHAR(40)";

    $rs = $db->Execute($q);
    if (!$rs) {
        return false;
    } else {
        return true;
    }

}

// Customers Mobile Phone
if (!update_customer_mobile_phone($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."TABLE_CUSTOMER_MOBILE_PHONE</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."TABLE_CUSTOMER_MOBILE_PHONE</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}


function update_customer_mobile_phone($db)
{

    $q = "ALTER TABLE `".PRFX."TABLE_CUSTOMER` CHANGE `CUSTOMER_MOBILE_PHONE` `CUSTOMER_MOBILE_PHONE` VARCHAR(40)";

    $rs = $db->Execute($q);
    if (!$rs) {
        return false;
    } else {
        return true;
    }

}

// Adding Version Number to Database
if (!create_version_table($db)) {
    echo("<tr>\n
			<td>CREATED TABLE ".PRFX."VERSION</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>CREATED TABLE ".PRFX."VERSION</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}


// CREATING VERSION NUMBER TABLE
function create_version_table($db)
{
    $q = "CREATE TABLE IF NOT EXISTS `".PRFX."VERSION` (`VERSION_ID` INT NOT NULL ,`VERSION_NAME` VARCHAR( 10 ) NOT NULL ,`VERSION_INSTALLED` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP ) ENGINE=MyISAM ";
    if (!$rs = $db->execute($q)) {
        return false;
    } else {
        return true;
    }

}

if (!insert_version_values($db)) {
    echo("<tr>\n
			<td>UPDATED TABLE ".PRFX."VERSION</td>\n
			<td><font color=\"red\"><b>Failed:</b></font> " . $db->ErrorMsg() . "</td>\n
		</tr>\n");
    $error_flag = true;
} else {
    echo("<tr>\n
				<td>UPDATED TABLE ".PRFX."VERSION</td>\n
				<td><font color=\"green\"><b>OK</b></font></td>\n
			</tr>\n");
}
// ADDING VERSION NUMBER TO DATABASE
function insert_version_values($db)
{
    //Insert New Records for version table
    $q = "INSERT INTO `".PRFX."VERSION` (`VERSION_ID`, `VERSION_NAME`) VALUES ('292', '0.2.9.2')";

    if (!$rs = $db->Execute($q)) {
        return false;
    } else {
        return true;
    }

}

?>

