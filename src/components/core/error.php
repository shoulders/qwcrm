<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\QFactory::$VAR['error_component'] = isset(\QFactory::$VAR['error_component']) ? \QFactory::$VAR['error_component'] : null;
\QFactory::$VAR['error_page_tpl'] = isset(\QFactory::$VAR['error_page_tpl']) ? \QFactory::$VAR['error_page_tpl'] : null;
\QFactory::$VAR['error_type'] = isset(\QFactory::$VAR['error_type']) ? \QFactory::$VAR['error_type'] : null;
\QFactory::$VAR['error_location'] = isset(\QFactory::$VAR['error_location']) ? \QFactory::$VAR['error_location'] : null;
\QFactory::$VAR['error_php_function'] = isset(\QFactory::$VAR['error_php_function']) ? \QFactory::$VAR['error_php_function'] : null;
\QFactory::$VAR['error_database'] = isset(\QFactory::$VAR['error_database']) ? \QFactory::$VAR['error_database'] : null;
\QFactory::$VAR['error_sql_query'] = isset(\QFactory::$VAR['error_sql_query']) ? \QFactory::$VAR['error_sql_query'] : null;
\QFactory::$VAR['error_msg'] = isset(\QFactory::$VAR['error_msg']) ? \QFactory::$VAR['error_msg'] : null;
\QFactory::$VAR['error_enable_override'] = isset(\QFactory::$VAR['error_enable_override']) ? \QFactory::$VAR['error_enable_override'] : null;

// Prevent direct access to this page
if(!check_page_accessed_via_qwcrm(null, null, \QFactory::$VAR['error_enable_override'])) {
    header('HTTP/1.1 403 Forbidden');
    die(_gettext("No Direct Access Allowed."));
}

// Process SQL Query for log if SQL loggin is enabled
if($config->get('qwcrm_sql_logging')) {
    
    // Prepare the SQL statement for the error log (already been prepared for output to screen)
    $sql_query_for_log = str_replace('<br>', '\r\n', \QFactory::$VAR['error_sql_query']);
    
} else {    
    $sql_query_for_log = '';    
}

// Log errors to log if enabled
if($config->get('qwcrm_error_log')) {    
    write_record_to_error_log(\QFactory::$VAR['error_component'].':'.\QFactory::$VAR['error_page_tpl'], \QFactory::$VAR['error_type'], \QFactory::$VAR['error_location'], \QFactory::$VAR['error_php_function'], \QFactory::$VAR['error_database'], \QFactory::$VAR['error_msg'], $sql_query_for_log);    
}
    
// View RAW error output if allowed and set
if($user->login_usergroup_id <= 6 && isset($output_raw_error_page)) {

    \QFactory::$BuildPage = '
        <div>    
            <strong>'._gettext("Error Page").': </strong>'.\QFactory::$VAR['error_component'].':'.\QFactory::$VAR['error_page_tpl'].'<br />
            <strong>'._gettext("Error Type").': </strong>'.\QFactory::$VAR['error_type'].'<br /><br />

            <strong>'._gettext("Error Location").': </strong>'.\QFactory::$VAR['error_location'].'<br />    
            <strong>'._gettext("PHP Function").': </strong>'.\QFactory::$VAR['error_php_function'].'<br /><br />      

            <strong>'._gettext("Database Error").': </strong><br />'.\QFactory::$VAR['error_database'].'<br /><br />
            <strong>'._gettext("SQL Query").': </strong><br />'.\QFactory::$VAR['error_sql_query'].'<br /><br />

            <strong>'._gettext("Error Message").': </strong>'.\QFactory::$VAR['error_msg'].'<br /><br /> 
        </div>
    ';

// View errors in normal template if allowed
} elseif($user->login_usergroup_id <= 6){

    // Assign variables to display on the error page (core:error)
    $smarty->assign('error_component',      \QFactory::$VAR['error_component']        );
    $smarty->assign('error_page_tpl',       \QFactory::$VAR['error_page_tpl']         );
    $smarty->assign('error_type',           \QFactory::$VAR['error_type']             );
    $smarty->assign('error_location',       \QFactory::$VAR['error_location']         );
    $smarty->assign('error_php_function',   \QFactory::$VAR['error_php_function']     );
    $smarty->assign('error_database',       \QFactory::$VAR['error_database']         );
    $smarty->assign('error_sql_query',      \QFactory::$VAR['error_sql_query']        );
    $smarty->assign('error_msg',            \QFactory::$VAR['error_msg']              );

    \QFactory::$BuildPage .= $smarty->fetch('core/error.tpl');

// No permission to see errors
} else {

    \QFactory::$BuildPage .= _gettext("An error has occured but you are not allowed to see it.").'<br>';
    \QFactory::$BuildPage .= _gettext("Timestamp").': '.time().'<br>';
    \QFactory::$BuildPage .= _gettext("Give this information to an admin and they can have a look at it for you.");

}


