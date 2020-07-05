<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['page_no'] = isset(\CMSApplication::$VAR['page_no']) ? \CMSApplication::$VAR['page_no'] : null;

// Build the page
$this->app->smarty->assign('overview_workorders_unassigned',        $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'unassigned')        );
$this->app->smarty->assign('overview_workorders_assigned',          $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'assigned')          );
$this->app->smarty->assign('overview_workorders_waiting_for_parts', $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'waiting_for_parts') );
$this->app->smarty->assign('overview_workorders_scheduled',         $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'scheduled')         );
$this->app->smarty->assign('overview_workorders_with_client',       $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'with_client')       );
$this->app->smarty->assign('overview_workorders_on_hold',           $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'on_hold')           );
$this->app->smarty->assign('overview_workorders_management',        $this->app->components->workorder->getRecords('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'management')        );

$this->app->smarty->assign('overview_workorder_stats', $this->app->components->report->getWorkordersStats('current'));
$this->app->smarty->assign('workorder_statuses', $this->app->components->workorder->getStatuses());