<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['error_component'] = \CMSApplication::$VAR['error_component'] ?? null;
\CMSApplication::$VAR['error_page_tpl'] = \CMSApplication::$VAR['error_page_tpl'] ?? null;
\CMSApplication::$VAR['error_type'] = \CMSApplication::$VAR['error_type'] ?? null;
\CMSApplication::$VAR['error_location'] = \CMSApplication::$VAR['error_location'] ?? null;
\CMSApplication::$VAR['error_php_function'] = \CMSApplication::$VAR['error_php_function'] ?? null;
\CMSApplication::$VAR['error_database'] = \CMSApplication::$VAR['error_database'] ?? null;
\CMSApplication::$VAR['error_sql_query'] = \CMSApplication::$VAR['error_sql_query'] ?? null;
\CMSApplication::$VAR['error_msg'] = \CMSApplication::$VAR['error_msg'] ?? null;
\CMSApplication::$VAR['error_enable_override'] = \CMSApplication::$VAR['error_enable_override'] ?? null;

// Prevent direct access to this page
if(!$this->app->system->security->checkPageAccessedViaQwcrm(null, null, \CMSApplication::$VAR['error_enable_override'])) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Process SQL Query for log if SQL loggin is enabled
if($this->app->config->get('qwcrm_sql_logging')) {
    
    // Prepare the SQL statement for the error log (already been prepared for output to screen)
    $sql_query_for_log = str_replace('<br>', '\r\n', \CMSApplication::$VAR['error_sql_query']);
    
} else {    
    $sql_query_for_log = '';    
}

// Log errors to log if enabled
if($this->app->config->get('qwcrm_error_log')) {    
    $this->app->system->general->writeRecordToErrorLog(\CMSApplication::$VAR['error_component'].':'.\CMSApplication::$VAR['error_page_tpl'], \CMSApplication::$VAR['error_type'], \CMSApplication::$VAR['error_location'], \CMSApplication::$VAR['error_php_function'], \CMSApplication::$VAR['error_database'], \CMSApplication::$VAR['error_msg'], $sql_query_for_log);    
}
    
// View RAW error output if allowed and set
if($this->app->user->login_usergroup_id <= 6 && $this->app->config->get('error_page_raw_output')) {

    $pagePayload = '
        <div>'._gettext("An error has occured while accessing the database.").'<br /><br /></div>
        <div>    
            <strong>'._gettext("Error Page").': </strong>'.\CMSApplication::$VAR['error_component'].':'.\CMSApplication::$VAR['error_page_tpl'].'<br />
            <strong>'._gettext("Error Type").': </strong>'.\CMSApplication::$VAR['error_type'].'<br /><br />

            <strong>'._gettext("Error Location").': </strong>'.\CMSApplication::$VAR['error_location'].'<br />    
            <strong>'._gettext("PHP Function").': </strong>'.\CMSApplication::$VAR['error_php_function'].'<br /><br />      

            <strong>'._gettext("Database Error").': </strong><br />'.\CMSApplication::$VAR['error_database'].'<br /><br />
            <strong>'._gettext("SQL Query").': </strong><br />'.\CMSApplication::$VAR['error_sql_query'].'<br /><br />

            <strong>'._gettext("Error Message").': </strong>'.\CMSApplication::$VAR['error_msg'].'<br /><br /> 
        </div>
    ';

// View errors in normal template if allowed
} elseif($this->app->user->login_usergroup_id <= 6){

    // Assign variables to display on the error page (core:error)
    $this->app->smarty->assign('error_component',      \CMSApplication::$VAR['error_component']        );
    $this->app->smarty->assign('error_page_tpl',       \CMSApplication::$VAR['error_page_tpl']         );
    $this->app->smarty->assign('error_type',           \CMSApplication::$VAR['error_type']             );
    $this->app->smarty->assign('error_location',       \CMSApplication::$VAR['error_location']         );
    $this->app->smarty->assign('error_php_function',   \CMSApplication::$VAR['error_php_function']     );
    $this->app->smarty->assign('error_database',       \CMSApplication::$VAR['error_database']         );
    $this->app->smarty->assign('error_sql_query',      \CMSApplication::$VAR['error_sql_query']        );
    $this->app->smarty->assign('error_msg',            \CMSApplication::$VAR['error_msg']              );

// No permission to see errors
} else {

    $pagePayload = _gettext("An error has occured but you are not allowed to see it.").'<br>';
    $pagePayload .= _gettext("Timestamp").': '.time().'<br>';
    $pagePayload .= _gettext("Give this information to an admin and they can have a look at it for you.");

}


