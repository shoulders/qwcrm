<?php

/** Mandatory Code **/

##############################
# Load language translations #
##############################

if(!xml2php('core_theme')) {    
    $smarty->assign('error_msg', 'Error in core language file');
}


// Menu is a special case, it should have its own language file section

/*
if(!xml2php('core_menu')) {    
    $smarty->assign('error_msg', 'Error in core language file');
}   
 */

/** Misc **/

#########################################
#  Greeting Message Based on Time       #
#########################################

function greeting_message_based_on_time($employee_name){
    
    global $smarty;
    
    $morning    = "Good morning! $employee_name";
    $afternoon  = "Good afternoon! $employee_name";
    $evening    = "Good evening! $employee_name";
    $night      = "Working late? $employee_name";
    
    $friday     = "Get ready for the weekend!";

    // Get the current hour
    $current_time = date('H');
    
    // Get the current day
    $current_day = date('l');
    
    // 06:00 - 11:59
    if ($current_time >= 6 && $current_time <=11) {
        $greeting_msg = $morning;
    }
    // 12:00 - 17:59
    elseif ($current_time >= 12 && $current_time <= 17) {
        $greeting_msg =  $afternoon;
    }
    // 18:00. - 23:59 p.m.
    elseif ($current_time >= 17 && $current_time <= 23) {
        $greeting_msg =  $evening;
    }
    // 00:00 - 05:59
    elseif ($current_time >= 0 && $current_time <= 5) {
        $greeting_msg = $night;
    }    
    
    // Friday
    if ($current_day === 'Friday'){
        $greeting_msg = $greeting_msg.' - '.$friday;
    }
    return $greeting_msg;
}