<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a schedule_id
if(!isset(\CMSApplication::$VAR['schedule_id']) || !\CMSApplication::$VAR['schedule_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Schedule ID supplied."));
    $this->app->system->page->force_page('schedule', 'search');
}

// If new schedule item submitted
if(isset(\CMSApplication::$VAR['submit'])) { 
        
    // Add missing Time variables to 'qform' (smarty workaround)
    \CMSApplication::$VAR['qform']['StartTime'] = \CMSApplication::$VAR['StartTime'];
    \CMSApplication::$VAR['qform']['EndTime'] = \CMSApplication::$VAR['EndTime'];
    
    // Add missing Time variables in DATETIME format
    \CMSApplication::$VAR['qform']['start_time'] = $this->app->system->general->smartytime_to_otherformat('datetime', \CMSApplication::$VAR['qform']['start_date'], \CMSApplication::$VAR['StartTime']['Time_Hour'], \CMSApplication::$VAR['StartTime']['Time_Minute'], '0', '24');
    \CMSApplication::$VAR['qform']['end_time']   = $this->app->system->general->smartytime_to_otherformat('datetime', \CMSApplication::$VAR['qform']['end_date'], \CMSApplication::$VAR['EndTime']['Time_Hour'], \CMSApplication::$VAR['EndTime']['Time_Minute'], '0', '24');
    
    /* This manually builds a 'Time' string
    \CMSApplication::$VAR['qform']['start_time'] = \CMSApplication::$VAR['StartTime']['Time_Hour'].":".\CMSApplication::$VAR['StartTime']['Time_Minute'];
    \CMSApplication::$VAR['qform']['end_time'] = \CMSApplication::$VAR['EndTime']['Time_Hour'].":".\CMSApplication::$VAR['EndTime']['Time_Minute'];*/
    
    
    // If db insert fails send them an error and reload the page with submitted info or load the page with the schedule
    if (!$this->app->components->schedule->updateRecord(\CMSApplication::$VAR['qform'])) {        
        
        // Build the page
        $this->app->smarty->assign('schedule_details', \CMSApplication::$VAR['qform']);
        $this->app->smarty->assign('active_employees', $this->app->components->user->getActiveUsers('employees'));                      
            
    } else {       
        
        /* Load the schedule day with the updated schedule item        
        \CMSApplication::$start_year            = date('Y', $this->app->system->general->date_to_timestamp(\CMSApplication::$VAR['qform']['start_date'])  );
        \CMSApplication::$start_month           = date('m', $this->app->system->general->date_to_timestamp(\CMSApplication::$VAR['qform']['start_date'])  );
        \CMSApplication::$start_day             = date('d', $this->app->system->general->date_to_timestamp(\CMSApplication::$VAR['qform']['start_date'])  );         
        $this->app->system->page->force_page('schedule', 'day', 'start_year='.$start_year.'&start_month='.$start_month.'&start_day='.$start_day.'&employee_id='.\CMSApplication::$VAR['qform']['employee_id'].'&workorder_id='.\CMSApplication::$VAR['qform']['workorder_id'].'&msg_success='._gettext("Schedule Successfully Updated."));
        */
        
        // Load the updated schedule details page
        $this->app->system->page->force_page('schedule', 'details&schedule_id='.\CMSApplication::$VAR['qform']['schedule_id'], 'msg_success='.gettext("Schedule Successfully Updated."));
        
    }

// If edit schedule form is loaded, get schedule item from the database and assign
} else {
    
    // Build the page       
    $this->app->smarty->assign('schedule_details', $this->app->components->schedule->getRecord(\CMSApplication::$VAR['schedule_id']));
    $this->app->smarty->assign('active_employees', $this->app->components->user->getActiveUsers('employees')       );
    
}