<?php

/*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

/*
 * Mandatory Code - Code that is run upon the file being loaded
 * Display Functions - Code that is used to primarily display records - linked tables
 * New/Insert Functions - Creation of new records
 * Get Functions - Grabs specific records/fields ready for update - no table linking
 * Update Functions - For updating records/fields
 * Close Functions - Closing Work Orders code
 * Delete Functions - Deleting Work Orders
 * Other Functions - All other functions not covered above
 */

defined('_QWEXEC') or die;

/** Mandatory Code **/

/** Display Functions **/

function display_hits_by_ip($db, $ip_address) {
    
    $sql = "SELECT * FROM ".PRFX."tracker WHERE ip=". $db->qstr( $ip_address ) ."ORDER BY date";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return hits by IP."));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }   
    
}


/** New/Insert Functions **/

/** Get Functions **/

/** Update Functions **/

/** Close Functions **/

/** Delete Functions **/

/** Other Functions **/

############################
#    Hits for the day      #
############################

function day_hits($db, $today_start, $today_end, $where) {
    
    $sql = "SELECT count(*) as count FROM ".PRFX."tracker WHERE date >= ".$db->qstr( $today_start )." AND date <= ".$db->qstr( $today_end ).$db->qstr( $where );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to return hits for the day."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}

#################################
#    All Stats for the day      #
#################################

function get_day_all_stats($db, $today_start, $today_end, $where){
    
    $sql = "SELECT date, uagent, count(*) as count, ip FROM ".PRFX."tracker WHERE date >= ".$db->qstr( $today_start )." AND date <= ".$db->qstr( $today_end )." ".$db->qstr( $where )." GROUP BY ip ORDER BY date";
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get all stats for the specified day."));
        exit;
    } else {
        
        return $rs->GetArray();
        
    }   
    
}

##################################
#     Hits for the month         #
##################################

function get_month_hits($db, $month_start, $month_end, $where) {
    
    $sql = "SELECT count(*) as count FROM ".PRFX."tracker WHERE date >= ".$db->qstr( $month_start )." AND date <= ".$db->qstr( $month_end )." ".$db->qstr( $where );
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, gettext("Failed to get all the hits for the month."));
        exit;
    } else {
        
        return $rs->fields['count']; 
        
    }   
    
}
