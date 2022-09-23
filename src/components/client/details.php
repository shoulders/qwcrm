<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Prevent undefined variable errors
\CMSApplication::$VAR['page_no'] = \CMSApplication::$VAR['page_no'] ?? null;

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    $this->app->system->page->forcePage('client', 'search');
}

// Build the page
$this->app->smarty->assign('client_types',             $this->app->components->client->getTypes()                                                                                                    );
$this->app->smarty->assign('client_details',           $this->app->components->client->getRecord(\CMSApplication::$VAR['client_id'])                                                                                 );
$this->app->smarty->assign('client_notes',             $this->app->components->client->getNotes(\CMSApplication::$VAR['client_id'])                                                                                );

$this->app->smarty->assign('GoogleMapString',          $this->app->components->client->buildGooglemapDirectionsURL(\CMSApplication::$VAR['client_id'], $this->app->user->login_user_id)                                                     );

$this->app->smarty->assign('workorder_statuses',       $this->app->components->workorder->getStatuses()                                                                                             );
$this->app->smarty->assign('workorders_open',          $this->app->components->workorder->getRecords('workorder_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'open', null, \CMSApplication::$VAR['client_id'])          );
$this->app->smarty->assign('workorders_closed',        $this->app->components->workorder->getRecords('workorder_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'closed', null, \CMSApplication::$VAR['client_id'])       );
$this->app->smarty->assign('workorder_stats',          $this->app->components->report->getWorkordersStats('all', null, null, null, \CMSApplication::$VAR['client_id'])  );

$this->app->smarty->assign('display_schedules',        $this->app->components->schedule->getRecords('schedule_id', 'DESC', 25, null, null, null, null, null, null, \CMSApplication::$VAR['client_id'])  );

$this->app->smarty->assign('invoice_statuses',         $this->app->components->invoice->getStatuses()                                                                                             );
$this->app->smarty->assign('invoices_open',            $this->app->components->invoice->getRecords('invoice_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'open', null, \CMSApplication::$VAR['client_id'])           );
$this->app->smarty->assign('invoices_closed',          $this->app->components->invoice->getRecords('invoice_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'closed', null, \CMSApplication::$VAR['client_id'])            );
$this->app->smarty->assign('invoice_stats',            $this->app->components->report->getInvoicesStats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['client_id'])  );

$this->app->smarty->assign('voucher_statuses',        $this->app->components->voucher->getStatuses()                                                                                                        );
$this->app->smarty->assign('vouchers_purchased',      $this->app->components->voucher->getRecords('voucher_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, null, null, \CMSApplication::$VAR['client_id'])              );
$this->app->smarty->assign('vouchers_claimed',        $this->app->components->voucher->getRecords('voucher_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'redeemed', null, null, null, null, \CMSApplication::$VAR['client_id'])        );
$this->app->smarty->assign('voucher_stats',           $this->app->components->report->getVouchersStats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['client_id'])  );

$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes());
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods());
$this->app->smarty->assign('payment_directions',       $this->app->components->payment->getDirections());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses());
$this->app->smarty->assign('payment_creditnote_action_types', $this->app->components->payment->getCreditnoteActionTypes());

//$this->app->smarty->assign('payments_received',      $this->app->components->payment->getRecords('payment_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'monies_received', null, 'monies_received', null, null, \CMSApplication::$VAR['client_id'], null, null, null, null, null, 'monies_received'));
//$this->app->smarty->assign('payments_sent',          $this->app->components->payment->getRecords('payment_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, 'monies_sent', null, 'monies_sent', null, null, \CMSApplication::$VAR['client_id'], null, null, null, null, null, 'monies_sent'));
$this->app->smarty->assign('payments_credits',         $this->app->components->payment->getRecords('payment_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, null, null, 'credit', null, null, \CMSApplication::$VAR['client_id']));
$this->app->smarty->assign('payments_debits',          $this->app->components->payment->getRecords('payment_id', 'DESC', 25, false, \CMSApplication::$VAR['page_no'], null, null, null, null, 'debit', null, null, \CMSApplication::$VAR['client_id']));
$this->app->smarty->assign('payment_stats',           $this->app->components->report->getPaymentsStats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['client_id'])   );

$this->app->smarty->assign('creditnote_types',        $this->app->components->creditnote->getTypes());
$this->app->smarty->assign('creditnote_statuses',     $this->app->components->creditnote->getStatuses());
$this->app->smarty->assign('creditnote_stats',        $this->app->components->report->getCreditnotesStats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['client_id']));
$this->app->smarty->assign('display_creditnotes',     $this->app->components->creditnote->getRecords('creditnote_id', 'DESC', 25, false, null, null, null, null, null, \CMSApplication::$VAR['client_id']));
$this->app->smarty->assign('allowed_to_create_creditnote', $this->app->components->creditnote->checkRecordCanBeCreated(\CMSApplication::$VAR['client_id'], null, null, null));