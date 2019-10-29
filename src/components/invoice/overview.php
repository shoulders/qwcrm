<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Build the page
$this->app->smarty->assign('overview_invoices_pending',            $this->app->components->invoice->display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'pending')           );
$this->app->smarty->assign('overview_invoices_unpaid',             $this->app->components->invoice->display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'unpaid')            );
$this->app->smarty->assign('overview_invoices_partially_paid',     $this->app->components->invoice->display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'partially_paid')    );
//$this->app->smarty->assign('overview_invoices_paid',               $this->app->components->invoice->display_invoices('invoice_id', 'DESC', true, '15', '1', null, null, 'paid')                );
$this->app->smarty->assign('overview_invoices_in_dispute',         $this->app->components->invoice->display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'in_dispute')        );
$this->app->smarty->assign('overview_invoices_overdue',            $this->app->components->invoice->display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'overdue')           );
$this->app->smarty->assign('overview_invoices_collections',        $this->app->components->invoice->display_invoices('invoice_id', 'DESC', false, null, null, null, null, 'collections')       );
//$this->app->smarty->assign('overview_invoices_refunded',           $this->app->components->invoice->display_invoices('invoice_id', 'DESC', true, '15', '1', null, null, 'refunded')            );
//$this->app->smarty->assign('overview_invoices_cancelled',          $this->app->components->invoice->display_invoices('invoice_id', 'DESC', true, '15', '1', null, null, 'cancelled')           );
$this->app->smarty->assign('overview_invoice_stats',               $this->app->components->report->get_invoices_stats('current'));
$this->app->smarty->assign('invoice_statuses',                     $this->app->components->invoice->get_invoice_statuses());