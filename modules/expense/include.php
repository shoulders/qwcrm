<?php

##########################################
#      QWcrm TAX Rate Call               #
##########################################

function tax_rate($db){

$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $tax_rate = $rs->fields['INVOICE_TAX'];
                return $tax_rate;
        }
}

##########################################
#      Last Record Look Up               #
##########################################

function last_record_id_lookup($db){

$q = 'SELECT * FROM '.PRFX.'TABLE_EXPENSE ORDER BY EXPENSE_ID DESC LIMIT 1';
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $last_record_id = $rs->fields['EXPENSE_ID'];
                return $last_record_id;
        }
}

##########################################
#      Insert New Record                 #
##########################################

function insert_new_expense($db,$VAR) {

    $checked_date = date_to_timestamp($VAR['expenseDate']);

//Remove Extra Slashes caused by Magic Quotes
$expenseNotes_string = $VAR['expenseNotes'];
$expenseNotes_string = stripslashes($expenseNotes_string);

$expenseItems_string = $VAR['expenseItems'];
$expenseItems_string = stripslashes($expenseItems_string);

    $sql = "INSERT INTO ".PRFX."TABLE_EXPENSE SET

            EXPENSE_ID              = ". $db->qstr( $VAR['expense_id']           ).",
            EXPENSE_PAYEE           = ". $db->qstr( $VAR['expensePayee']        ).",
            EXPENSE_DATE            = ". $db->qstr( $checked_date               ).",
            EXPENSE_TYPE            = ". $db->qstr( $VAR['expenseType']         ).",
            EXPENSE_PAYMENT_METHOD  = ". $db->qstr( $VAR['expensePaymentMethod']).",
            EXPENSE_NET_AMOUNT      = ". $db->qstr( $VAR['expenseNetAmount']    ).",
            EXPENSE_TAX_RATE        = ". $db->qstr( $VAR['expenseTaxRate']      ).",
            EXPENSE_TAX_AMOUNT      = ". $db->qstr( $VAR['expenseTaxAmount']    ).",
            EXPENSE_GROSS_AMOUNT    = ". $db->qstr( $VAR['expenseGrossAmount']  ).",
            EXPENSE_NOTES           = ". $db->qstr( $expenseNotes_string        ).",
            EXPENSE_ITEMS           = ". $db->qstr( $expenseItems_string        );

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } /*else {
        return true;
    } */
    
} 

#####################################
#     Edit - Load Record            #
#####################################

function edit_info($db, $expense_id){
    $sql = "SELECT * FROM ".PRFX."TABLE_EXPENSE WHERE EXPENSE_ID=".$db->qstr($expense_id);
    
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $row = $result->FetchRow();
        return $row;
    }
}

#####################################
#     Update Record              #
#####################################

function update_expense($db,$VAR) {

        $checked_date = date_to_timestamp($VAR['expenseDate']);

//Remove Extra Slashes caused by Magic Quotes
$expenseNotes_string = $VAR['expenseNotes'];
$expenseNotes_string = stripslashes($expenseNotes_string);

$expenseItems_string = $VAR['expenseItems'];
$expenseItems_string = stripslashes($expenseItems_string);

    $sql = "UPDATE ".PRFX."TABLE_EXPENSE SET

            EXPENSE_PAYEE            = ". $db->qstr( $VAR['expensePayee']        ).",
            EXPENSE_DATE            = ". $db->qstr( $checked_date               ).",
            EXPENSE_TYPE            = ". $db->qstr( $VAR['expenseType']         ).",
            EXPENSE_PAYMENT_METHOD          = ". $db->qstr( $VAR['expensePaymentMethod']).",
            EXPENSE_NET_AMOUNT        = ". $db->qstr( $VAR['expenseNetAmount']    ).",
            EXPENSE_TAX_RATE                = ". $db->qstr( $VAR['expenseTaxRate']      ).",
            EXPENSE_TAX_AMOUNT              = ". $db->qstr( $VAR['expenseTaxAmount']    ).",
            EXPENSE_GROSS_AMOUNT            = ". $db->qstr( $VAR['expenseGrossAmount']  ).",
            EXPENSE_NOTES                   = ". $db->qstr( $expenseNotes_string        ).",
            EXPENSE_ITEMS                   = ". $db->qstr( $expenseItems_string        )."
            WHERE EXPENSE_ID        = ". $db->qstr( $VAR['expense_id']           );
                        
            
    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
      return true;
    }
    
} 

#####################################
#    Delete Record                  #
#####################################

function delete_expense($db, $expense_id){
    $sql = "DELETE FROM ".PRFX."TABLE_EXPENSE WHERE EXPENSE_ID=".$db->qstr($expense_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;    
    } else {
        return true;
    }    
}

#####################################
#     Display Single Record         #
#####################################

function display_expense_info($db, $expense_id){

    $sql = "SELECT * FROM ".PRFX."TABLE_EXPENSE WHERE EXPENSE_ID=".$db->qstr($expense_id);

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $expense_array = array();
    }

    while($row = $result->FetchRow()){
         array_push($expense_array, $row);
    }

    return $expense_array;
}

##########################################
#          xml2php Gateway               #
# Loads language file up as a php array  #
##########################################

// might be redundant now - move to includes/includes
function gateway_xml2php($module) {
    global $smarty;

    
   $file = QWCRM_PHYSICAL_PATH.LANGUAGE_DIR.THEME_LANGUAGE;

   $xml_parser = xml_parser_create();
   if (!($fp = fopen($file, 'r'))) {
       die('unable to open XML');
   }
   $contents = fread($fp, filesize($file));
   fclose($fp);
   xml_parse_into_struct($xml_parser, $contents, $arr_vals);
   xml_parser_free($xml_parser);

   $xmlarray = array();

  foreach($arr_vals as $things){
        if($things['tag'] != 'TRANSLATE' && $things['value'] != "" ){

                    $ttag = strtolower($things['tag']);
                    $tvalue = $things['value'];

                    $xmlarray[$ttag]= $tvalue;
                      
        }
    }
 
    return $xmlarray;
}

######################################################
#                EXPENSE GATEWAY                     #
#      Manipulates search data for server submission #
######################################################

function expense_search_gateway($db, $expense_search_category, $expense_search_term) {
    // global $smarty;

           $langvals = gateway_xml2php('expense');

            switch ($expense_search_category) {

                   case "DATE": {
                   $expense_gateway_search_term = date_to_timestamp($expense_search_term);                   
                   return $expense_gateway_search_term;
                   break;
                   }

                   case "TYPE": {
                           switch ($expense_search_term) {

                             case ($langvals['expense_type_1']):
                                 $expense_gateway_search_term = "1";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_2']):
                                 $expense_gateway_search_term = "2";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_3']):
                                 $expense_gateway_search_term = "3";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_4']):
                                 $expense_gateway_search_term = "4";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_5']):
                                 $expense_gateway_search_term = "5";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_6']):
                                 $expense_gateway_search_term = "6";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_7']):
                                 $expense_gateway_search_term = "7";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_8']):
                                 $expense_gateway_search_term = "8";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_9']):
                                 $expense_gateway_search_term = "9";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_10']):
                                 $expense_gateway_search_term = "10";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_11']):
                                 $expense_gateway_search_term = "11";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_12']):
                                 $expense_gateway_search_term = "12";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_13']):
                                 $expense_gateway_search_term = "13";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_14']):
                                 $expense_gateway_search_term = "14";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_15']):
                                 $expense_gateway_search_term = "15";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_16']):
                                 $expense_gateway_search_term = "16";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_17']):
                                 $expense_gateway_search_term = "17";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_18']):
                                 $expense_gateway_search_term = "18";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_19']):
                                 $expense_gateway_search_term = "19";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_20']):
                                 $expense_gateway_search_term = "20";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_type_21']):
                                 $expense_gateway_search_term = "21";
                                 return $expense_gateway_search_term;
                                 break;

                           default:
                                  $expense_search_gateway = $expense_search_term;
                                  return $expense_search_gateway;
                                  break;
                                }
                             }

                  case "PAYMENT_METHOD": {
                           switch ($expense_search_term) {

                             case ($langvals['expense_payment_method_1']):
                                 $expense_gateway_search_term = "1";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_2']):
                                 $expense_gateway_search_term = "2";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_3']):
                                 $expense_gateway_search_term = "3";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_4']):
                                 $expense_gateway_search_term = "4";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_5']):
                                 $expense_gateway_search_term = "5";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_6']):
                                 $expense_gateway_search_term = "6";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_7']):
                                 $expense_gateway_search_term = "7";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_8']):
                                 $expense_gateway_search_term = "8";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_9']):
                                 $expense_gateway_search_term = "9";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_10']):
                                 $expense_gateway_search_term = "10";
                                 return $expense_gateway_search_term;
                                 break;

                             case ($langvals['expense_payment_method_11']):
                                 $expense_gateway_search_term = "11";
                                 return $expense_gateway_search_term;
                                 break;

                                }
                             }

                  default:
                      $expense_gateway_search_term = "%".$expense_search_term."%";
                      return $expense_gateway_search_term;
                      break;

               }
    }

######################################################
# Search - Also returns records for intial view page #
######################################################

function display_expense_search($db, $expense_search_category, $expense_search_term, $page_no, $smarty) {
    global $smarty;

    // Define the number of results per page
    $max_results = 25;

    // Figure out the limit for the Execute based
    // on the current page number.
    $from = (($page_no * $max_results) - $max_results);

    $sql = "SELECT * FROM ".PRFX."TABLE_EXPENSE WHERE EXPENSE_$expense_search_category LIKE '$expense_search_term' ORDER BY EXPENSE_ID DESC LIMIT $from, $max_results";

    //print $sql;

    if(!$result = $db->Execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $expense_search_result = array();
    }

    while($row = $result->FetchRow()){
         array_push($expense_search_result, $row);
    }

    // Figure out the total number of results in DB:
    $results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_EXPENSE WHERE EXPENSE_$expense_search_category LIKE ".$db->qstr("$expense_search_term") );

    if(!$total_results = $results->FetchRow()) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {
        $smarty->assign('total_results', $total_results['Num']);
    }

    // Figure out the total number of pages. Always round up using ceil()
    $total_pages = ceil($total_results["Num"] / $max_results);
    $smarty->assign('total_pages', $total_pages);

    // Assign the first page
    if($page_no > 1) {
        $prev = ($page_no - 1);
    }

    // Build Next Link
    if($page_no < $total_pages){
        $next = ($page_no + 1);
    }

    $smarty->assign('items', $items);
    $smarty->assign('page_no', $page_no);
    $smarty->assign('previous', $prev);
    $smarty->assign('next', $next);
    $smarty->assign('expense_search_category', $expense_search_category);
    $smarty->assign('expense_search_term', $expense_search_term);

    return $expense_search_result;
}