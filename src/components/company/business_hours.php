<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'company.php');

// If new times submitted
if(isset(\QFactory::$VAR['submit'])) {
    
    // Build the start and end times for comparison
    $opening_time = strtotime(\QFactory::$VAR['openingTime']['Time_Hour'].':'.\QFactory::$VAR['openingTime']['Time_Minute'].':'.'00');
    $closing_time = strtotime(\QFactory::$VAR['closingTime']['Time_Hour'].':'.\QFactory::$VAR['closingTime']['Time_Minute'].':'.'00');

    // Validate the submitted times
    if (check_start_end_times($opening_time, $closing_time)) {
        
        // Update opening and closing Times into the database
        update_company_hours(\QFactory::$VAR['openingTime'], \QFactory::$VAR['closingTime']);
        
    }
    
    // Assign varibles (for page load)
    $smarty->assign('opening_time', $opening_time   );
    $smarty->assign('closing_time', $closing_time   );

// If page is just loaded get the opening and closing times stored in the database
} else {    
    $smarty->assign('opening_time', get_company_opening_hours('opening_time', 'smartytime'));
    $smarty->assign('closing_time', get_company_opening_hours('closing_time', 'smartytime'));   
}