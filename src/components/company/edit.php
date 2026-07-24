<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['qform']['delete_logo'] = \CMSApplication::$VAR['qform']['delete_logo'] ?? null;

// Update Company details
if(isset(\CMSApplication::$VAR['submit'])) {

    // Check the submission is valid, if not, carry on loading the page loading the page but with an error message
    if($this->app->components->company->checkRecordSubmissionIsValid(\CMSApplication::$VAR['qform'])) {

        // Submit data to the database
        $this->app->components->company->updateRecord(\CMSApplication::$VAR['qform']);

        // Reload Company options
        $this->app->system->page->forcePage('company', 'edit');

    // Submission has failed validation,
    } else {
        $submitFailedValidation = true;
    }

}

// If a submission happend and failed validation, load page with the failed submitted values, else load values from database as normal
if($submitFailedValidation ?? null) {
    $company_details = array_merge($this->app->components->company->getRecord(), \CMSApplication::$VAR['qform']);

    // Handle specifc case of opening hours
    $company_details['opening_hour'] = \CMSApplication::$VAR['qform']['openingTime']['Time_Hour'];
    $company_details['opening_minute'] = \CMSApplication::$VAR['qform']['openingTime']['Time_Minute'];
    $company_details['closing_hour'] = \CMSApplication::$VAR['qform']['closingTime']['Time_Hour'];
    $company_details['closingMinute'] = \CMSApplication::$VAR['qform']['closingTime']['Time_Minute'];

} else {
    $company_details = $this->app->components->company->getRecord();
}

// Build the page
$this->app->smarty->assign('date_formats', $this->app->system->general->getDateFormats());
$this->app->smarty->assign('tax_systems', $this->app->components->company->getTaxSystems());
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes(null, true));
$this->app->smarty->assign('company_details', $company_details);

// These use the format ('07:30:00')
//$this->app->smarty->assign('opening_time', $this->app->components->company->getOpeningHours('opening_time', 'smartytime'));
//$this->app->smarty->assign('closing_time', $this->app->components->company->getOpeningHours('closing_time', 'smartytime'));

// Opening hours can use timestamps?
// $this->app->smarty->assign('opening_time', $opening_time );
//$this->app->smarty->assign('closing_time', $closing_time );
