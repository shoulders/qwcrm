<?php

##########################################
#      Get Start and End Times       #
##########################################

function get_company_start_end_times($db, $time_event) {
    
    $q = 'SELECT OPENING_HOUR, OPENING_MINUTE, CLOSING_HOUR, CLOSING_MINUTE FROM '.PRFX.'SETUP';

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error','error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    }
    $companyTime = $rs->GetArray();
    
    // return opening time in correct format for smarty time builder
    if($time_event == 'opening_time') {
        return $companyTime['0']['OPENING_HOUR'].':'.$companyTime['0']['OPENING_MINUTE'].':00';
    }
    
    // return closing time in correct format for smarty time builder
    if($time_event == 'closing_time') {
        return $companyTime['0']['CLOSING_HOUR'].':'.$companyTime['0']['CLOSING_MINUTE'].':00';
    }
}
    
    
##########################################
#  Check Start and End times are valid   #
##########################################

function check_start_end_times($start_time, $end_time) {
    
    global $smarty; 
    
    // If start time is before end time
    if($start_time > $end_time) {        
        $smarty->assign('warning_msg', 'Start Time is after End Time');
        return false;
    }
        
    // If the start and end time are the same    
    if($start_time ==  $end_time) {        
        $smarty->assign('warning_msg', 'Start Time is the same as End Time');
        return false;
    }
    
    return true;
}

##########################################
#        Insert Company Hours            #
##########################################

function insert_company_hours($db, $openingTime, $closingTime) {
    
    global $smarty;
    
    $q = 'UPDATE '.PRFX.'SETUP SET
        OPENING_HOUR    ='. $db->qstr( $openingTime['Time_Hour']     ).',
        OPENING_MINUTE  ='. $db->qstr( $openingTime['Time_Minute']   ).',
        CLOSING_HOUR    ='. $db->qstr( $closingTime['Time_Hour']     ).',
        CLOSING_MINUTE  ='. $db->qstr( $closingTime['Time_Minute']   );

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error','error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;
    } else {            
        $smarty->assign('information_msg','Office hours have been updated.');
        return true;
    }
}