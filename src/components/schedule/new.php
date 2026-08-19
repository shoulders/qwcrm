<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
//\CMSApplication::$VAR['qform']['note'] = \CMSApplication::$VAR['qform']['note'] ?? null;

// Check if we have an employee_id
if(!isset(\CMSApplication::$VAR['employee_id']) || !\CMSApplication::$VAR['employee_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Employee ID supplied."));
    $this->app->system->page->forcePage('user', 'search');
}

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    $this->app->system->page->forcePage('workorder', 'search');
}

// Check if the record can be created
if(!$this->app->components->schedule->checkRecordCanBeCreated(\CMSApplication::$VAR['employee_id'], \CMSApplication::$VAR['workorder_id'])) {
    $this->app->system->page->forcePage('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
} else {

    // Get client_id
    \CMSApplication::$VAR['client_id'] =  $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id'], 'client_id');

    // If data has been submitted, validate and then update the record (in this case create record)
    if(isset(\CMSApplication::$VAR['submit']))
    {
        // Holding variable for validation tests
        $submitFailedValidation = false;

        // Add missing Time variables to 'qform' (smarty workaround)
        \CMSApplication::$VAR['qform']['StartTime'] = \CMSApplication::$VAR['StartTime'];
        \CMSApplication::$VAR['qform']['EndTime'] = \CMSApplication::$VAR['EndTime'];

        // Add missing Time variables in DATETIME format
        \CMSApplication::$VAR['qform']['start_time'] = $this->app->system->general->smartytimeToOtherformat('datetime', \CMSApplication::$VAR['qform']['start_date'], \CMSApplication::$VAR['StartTime']['Time_Hour'], \CMSApplication::$VAR['StartTime']['Time_Minute'], '0', '24');
        \CMSApplication::$VAR['qform']['end_time']   = $this->app->system->general->smartytimeToOtherformat('datetime', \CMSApplication::$VAR['qform']['end_date'], \CMSApplication::$VAR['EndTime']['Time_Hour'], \CMSApplication::$VAR['EndTime']['Time_Minute'], '0', '24');

        /* This manually builds a 'Time' string
        \CMSApplication::$VAR['qform']['start_time'] = \CMSApplication::$VAR['StartTime']['Time_Hour'].":".\CMSApplication::$VAR['StartTime']['Time_Minute'];
        \CMSApplication::$VAR['qform']['end_time'] = \CMSApplication::$VAR['EndTime']['Time_Hour'].":".\CMSApplication::$VAR['EndTime']['Time_Minute'];*/

        // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
        if($this->app->components->schedule->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform']))
        {
            // Create the record
            $this->app->components->schedule->insertRecord(\CMSApplication::$VAR['qform']);

            // Success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Schedule created successfully."));

        } else {
            $submitFailedValidation = true;
        }

        // If submission was successful, load the details page / schedule day
        if(!$submitFailedValidation) {

            //$this->app->system->page->forcePage('schedule', 'details&schedule_id='.\CMSApplication::$VAR['qform']['schedule_id']);

            // Break up the date into segments in the correct format
            $start_year            = date('Y', $this->app->system->general->dateToTimestamp(\CMSApplication::$VAR['qform']['start_date'])  );
            $start_month           = date('m', $this->app->system->general->dateToTimestamp(\CMSApplication::$VAR['qform']['start_date'])  );
            $start_day             = date('d', $this->app->system->general->dateToTimestamp(\CMSApplication::$VAR['qform']['start_date'])  );

            // Load the schedule day with the newly submitted schedule item
            $this->app->system->page->forcePage('schedule', 'day', 'start_year='.$start_year.'&start_month='.$start_month.'&start_day='.$start_day.'&employee_id='.\CMSApplication::$VAR['qform']['employee_id'].'&workorder_id='.\CMSApplication::$VAR['qform']['workorder_id']);

        }
    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $qform = \CMSApplication::$VAR['qform'];
    } else {

        // Generate `end_time` by adding 1 hour on to a `start_time` (if possible)
        $end_time = explode(':', \CMSApplication::$VAR['start_time']);
        $end_time[0] = ($end_time[0] + 1) > 23 ? 23 : ($end_time[0] + 1);
        $end_time = (string) $end_time[0].':'.(string)$end_time[1];

        // Build QForm
        $qform['client_id']      = \CMSApplication::$VAR['client_id'];
        $qform['employee_id']    = \CMSApplication::$VAR['employee_id'];
        $qform['workorder_id']   = \CMSApplication::$VAR['workorder_id'];
        $qform['start_date']     = $this->app->system->general->yearMonthDayToDate(\CMSApplication::$VAR['start_year'], \CMSApplication::$VAR['start_month'], \CMSApplication::$VAR['start_day']);
        $qform['start_time']     = \CMSApplication::$VAR['start_time'];
        $qform['end_date']       = $this->app->system->general->yearMonthDayToDate(\CMSApplication::$VAR['start_year'], \CMSApplication::$VAR['start_month'], \CMSApplication::$VAR['start_day']);
        $qform['end_time']       = $end_time;
        $qform['note']           = '';
    }

    // Build the page
    $this->app->smarty->assign('qform', $qform);
    $this->app->smarty->assign('active_employees', $this->app->components->user->getActiveUsers('employees'));

}
