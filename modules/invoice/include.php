<?php
#########################################
# 	Display Open Invoice		#
#########################################

function display_open_invoice($db, $page_no, $smarty)
{

    global $smarty;

    // Define the number of results per page
    $max_results = 25;

    // Figure out the limit for the Execute based
    // on the current page number.
    $from = (($page_no * $max_results) - $max_results);

    $sql = "SELECT " . PRFX . "TABLE_INVOICE.*,
				" . PRFX . "TABLE_CUSTOMER. CUSTOMER_DISPLAY_NAME, CUSTOMER_ADDRESS, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP, CUSTOMER_PHONE, CUSTOMER_WORK_PHONE, CUSTOMER_MOBILE_PHONE, CUSTOMER_EMAIL, CUSTOMER_TYPE, CUSTOMER_FIRST_NAME, CUSTOMER_LAST_NAME, CREATE_DATE, LAST_ACTIVE ,
				" . PRFX . "TABLE_EMPLOYEE.*
			FROM " . PRFX . "TABLE_INVOICE
				LEFT JOIN " . PRFX . "TABLE_CUSTOMER ON (" . PRFX . "TABLE_INVOICE.CUSTOMER_ID = " . PRFX . "TABLE_CUSTOMER.CUSTOMER_ID)
				LEFT JOIN " . PRFX . "TABLE_EMPLOYEE ON (" . PRFX . "TABLE_INVOICE.EMPLOYEE_ID = " . PRFX . "TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE INVOICE_PAID=" . $db->qstr(0) . " ORDER BY INVOICE_ID DESC LIMIT $from, $max_results";

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $invoice_arr = $rs->GetArray();
    }
    // Figure out the total number of results in DB:
    $q = "SELECT COUNT(*) as Num FROM " . PRFX . "TABLE_INVOICE WHERE INVOICE_PAID=" . $db->qstr(0);

    if (!$results = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }


    if (!$total_results = $results->FetchRow()) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', $total_results['Num']);
    }

    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results);
    $smarty->assign('total_pages', $total_pages);

    // Assign the first page
    if ($page_no > 1) {
        $prev = ($page_no - 1);
    }

    // Build Next Link
    if ($page_no < $total_pages) {
        $next = ($page_no + 1);
    }


    $smarty->assign('name', $name);
    $smarty->assign('page_no', $page_no);
    $smarty->assign("previous", $prev);
    $smarty->assign("next", $next);
    return $invoice_arr;
}

########################################
# Paid Invoices	                       #
########################################

function display_paid_invoice($db, $page_no, $smarty)
{

    global $smarty;

    // Define the number of results per page
    $max_results = 25;

    // Figure out the limit for the Execute based
    // on the current page number.
    $from = (($page_no * $max_results) - $max_results);

    $sql = "SELECT " . PRFX . "TABLE_INVOICE.*,
				" . PRFX . "TABLE_CUSTOMER. CUSTOMER_DISPLAY_NAME, CUSTOMER_ADDRESS, CUSTOMER_CITY, CUSTOMER_STATE, CUSTOMER_ZIP, CUSTOMER_PHONE, CUSTOMER_WORK_PHONE, CUSTOMER_MOBILE_PHONE, CUSTOMER_EMAIL, CUSTOMER_TYPE, CUSTOMER_FIRST_NAME, CUSTOMER_LAST_NAME, CREATE_DATE, LAST_ACTIVE ,
				" . PRFX . "TABLE_EMPLOYEE.*
			FROM " . PRFX . "TABLE_INVOICE
				LEFT JOIN " . PRFX . "TABLE_CUSTOMER ON (" . PRFX . "TABLE_INVOICE.CUSTOMER_ID = " . PRFX . "TABLE_CUSTOMER.CUSTOMER_ID)
				LEFT JOIN " . PRFX . "TABLE_EMPLOYEE ON (" . PRFX . "TABLE_INVOICE.EMPLOYEE_ID = " . PRFX . "TABLE_EMPLOYEE.EMPLOYEE_ID)
			WHERE INVOICE_PAID=" . $db->qstr(1) . " ORDER BY INVOICE_ID DESC LIMIT $from, $max_results";

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $invoice_arr = $rs->GetArray();
    }

    // Figure out the total number of results in DB:
    $q = "SELECT COUNT(*) as Num FROM " . PRFX . "TABLE_INVOICE WHERE INVOICE_PAID=" . $db->qstr(1);
    if (!$results = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }


    if (!$total_results = $results->FetchRow()) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', $total_results['Num']);
    }

    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results);
    $smarty->assign('total_pages', $total_pages);

    // Assign the first page
    if ($page_no > 1) {
        $prev = ($page_no - 1);
    }

    // Build Next Link
    if ($page_no < $total_pages) {
        $next = ($page_no + 1);
    }


    $smarty->assign('name', $name);
    $smarty->assign('page_no', $page_no);
    $smarty->assign("previous", $prev);
    $smarty->assign("next", $next);
    return $invoice_arr;
}

##########################################
#          xml2php Gateway               #
# Loads language file up as a php array  #
##########################################

function gateway_xml2php($module)
{
    global $smarty;

    //$file = FILE_ROOT."language".SEP.$module.SEP.LANG ;
    $file = FILE_ROOT . "language" . SEP . LANG;

    $xml_parser = xml_parser_create();
    if (!($fp = fopen($file, 'r'))) {
        die('unable to open XML');
    }
    $contents = fread($fp, filesize($file));
    fclose($fp);
    xml_parse_into_struct($xml_parser, $contents, $arr_vals);
    xml_parser_free($xml_parser);

    $xmlarray = array();

    foreach ($arr_vals as $things) {
        if ($things['tag'] != 'TRANSLATE' && $things['value'] != "") {

            $ttag = strtolower($things['tag']);
            $tvalue = $things['value'];

            $xmlarray[$ttag] = $tvalue;

        }
    }

    return $xmlarray;
}

#####################################
#   Delete Labour Record            #
#####################################

$labourID = $VAR['labourID'];

function delete_labour_record($db, $labourID)
{
    $sql = "DELETE FROM " . PRFX . "TABLE_INVOICE_LABOR WHERE INVOICE_LABOR_ID=" . $db->qstr($labourID);

    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        return true;
    }
}

#####################################
#   Delete Parts Record             #
#####################################

function delete_parts_record($db, $partsID)
{
    $sql = "DELETE FROM " . PRFX . "TABLE_INVOICE_PARTS WHERE INVOICE_PARTS_ID=" . $db->qstr($partsID);


    if (!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        return true;
    }


}

#####################################
#   Delete Invoice                  #
#####################################

function delete_invoice($db, $invoice_id, $customer_id, $login)
{
      //Actual Deletion Function from Invoice Table
    $q = "DELETE FROM ".PRFX."TABLE_INVOICE WHERE INVOICE_ID=".$db->qstr($invoice_id);

    if (!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    } else {
        return true;
    }
    // TODO - Add transaction log to database
/*
    $q = "INSERT INTO ".PRFX."TABLE_TRANSACTION ( TRANSACTION_ID, DATE, TYPE, INVOICE_ID, WORKORDER_ID, CUSTOMER_ID, MEMO, AMOUNT ) VALUES,
         ( NULL, ".$db->qstr(time()).",'6',".$db->qstr($invoice_id).",'0',".$db->qstr($customer_id).",'Invoice Deleted By ".$db->qstr($login).",'0.00');";

    if (!$rs = $db->Execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: ' . $db->ErrorMsg() . '&menu=1&type=database');
        exit;
    }*/
    
}
#####################################
#   Sum Labour Sub Totals           #
#####################################

function labour_sub_total_sum($db, $invoiceID)
{
    $q = "SELECT SUM(INVOICE_LABOR_SUBTOTAL) AS labour_sub_total_sum FROM " . PRFX . "TABLE_INVOICE_LABOR WHERE INVOICE_ID=" . $db->qstr($invoiceID);
    if (!$rs = $db->Execute($q)) {
        echo 'Error: ' . $db->ErrorMsg();
        die;
    }
    $labour_sub_total_sum = $rs->fields['labour_sub_total_sum'];
    return $labour_sub_total_sum;
}

#####################################
#   Sum Parts Sub Total             #
#####################################

function parts_sub_total_sum($db, $invoiceID)
{
    $q = "SELECT SUM(INVOICE_PARTS_SUBTOTAL) AS parts_sub_total_sum FROM " . PRFX . "TABLE_INVOICE_PARTS WHERE INVOICE_ID=" . $db->qstr($invoiceID);
    if (!$rs = $db->Execute($q)) {
        echo 'Error: ' . $db->ErrorMsg();
        die;
    }
    $parts_sub_total_sum = $rs->fields['parts_sub_total_sum'];
    return $parts_sub_total_sum;
}

?>
