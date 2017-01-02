<?php

require(INCLUDES_DIR.'modules/company.php');

// If new times submitted
if(isset($VAR['submit'])) {
    
    // Build the start and end times for comparision
    $opening_time = strtotime($VAR['openingTime']['Time_Hour'].':'.$VAR['openingTime']['Time_Minute'].':'.'00');
    $closing_time = strtotime($VAR['closingTime']['Time_Hour'].':'.$VAR['closingTime']['Time_Minute'].':'.'00');

    // Validate the submitted times
    if (check_start_end_times($opening_time, $closing_time)) {
        
        // Insert opening and closing Times into the database
        insert_company_hours($db, $VAR['openingTime'], $VAR['closingTime']);
        
    }
    
    // Assign varibles to load database or post
    $smarty->assign('opening_time', $opening_time   );
    $smarty->assign('closing_time', $closing_time   );

// If page is just loaded get the opening and closing times stored in the database
} else {    
    $smarty->assign('opening_time', get_company_start_end_times($db, 'opening_time'));
    $smarty->assign('closing_time', get_company_start_end_times($db, 'closing_time'));   
}

// Fetch the hours page
$BuildPage .= $smarty->fetch('company'.SEP.'hours.tpl');