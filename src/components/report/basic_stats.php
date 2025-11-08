<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Employee Workorder Stats (Logged in user)
$this->app->smarty->assign('employee_workorder_current_stats', $this->app->components->report->workorderGetStats('current', null, null, $this->app->user->login_user_id));
$this->app->smarty->assign('employee_workorder_historic_stats', $this->app->components->report->workorderGetStats('historic', null, null, $this->app->user->login_user_id));

// Global Client Stats
$this->app->smarty->assign('global_client_historic_stats', $this->app->components->report->clientGetStats('historic'));

// Global Workorder Stats
$this->app->smarty->assign('global_workorder_current_stats', $this->app->components->report->workorderGetStats('current'));
$this->app->smarty->assign('global_workorder_historic_stats', $this->app->components->report->workorderGetStats('historic'));

// Global Invoice Stats
$this->app->smarty->assign('global_invoice_current_stats', $this->app->components->report->invoiceGetStats('current'));
$this->app->smarty->assign('global_invoice_historic_stats', $this->app->components->report->invoiceGetStats('historic'));
$this->app->smarty->assign('global_invoice_revenue_stats', $this->app->components->report->invoiceGetStats('revenue'));

// Global Voucher Stats
$this->app->smarty->assign('global_voucher_revenue_stats', $this->app->components->report->voucherGetStats('revenue'));

// Global Payment Stats
$this->app->smarty->assign('global_payment_revenue_stats', $this->app->components->report->paymentGetStats('revenue'));

$this->app->smarty->assign('tax_systems', $this->app->components->company->getTaxSystems());
