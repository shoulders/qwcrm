<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Employee Workorder Stats (Logged in user)
$this->app->smarty->assign('employee_workorder_current_stats', $this->app->components->report->getWorkordersStats('current', null, null, $this->app->user->login_user_id));
$this->app->smarty->assign('employee_workorder_historic_stats', $this->app->components->report->getWorkordersStats('historic', null, null, $this->app->user->login_user_id));

// Global Client Stats
$this->app->smarty->assign('global_client_historic_stats', $this->app->components->report->getClientsStats('historic'));

// Global Workorder Stats
$this->app->smarty->assign('global_workorder_current_stats', $this->app->components->report->getWorkordersStats('current'));
$this->app->smarty->assign('global_workorder_historic_stats', $this->app->components->report->getWorkordersStats('historic'));

// Global Invoice Stats
$this->app->smarty->assign('global_invoice_current_stats', $this->app->components->report->getInvoicesStats('current'));
$this->app->smarty->assign('global_invoice_historic_stats', $this->app->components->report->getInvoicesStats('historic'));
$this->app->smarty->assign('global_invoice_revenue_stats', $this->app->components->report->getInvoicesStats('revenue'));

// Global Payment Stats
$this->app->smarty->assign('global_payment_revenue_stats', $this->app->components->report->getPaymentsStats('revenue'));