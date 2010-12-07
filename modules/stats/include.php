<?php

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

?>
