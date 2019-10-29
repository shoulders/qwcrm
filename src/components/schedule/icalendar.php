<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// If no schedule year/month/day set, use today's date
\CMSApplication::$VAR['start_year'] = isset(\CMSApplication::$VAR['start_year']) ? \CMSApplication::$VAR['start_year'] : date('Y');
\CMSApplication::$VAR['start_month'] = isset(\CMSApplication::$VAR['start_month']) ? \CMSApplication::$VAR['start_month'] : date('m');
\CMSApplication::$VAR['start_day'] = isset(\CMSApplication::$VAR['start_day']) ? \CMSApplication::$VAR['start_day'] : date('d');

// Check if we have a employee_id and output is set to day
if(isset(\CMSApplication::$VAR['ics_type']) && \CMSApplication::$VAR['ics_type'] == 'day' && !\CMSApplication::$VAR['employee_id']) {    
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Employee ID missing."));
    $this->app->system->general->force_page('schedule', 'search');
}

// Check if we have a schedule_id if output is not set to day
if(isset(\CMSApplication::$VAR['ics_type']) && \CMSApplication::$VAR['ics_type'] != 'day' && !\CMSApplication::$VAR['schedule_id']) {    
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Schedule ID is missing."));
    $this->app->system->general->force_page('schedule', 'search');
}

// Check if we have all of the date information required
if(!isset(\CMSApplication::$VAR['start_year'], \CMSApplication::$VAR['start_month'], \CMSApplication::$VAR['start_day']) || !\CMSApplication::$VAR['start_year'] || !\CMSApplication::$VAR['start_month'] || !\CMSApplication::$VAR['start_day']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("Some date information is missing."));
    $this->app->system->general->force_page('schedule', 'search');
}

// Add routines here to decide what is returned ie multi schedule, single schedule or a live calendar

// ICS Schedule
if(isset(\CMSApplication::$VAR['ics_type']) && \CMSApplication::$VAR['ics_type'] == 'day') {
    
    // Get Employee Display Name
    $user_display_name = $this->app->components->user->get_user_details(\CMSApplication::$VAR['employee_id'], 'display_name');
    
    // Set filename    
    $ics_filename = str_replace(' ', '-', $user_display_name).'_'._gettext("Day").'-'._gettext("Schedule").'_'.\CMSApplication::$VAR['start_year'].'-'.\CMSApplication::$VAR['start_month'].'-'.\CMSApplication::$VAR['start_day'].'.ics';
    
    // Build Day Schedule for the employee as an .ics
    $ics_content =  $this->app->components->schedule->build_ics_schedule_day(\CMSApplication::$VAR['employee_id'], \CMSApplication::$VAR['start_year'], \CMSApplication::$VAR['start_month'], \CMSApplication::$VAR['start_day']);
    
    // Log activity
    $record = 'Day Schedule'.' ('.\CMSApplication::$VAR['start_year'].'-'.\CMSApplication::$VAR['start_month'].'-'.\CMSApplication::$VAR['start_day'].') '._gettext("for").' ' .$user_display_name.' '._gettext("has been exported.");
    $this->app->system->general->write_record_to_activity_log($record, \CMSApplication::$VAR['employee_id']);

// Single ICS
} else {
    
    // Get Schedule Details
    $schedule_details = $this->app->components->schedule->get_schedule_details(\CMSApplication::$VAR['schedule_id']);
    
    // Get Client Display Name
    $client_display_name = $this->app->components->client->get_client_details($schedule_details['client_id'], 'display_name');
    
    // Set filename
    $ics_filename   = _gettext("Schedule").'-'.\CMSApplication::$VAR['schedule_id'].'_'._gettext("WorkOrder").'-'.$schedule_details['workorder_id'].'_'.str_replace(' ', '-', $client_display_name).'.ics';
    //$ics_filename   = 'schedule.ics';
    
    // Build a single schedule item as an .ics
    $ics_content =  $this->app->components->schedule->build_single_schedule_ics(\CMSApplication::$VAR['schedule_id']);
    
    // Log activity
    $record = _gettext("Schedule").' '.\CMSApplication::$VAR['schedule_id'].' '._gettext("has been exported.");
    $this->app->system->general->write_record_to_activity_log($record, $schedule_details['employee_id'], $schedule_details['client_id'], $schedule_details['workorder_id']);
    
}

// Set the correct headers for this file
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: attachment; filename=' . $ics_filename);

// Output the .ics file
echo $ics_content;

// No furhter processing required
die();
