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
    $this->app->system->page->forcePage('schedule', 'search');
}

// Load the edit page if allowed
if(!$this->app->components->schedule->checkRecordAllowsEdit(\CMSApplication::$VAR['schedule_id'])) {
    $this->app->system->page->forcePage('schedule', 'details&schedule_id='.\CMSApplication::$VAR['schedule_id']);
} else {

    // If data has been submitted, validate and then update the record
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
            // Update the record
            $this->app->components->schedule->updateRecord(\CMSApplication::$VAR['qform']);

            // Success message
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Schedule updated successfully."));

        } else {
            $submitFailedValidation = true;
        }

        // If submission was successful, load the details page
        if(!$submitFailedValidation) {
            $this->app->system->page->forcePage('schedule', 'details&schedule_id='.\CMSApplication::$VAR['qform']['schedule_id']);

            /* Load the schedule day with the updated schedule item
            \CMSApplication::$start_year            = date('Y', $this->app->system->general->date_to_timestamp(\CMSApplication::$VAR['qform']['start_date'])  );
            \CMSApplication::$start_month           = date('m', $this->app->system->general->date_to_timestamp(\CMSApplication::$VAR['qform']['start_date'])  );
            \CMSApplication::$start_day             = date('d', $this->app->system->general->date_to_timestamp(\CMSApplication::$VAR['qform']['start_date'])  );
            $this->app->system->variables->systemMessagesWrite('success', _gettext("Schedule Successfully Updated."));
            $this->app->system->page->forcePage('schedule', 'day', 'start_year='.$start_year.'&start_month='.$start_month.'&start_day='.$start_day.'&employee_id='.\CMSApplication::$VAR['qform']['employee_id'].'&workorder_id='.\CMSApplication::$VAR['qform']['workorder_id']);
            */

        }
    }

    // If a submission happened and failed validation, load page with the failed submitted values, else load values from database as normal
    if($submitFailedValidation ?? null) {
        $schedule_details  = array_merge($this->app->components->schedule->getRecord(\CMSApplication::$VAR['schedule_id']), \CMSApplication::$VAR['qform']);
    } else {
        $schedule_details = $this->app->components->schedule->getRecord(\CMSApplication::$VAR['schedule_id']);
    }

    // Build the page
    $this->app->smarty->assign('schedule_details', $schedule_details);
    $this->app->smarty->assign('active_employees', $this->app->components->user->getActiveUsers('employees'));

}
