<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    $this->app->system->page->forcePage('workorder', 'search');
}

$workorder_details = $this->app->components->workorder->getRecord(\CMSApplication::$VAR['workorder_id']);
$client_details = $this->app->components->client->getRecord($workorder_details['client_id']);

// Build the page with the workorder details from the database
$this->app->smarty->assign('employee_details',     $this->app->components->user->getRecord($workorder_details['employee_id'])                                      );
$this->app->smarty->assign('client_details',       $client_details                                                                          );
$this->app->smarty->assign('workorder_statuses',   $this->app->components->workorder->getStatuses()                                                                 );
$this->app->smarty->assign('workorder_details',    $workorder_details                                                                       );
$this->app->smarty->assign('workorder_schedules',  $this->app->components->schedule->getRecords('schedule_id', 'DESC', 0, false, null, null, null, null, null, null, \CMSApplication::$VAR['workorder_id'])  );
$this->app->smarty->assign('workorder_notes',      $this->app->components->workorder->getNotes(\CMSApplication::$VAR['workorder_id'])                                            ); 
$this->app->smarty->assign('workorder_history',    $this->app->components->workorder->getHistory(\CMSApplication::$VAR['workorder_id'])                                          );
$this->app->smarty->assign('selected_date',        $this->app->system->general->timestampToCalendarFormat( time() )                                                   );
$this->app->smarty->assign('GoogleMapString',      $this->app->components->client->buildGooglemapDirectionsURL($workorder_details['client_id'], $this->app->user->login_user_id) );