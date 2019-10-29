<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Global Workorder Stats
$this->app->smarty->assign('global_workorder_current_stats', $this->app->components->report->get_workorders_stats('current'));
$this->app->smarty->assign('global_workorder_historic_stats', $this->app->components->report->get_workorders_stats('historic'));

// Global Invoice Stats
$this->app->smarty->assign('global_invoice_current_stats', $this->app->components->report->get_invoices_stats('current'));
$this->app->smarty->assign('global_invoice_historic_stats', $this->app->components->report->get_invoices_stats('historic'));

// Global Client Stats
$this->app->smarty->assign('global_client_historic_stats', $this->app->components->report->get_clients_stats('historic'));

// Employee Workorder Stats (Logged in user)
$this->app->smarty->assign('employee_workorder_current_stats', $this->app->components->report->get_workorders_stats('current', null, null, $user->login_user_id));
$this->app->smarty->assign('employee_workorder_historic_stats', $this->app->components->report->get_workorders_stats('historic', null, null, $user->login_user_id));