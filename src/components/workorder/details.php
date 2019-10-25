<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');
require(INCLUDES_DIR.'workorder.php');
require(INCLUDES_DIR.'schedule.php');
require(INCLUDES_DIR.'user.php');

// Check if we have a workorder_id
if(!isset(\QFactory::$VAR['workorder_id']) || !\QFactory::$VAR['workorder_id']) {
    systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    force_page('workorder', 'search');
}

$workorder_details = get_workorder_details(\QFactory::$VAR['workorder_id']);
$client_details = get_client_details($workorder_details['client_id']);

// Build the page with the workorder details from the database
$smarty->assign('employee_details',     get_user_details($workorder_details['employee_id'])                                      );
$smarty->assign('client_details',       $client_details                                                                          );
$smarty->assign('workorder_statuses',   get_workorder_statuses()                                                                 );
$smarty->assign('workorder_details',    $workorder_details                                                                       );
$smarty->assign('workorder_schedules',  display_schedules('schedule_id', 'DESC', false, null, null, null, null, null, null, null, \QFactory::$VAR['workorder_id'])  );
$smarty->assign('workorder_notes',      display_workorder_notes(\QFactory::$VAR['workorder_id'])                                            ); 
$smarty->assign('workorder_history',    display_workorder_history(\QFactory::$VAR['workorder_id'])                                          );
$smarty->assign('selected_date',        timestamp_to_calendar_format( time() )                                                   );
$smarty->assign('GoogleMapString',      build_googlemap_directions_string($workorder_details['client_id'], $user->login_user_id) );