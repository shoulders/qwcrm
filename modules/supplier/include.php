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

$q = 'SELECT * FROM '.PRFX.'TABLE_SUPPLIER ORDER BY SUPPLIER_ID DESC LIMIT 1';
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$last_record_id = $rs->fields['SUPPLIER_ID'];
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

function insert_new_supplier($db,$VAR) {

//Remove Extra Slashes caused by Magic Quotes
$supplierNotes_string = $VAR['supplierNotes'];
$supplierNotes_string = stripslashes($supplierNotes_string);

$supplierItems_string = $VAR['supplierItems'];
$supplierItems_string = stripslashes($supplierItems_string);

	$sql = "INSERT INTO ".PRFX."TABLE_SUPPLIER SET

			SUPPLIER_ID			= ". $db->qstr( $VAR['supplierID']          ).",
			SUPPLIER_NAME			= ". $db->qstr( $VAR['supplierName']        ).",
			SUPPLIER_CONTACT		= ". $db->qstr( $VAR['supplierContact']     ).",
			SUPPLIER_TYPE			= ". $db->qstr( $VAR['supplierType']        ).",
			SUPPLIER_PHONE                  = ". $db->qstr( $VAR['supplierPhone']       ).",
			SUPPLIER_FAX                    = ". $db->qstr( $VAR['supplierFax']         ).",
                        SUPPLIER_MOBILE                 = ". $db->qstr( $VAR['supplierMobile']      ).",
                        SUPPLIER_WWW                    = ". $db->qstr( $VAR['supplierWww']         ).",
                        SUPPLIER_EMAIL                  = ". $db->qstr( $VAR['supplierEmail']       ).",
                        SUPPLIER_ADDRESS                = ". $db->qstr( $VAR['supplierAddress']     ).",
                        SUPPLIER_CITY                   = ". $db->qstr( $VAR['supplierCity']        ).",
                        SUPPLIER_STATE                  = ". $db->qstr( $VAR['supplierState']       ).",
                        SUPPLIER_ZIP                    = ". $db->qstr( $VAR['supplierZip']         ).",
                        SUPPLIER_NOTES                  = ". $db->qstr( $supplierNotes_string       ).",
                        SUPPLIER_DESCRIPTION            = ". $db->qstr( $supplierDescription_string );

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

function edit_info($db, $supplierID){
	$sql = "SELECT * FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_ID=".$db->qstr($supplierID);
	
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

function update_supplier($db,$VAR) {

        $checked_date = date_to_timestamp($db, $VAR['supplierDate']);

//Remove Extra Slashes caused by Magic Quotes
$supplierAddress_string = $VAR['supplierAddress'];
$supplierAddress_string = stripslashes($supplierAddress_string);

$supplierNotes_string = $VAR['supplierNotes'];
$supplierNotes_string = stripslashes($supplierNotes_string);

$supplierDescription_string = $VAR['supplierDescription'];
$supplierDescription_string = stripslashes($supplierDescription_string);

	$sql = "UPDATE ".PRFX."TABLE_SUPPLIER SET

			SUPPLIER_NAME			= ". $db->qstr( $VAR['supplierName']        ).",
			SUPPLIER_CONTACT		= ". $db->qstr( $VAR['supplierContact']     ).",
			SUPPLIER_TYPE			= ". $db->qstr( $VAR['supplierType']        ).",
			SUPPLIER_PHONE                  = ". $db->qstr( $VAR['supplierPhone']       ).",
			SUPPLIER_FAX                    = ". $db->qstr( $VAR['supplierFax']         ).",
                        SUPPLIER_MOBILE                 = ". $db->qstr( $VAR['supplierMobile']      ).",
                        SUPPLIER_WWW                    = ". $db->qstr( $VAR['supplierWww']         ).",
                        SUPPLIER_EMAIL                  = ". $db->qstr( $VAR['supplierEmail']       ).",
                        SUPPLIER_ADDRESS                = ". $db->qstr( $supplierAddress_string     ).",
                        SUPPLIER_CITY                   = ". $db->qstr( $VAR['supplierCity']        ).",
                        SUPPLIER_STATE                  = ". $db->qstr( $VAR['supplierState']       ).",
                        SUPPLIER_ZIP                    = ". $db->qstr( $VAR['supplierZip']         ).",
                        SUPPLIER_NOTES                  = ". $db->qstr( $supplierNotes_string       ).",
                        SUPPLIER_DESCRIPTION            = ". $db->qstr( $supplierDescription_string )."
                        WHERE SUPPLIER_ID		= ". $db->qstr( $VAR['supplierID']          );                        
			
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

function delete_supplier($db, $supplierID){
	$sql = "DELETE FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_ID=".$db->qstr($supplierID);
	
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

function display_supplier_info($db, $supplierID){

	$sql = "SELECT * FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_ID=".$db->qstr($supplierID);

	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$supplier_array = array();
	}

	while($row = $result->FetchRow()){
		 array_push($supplier_array, $row);
	}

	return $supplier_array;
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
#                SUPPLIER GATEWAY                     #
#      Manipulates search data for server submission #
######################################################

function supplier_search_gateway($db, $supplier_search_category, $supplier_search_term) {
	// global $smarty;

           $langvals = gateway_xml2php('supplier');

            switch ($supplier_search_category) {
                   
                   case "TYPE": {
                           switch ($supplier_search_term) {

                             case ($langvals['supplier_type_1']):
                                 $supplier_gateway_search_term = "1";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_2']):
                                 $supplier_gateway_search_term = "2";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_3']):
                                 $supplier_gateway_search_term = "3";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_4']):
                                 $supplier_gateway_search_term = "4";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_5']):
                                 $supplier_gateway_search_term = "5";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_6']):
                                 $supplier_gateway_search_term = "6";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_7']):
                                 $supplier_gateway_search_term = "7";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_8']):
                                 $supplier_gateway_search_term = "8";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_9']):
                                 $supplier_gateway_search_term = "9";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_10']):
                                 $supplier_gateway_search_term = "10";
                                 return $supplier_gateway_search_term;
                                 break;

                             case ($langvals['supplier_type_11']):
                                 $supplier_gateway_search_term = "11";
                                 return $supplier_gateway_search_term;
                                 break;

                                }
                             }

                  default:
                      $supplier_gateway_search_term = "%".$supplier_search_term."%";
                      return $supplier_gateway_search_term;
                      break;

               }
    }

######################################################
# Search - Also returns records for intial view page #
######################################################

function display_supplier_search($db, $supplier_search_category, $supplier_search_term, $page_no, $smarty) {
	global $smarty;

	// Define the number of results per page
	$max_results = 25;

	// Figure out the limit for the Execute based
	// on the current page number.
	$from = (($page_no * $max_results) - $max_results);

	$sql = "SELECT * FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_$supplier_search_category LIKE '$supplier_search_term' ORDER BY SUPPLIER_ID LIMIT $from, $max_results";

	//print $sql;

	if(!$result = $db->Execute($sql)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	} else {
		$supplier_search_result = array();
	}

	while($row = $result->FetchRow()){
		 array_push($supplier_search_result, $row);
	}

	// Figure out the total number of results in DB:
	$results = $db->Execute("SELECT COUNT(*) as Num FROM ".PRFX."TABLE_SUPPLIER WHERE SUPPLIER_$supplier_search_category LIKE ".$db->qstr("$supplier_search_term") );

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
        $smarty->assign('supplier_search_category', $supplier_search_category);
        $smarty->assign('supplier_search_term', $supplier_search_term);

	return $supplier_search_result;
}

?>
