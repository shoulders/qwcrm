<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have an payment_id
if(!isset(\CMSApplication::$VAR['payment_id']) || !\CMSApplication::$VAR['payment_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Payment ID supplied."));
    $this->app->system->page->forcePage('payment', 'search');
}

$payment_details = $this->app->components->payment->getRecord(\CMSApplication::$VAR['payment_id']);

// Build the page
$this->app->smarty->assign('employee_display_name',    $this->app->components->user->getRecord($payment_details['employee_id'], 'display_name'));
$this->app->smarty->assign('client_display_name',      $this->app->components->client->getRecord($payment_details['client_id'], 'display_name'));
$this->app->smarty->assign('supplier_display_name',    $this->app->components->supplier->getRecord($payment_details['supplier_id'], 'display_name'));
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes());
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods());
$this->app->smarty->assign('payment_directions',       $this->app->components->payment->getDirections());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses());
$this->app->smarty->assign('payment_details',          $payment_details);
