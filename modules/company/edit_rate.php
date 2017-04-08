<?php

if(isset($VAR['upload'])&& $_FILES['userfile']['size'] > 0 ){

// check extension for csv
$fname = $_FILES['userfile']['name'];
$chk_ext = explode(".",$fname);
if(strtolower($chk_ext[1]) == "csv"){
    // nothing here
} else {
    force_page('core', 'error&error_msg=Error: Only CSV files accepted');
    exit;    
}

// Open CSV file
$filename = $_FILES['userfile']['tmp_name'];
$handle = fopen($filename, 'r');

// Read CSV data and insert into database
while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {

    $sql = "INSERT INTO ".PRFX."TABLE_LABOR_RATE(LABOR_RATE_NAME,LABOR_RATE_AMOUNT,LABOR_RATE_COST,LABOR_RATE_ACTIVE,LABOR_TYPE,LABOR_MANUF) VALUES ('$data[1]','$data[2]','$data[3]','$data[4]','$data[5]','$data[6]')";

    if(!$rs = $db->execute($sql)) {
    force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
    exit;}
}

// Close CSV file
fclose($handle);

}
// Now if we edit/add a new item
if(isset($VAR['submit'])) {
    
    // edit rate
    if($VAR['submit'] == 'Edit') {            
        $sql = "UPDATE ".PRFX."TABLE_LABOR_RATE SET
                LABOR_RATE_NAME     =". $db->qstr( $VAR['display']      ).",
                LABOR_RATE_AMOUNT   =". $db->qstr( $VAR['amount']       ).",
                LABOR_RATE_COST     =". $db->qstr( $VAR['cost']         ).",
                LABOR_RATE_ACTIVE   =". $db->qstr( $VAR['active']       ).",
                LABOR_TYPE          =". $db->qstr( $VAR['type']         ).",
                LABOR_MANUF         =". $db->qstr( $VAR['manufacturer'] )."
                WHERE LABOR_RATE_ID =". $db->qstr( $VAR['id']           );
        
        if(!$rs = $db->execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        } 
        
    }
    
    // delete rate
    if($VAR['submit'] == 'Delete') {
        $sql = "DELETE FROM ".PRFX."TABLE_LABOR_RATE WHERE LABOR_RATE_ID =".$db->qstr($VAR['id']);
        
        if(!$rs = $db->execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }

    }

    // New Rate
    if($VAR['submit'] == 'New') {
        $sql = "INSERT INTO ".PRFX."TABLE_LABOR_RATE SET
                LABOR_RATE_NAME     =". $db->qstr( $VAR['display']      ).",
                LABOR_RATE_AMOUNT   =". $db->qstr( $VAR['amount']       ).",
                LABOR_RATE_COST     =". $db->qstr( $VAR['cost']         ).",
                LABOR_TYPE          =". $db->qstr( $VAR['type']         ).",
                LABOR_MANUF         =". $db->qstr( $VAR['manufacturer'] ).",
                LABOR_RATE_ACTIVE   =". $db->qstr( 1                    );
        
        if(!$rs = $db->execute($sql)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }
    }

    // Reload rates page
    force_page('company', 'edit_rate&page_title=Edit Billing Rates');
    exit;
    
} else {
    
    // Loads rates from database
    $sql = "SELECT * FROM ".PRFX."TABLE_LABOR_RATE ORDER BY LABOR_RATE_ID ASC";
    
    if(!$rs = $db->execute($sql)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }    
    $smarty->assign('rate', $rs->GetArray());
    
    // Fetch Page    
    $BuildPage .= $smarty->fetch('company/edit_rate.tpl');
    
}