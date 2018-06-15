<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm(null, null, $raw_output)) {
    die(_gettext("No Direct Access Allowed."));
}

// Process SQL Query for log if SQL loggin is enabled
if(QFactory::getConfig()->get('qwcrm_sql_logging')) {
    
    // Prepare the SQL statement for the error log (already been prepared for output to screen)
    $sql_query_for_log = str_replace('<br>', '\r\n', $VAR['error_sql_query']);
    
} else {    
    $sql_query_for_log = '';    
}

// Log errors to log if enabled
if(QFactory::getConfig()->get('qwcrm_error_log')) {    
    write_record_to_error_log($VAR['error_component'].':'.$VAR['error_page_tpl'], $VAR['error_type'], $VAR['error_location'], $VAR['error_php_function'], $VAR['error_database'], $VAR['error_msg'], $sql_query_for_log);    
}
    
// View RAW error output if allowed and set
if($user->login_usergroup_id <= 6 && $raw_output) {

    $BuildPage = '
        <div>    
            <strong>'._gettext("Error Page").': </strong>'.$VAR['error_component'].':'.$VAR['error_page_tpl'].'<br />
            <strong>'._gettext("Error Type").': </strong>'.$VAR['error_type'].'<br /><br />

            <strong>'._gettext("Error Location").': </strong>'.$VAR['error_location'].'<br />    
            <strong>'._gettext("PHP Function").': </strong>'.$VAR['error_php_function'].'<br /><br />      

            <strong>'._gettext("Database Error").': </strong><br />'.$VAR['error_database'].'<br /><br />
            <strong>'._gettext("SQL Query").': </strong><br />'.$VAR['error_sql_query'].'<br /><br />

            <strong>'._gettext("Error Message").': </strong>'.$VAR['error_msg'].'<br /><br /> 
        </div>
    ';

// View errors in normal template if allowed
} elseif($user->login_usergroup_id <= 6){

    // Assign variables to display on the error page (core:error)
    $smarty->assign('error_component',      $VAR['error_component']        );
    $smarty->assign('error_page_tpl',       $VAR['error_page_tpl']         );
    $smarty->assign('error_type',           $VAR['error_type']             );
    $smarty->assign('error_location',       $VAR['error_location']         );
    $smarty->assign('error_php_function',   $VAR['error_php_function']     );
    $smarty->assign('error_database',       $VAR['error_database']         );
    $smarty->assign('error_sql_query',      $VAR['error_sql_query']        );
    $smarty->assign('error_msg',            $VAR['error_msg']              );

    $BuildPage .= $smarty->fetch('core/error.tpl');

// No permission to see errors
} else {

    $BuildPage .= _gettext("An error has occured but you are not allowed to see it.").'<br>';
    $BuildPage .= _gettext("Timestamp").': '.time().'<br>';
    $BuildPage .= _gettext("Give this information to an admin and they can have a look at it for you.");

}


