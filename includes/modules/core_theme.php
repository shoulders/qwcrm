<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

/** Misc **/

#########################################
#  Greeting Message Based on Time       #
#########################################

function greeting_message_based_on_time($employee_name)
{
    $morning    = _gettext("Good morning").' '.$employee_name;
    $afternoon  = _gettext("Good afternoon").' '.$employee_name;
    $evening    = _gettext("Good evening").' '.$employee_name;
    $night      = _gettext("Working late").' '.$employee_name;
    
    $friday     = _gettext("Get ready for the weekend!");
    
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
    if ($current_day === 'Friday') {
        $greeting_msg = $greeting_msg.' - '.$friday;
    }
    
    return $greeting_msg;
}
