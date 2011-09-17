<?php

##########################################
#      MyITCRM TAX Rate Call             #
##########################################

function tax_rate($db){

$q = 'SELECT * FROM '.PRFX.'SETUP';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$tax_rate = $rs->fields['INVOICE_TAX'];
                return $tax_rate;
        }
}

##########################################
#      MyITCRM Date Format Call          #
##########################################

function date_format_call($db){

$q = 'SELECT * FROM '.PRFX.'TABLE_COMPANY';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$date_format = $rs->fields['COMPANY_DATE_FORMAT'];
                return $date_format;
        }
}

##########################################
#      Last Record Look Up               #
##########################################

function last_record_id_lookup($db){

$q = 'SELECT * FROM '.PRFX.'TABLE_REFUND ORDER BY REFUND_ID DESC LIMIT 1';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$last_record_id = $rs->fields['REFUND_ID'];
                return $last_record_id;
        }
}

##########################################
# Convert dates into Timestamp           #
# including UK to US date conversion     #
##########################################

  function date_to_timestamp($db, $check_date){

      $date_format = date_format_call($db);

      if
          //Change and clean UK date to US date format
            ($date_format == "%d/%m/%Y" || $date_format == "%d/%m/%y"){

                // Removes all non valid seperators and replaces them with a / (slash)
                    $newseparator = array('/', '/');
                    $oldseparator = array('-', 'g');
                    $cleaned_date = str_replace($oldseparator, $newseparator, $check_date);

                // Convert a UK date (DD/MM/YYYY) to a US date (MM/DD/YYYY) and vice versa
                    $aDate = split ("/", $cleaned_date);
                    $USdate = $aDate[1]."/".$aDate[0]."/".$aDate[2];

                // Converts date to string time
                    $timestamp = strtotime($USdate);
                    return $timestamp;

            }

        else if
            //If already US format just run string to time
              ($date_format == "%m/%d/%Y" || $date_format == "%m/%d/%y"){

                // Converts date to string time
                    $timestamp = strtotime($check_date);
                    return $timestamp;
            }
      
  }

##########################################
#     Timestamp to dates                 #
##########################################

function timestamp_to_date($db, $timestamp){

     $date_format = date_format_call($db);
     $formatted_date = date($date_format, $timestamp);

      switch($date_format)
      {
          case "%d/%m/%Y":
          $formatted_date = date("d/m/Y", $timestamp);
          return $formatted_date;
      
          case "%d/%m/%y":
          $formatted_date = date("d/m/y", $timestamp);
          return $formatted_date;
      
          case "%m/%d/%Y":
          $formatted_date = date("m/d/Y", $timestamp);
          return $formatted_date;
      
          case "%m/%d/%y":
          $formatted_date = date("m/d/y", $timestamp);
          return $formatted_date;
      }

}

##########################################
#      Insert New Record                 #
##########################################

function insert_new_refund($db,$VAR) {

    $checked_date = date_to_timestamp($db, $VAR['refundDate']);

//Remove Extra Slashes caused by Magic Quotes
$refundNotes_string = $VAR['refundNotes'];
$refundNotes_string = stripslashes($refundNotes_string);

$refundItems_string = $VAR['refundItems'];
$refundItems_string = stripslashes($refundItems_string);

	$sql = "INSERT INTO ".PRFX."TABLE_REFUND SET

			REFUND_ID			= ". $db->qstr( $VAR['refundID']           ).",
			REFUND_PAYEE			= ". $db->qstr( $VAR['refundPayee']        ).",
			REFUND_DATE			= ". $db->qstr( $checked_date               ).",
			REFUND_TYPE			= ". $db->qstr( $VAR['refundType']         ).",
			REFUND_PAYMENT_METHOD          = ". $db->qstr( $VAR['refundPaymentMethod']).",
			REFUND_NET_AMOUNT		= ". $db->qstr( $VAR['refundNetAmount']    ).",
                        REFUND_TAX_RATE                = ". $db->qstr( $VAR['refundTaxRate']      ).",
                        REFUND_TAX_AMOUNT              = ". $db->qstr( $VAR['refundTaxAmount']    ).",
                        REFUND_GROSS_AMOUNT            = ". $db->qstr( $VAR['refundGrossAmount']  ).",
                        REFUND_NOTES                   = ". $db->qstr( $refundNotes_string        ).",
                        REFUND_ITEMS                   = ". $db->qstr( $refundItems_string        );

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

function edit_info($db, $refundID){
	$sql = "SELECT * FROM ".PRFX."TABLE_REFUND WHERE REFUND_ID=".$db->qstr($refundID);
	
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$row = $result->FetchRow();
		return $row;
	}
}

#####################################
#	 Update Record              #
#####################################

function update_refund($db,$VAR) {

        $checked_date = date_to_timestamp($db, $VAR['refundDate']);

//Remove Extra Slashes caused by Magic Quotes
$refundNotes_string = $VAR['refundNotes'];
$refundNotes_string = stripslashes($refundNotes_string);

$refundItems_string = $VAR['refundItems'];
$refundItems_string = stripslashes($refundItems_string);

	$sql = "UPDATE ".PRFX."TABLE_REFUND SET

			REFUND_PAYEE			= ". $db->qstr( $VAR['refundPayee']        ).",
			REFUND_DATE			= ". $db->qstr( $checked_date               ).",
			REFUND_TYPE			= ". $db->qstr( $VAR['refundType']         ).",
			REFUND_PAYMENT_METHOD          = ". $db->qstr( $VAR['refundPaymentMethod']).",
			REFUND_NET_AMOUNT		= ". $db->qstr( $VAR['refundNetAmount']    ).",
                        REFUND_TAX_RATE                = ". $db->qstr( $VAR['refundTaxRate']      ).",
                        REFUND_TAX_AMOUNT              = ". $db->qstr( $VAR['refundTaxAmount']    ).",
                        REFUND_GROSS_AMOUNT            = ". $db->qstr( $VAR['refundGrossAmount']  ).",
                        REFUND_NOTES                   = ". $db->qstr( $refundNotes_string        ).",
                        REFUND_ITEMS                   = ". $db->qstr( $refundItems_string        )."
                        WHERE REFUND_ID		= ". $db->qstr( $VAR['refundID']           );
                        
			
	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
    } else {
      return true;
    }
	
} 

#####################################
#	Delete Record               #
#####################################

function delete_refund($db, $refundID){
	$sql = "DELETE FROM ".PRFX."TABLE_REFUND WHERE REFUND_ID=".$db->qstr($refundID);
	
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

function display_refund_info($db, $refundID){

	$sql = "SELECT * FROM ".PRFX."TABLE_REFUND WHERE REFUND_ID=".$db->qstr($refundID);

	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$refund_array = array();
	}

	while($row = $result->FetchRow()){
		 array_push($refund_array, $row);
	}

	return $refund_array;
}

##########################################
#          xml2php Gateway               #
# Loads language file up as a php array  #
##########################################

function gateway_xml2php($module) {
	global $smarty;

	//$file = FILE_ROOT."language".SEP.$module.SEP.LANG ;
        $file = FILE_ROOT."language".SEP.LANG ;

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
#                REFUND GATEWAY                      #
#      Manipulates search data for server submission #
######################################################

function refund_search_gateway($db, $refund_search_category, $refund_search_term) {
	// global $smarty;

           $langvals = gateway_xml2php('refund');

            switch ($refund_search_category) {

                   case "DATE": {
                   $refund_gateway_search_term = date_to_timestamp($db, $refund_search_term);
                   return $refund_gateway_search_term;
                   break;
                   }

                   case "TYPE": {
                           switch ($refund_search_term) {

                             case ($langvals['refund_type_1']):
                                 $refund_gateway_search_term = "1";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_type_2']):
                                 $refund_gateway_search_term = "2";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_type_3']):
                                 $refund_gateway_search_term = "3";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_type_4']):
                                 $refund_gateway_search_term = "4";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_type_5']):
                                 $refund_gateway_search_term = "5";
                                 return $refund_gateway_search_term;
                                 break;

                           default:
                                  $refund_search_gateway = $refund_search_term;
                                  return $refund_search_gateway;
                                  break;
                                }
                             }

                  case "PAYMENT_METHOD": {
                           switch ($refund_search_term) {

                             case ($langvals['refund_payment_method_1']):
                                 $refund_gateway_search_term = "1";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_2']):
                                 $refund_gateway_search_term = "2";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_3']):
                                 $refund_gateway_search_term = "3";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_4']):
                                 $refund_gateway_search_term = "4";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_5']):
                                 $refund_gateway_search_term = "5";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_6']):
                                 $refund_gateway_search_term = "6";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_7']):
                                 $refund_gateway_search_term = "7";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_8']):
                                 $refund_gateway_search_term = "8";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_9']):
                                 $refund_gateway_search_term = "9";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_10']):
                                 $refund_gateway_search_term = "10";
                                 return $refund_gateway_search_term;
                                 break;

                             case ($langvals['refund_payment_method_11']):
                                 $refund_gateway_search_term = "11";
                                 return $refund_gateway_search_term;
                                 break;

                                }
                             }

                  default:
                      $refund_gateway_search_term = "%".$refund_search_term."%";
                      return $refund_gateway_search_term;
                      break;

               }
    }

######################################################
# Search - Also returns records for intial view page #
######################################################

function display_refund_search($db, $refund_search_category, $refund_search_term, $page_no, $smarty) {
	global $smarty;

	// Define the number of results per page
	$max_results = 25;

	// Figure out the limit for the Execute based
	// on the current page number.
	$from = (($page_no * $max_results) - $max_results);

	$sql = "SELECT * FROM ".PRFX."TABLE_REFUND WHERE REFUND_$refund_search_category LIKE '$refund_search_term' ORDER BY REFUND_ID DESC LIMIT $from, $max_results";

	//print $sql;

	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$refund_search_result = array();
	}

	while($row = $result->FetchRow()){
		 array_push($refund_search_result, $row);
	}

	// Figure out the total number of results in DB:
	$results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_REFUND WHERE REFUND_$refund_search_category LIKE ".$db->qstr("$refund_search_term") );

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
        $smarty->assign('refund_search_category', $refund_search_category);
        $smarty->assign('refund_search_term', $refund_search_term);

	return $refund_search_result;
}

?>
