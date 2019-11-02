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

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    $this->app->system->page->force_page('client', 'search');
}

// Build the page
$this->app->smarty->assign('client_types',             $this->app->components->client->get_client_types()                                                                                                    );
$this->app->smarty->assign('client_details',           $this->app->components->client->get_client_details(\CMSApplication::$VAR['client_id'])                                                                                 );
$this->app->smarty->assign('client_notes',             $this->app->components->client->get_client_notes(\CMSApplication::$VAR['client_id'])                                                                                );

$this->app->smarty->assign('GoogleMapString',          $this->app->components->client->build_googlemap_directions_string(\CMSApplication::$VAR['client_id'], $this->app->user->login_user_id)                                                     );

$this->app->smarty->assign('workorder_statuses',       $this->app->components->workorder->get_workorder_statuses()                                                                                             );
$this->app->smarty->assign('workorders_open',          $this->app->components->workorder->display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'open', null, \CMSApplication::$VAR['client_id'])          );
$this->app->smarty->assign('workorders_closed',        $this->app->components->workorder->display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'closed', null, \CMSApplication::$VAR['client_id'])       );
$this->app->smarty->assign('workorder_stats',          $this->app->components->report->get_workorders_stats('all', null, null, null, \CMSApplication::$VAR['client_id'])  );

$this->app->smarty->assign('display_schedules',        $this->app->components->schedule->display_schedules('schedule_id', 'DESC', false, null, null, null, null, null, null, \CMSApplication::$VAR['client_id'])  );

$this->app->smarty->assign('invoice_statuses',         $this->app->components->invoice->get_invoice_statuses()                                                                                             );
$this->app->smarty->assign('invoices_open',            $this->app->components->invoice->display_invoices('invoice_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'open', null, \CMSApplication::$VAR['client_id'])           );
$this->app->smarty->assign('invoices_closed',          $this->app->components->invoice->display_invoices('invoice_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'closed', null, \CMSApplication::$VAR['client_id'])            );
$this->app->smarty->assign('invoice_stats',            $this->app->components->report->get_invoices_stats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['client_id'])  );

$this->app->smarty->assign('voucher_statuses',        $this->app->components->voucher->get_voucher_statuses()                                                                                                        );
$this->app->smarty->assign('vouchers_purchased',      $this->app->components->voucher->display_vouchers('voucher_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, null, null, \CMSApplication::$VAR['client_id'])              );
$this->app->smarty->assign('vouchers_claimed',        $this->app->components->voucher->display_vouchers('voucher_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'redeemed', null, null, null, null, \CMSApplication::$VAR['client_id'])        );
$this->app->smarty->assign('voucher_stats',           $this->app->components->report->get_vouchers_stats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['client_id'])  );

$this->app->smarty->assign('payment_types',            $this->app->components->payment->get_payment_types()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->get_payment_methods()                                                                               );
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->get_payment_statuses()                                                                              );
$this->app->smarty->assign('payments_received',        $this->app->components->payment->display_payments('payment_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'received', null, null, null, \CMSApplication::$VAR['client_id'])        );
$this->app->smarty->assign('payments_sent',            $this->app->components->payment->display_payments('payment_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'sent', null, null, null, \CMSApplication::$VAR['client_id'])        );
$this->app->smarty->assign('payment_stats',            $this->app->components->report->get_payments_stats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['client_id'])   );

$this->app->smarty->assign('refund_types',            $this->app->components->refund->get_refund_types()                                                                                 );
$this->app->smarty->assign('refund_statuses',         $this->app->components->refund->get_refund_statuses()                                                                                                        );
$this->app->smarty->assign('display_refunds',         $this->app->components->refund->display_refunds('refund_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, null, null, null, \CMSApplication::$VAR['client_id'])        );
$this->app->smarty->assign('refund_stats',            $this->app->components->report->get_refunds_stats('all', null, null, QW_TAX_SYSTEM, null, \CMSApplication::$VAR['client_id'])   );