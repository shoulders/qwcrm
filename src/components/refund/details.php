<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a refund_id
if(!isset(\CMSApplication::$VAR['refund_id']) || !\CMSApplication::$VAR['refund_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
    $this->app->system->page->forcePage('refund', 'search');
} 

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods()                                                             ); 
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses()                                                                              );
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, 'refund', null, null, null, null, null, \CMSApplication::$VAR['refund_id']));

// Build the page
$refund_details = $this->app->components->refund->getRecord(\CMSApplication::$VAR['refund_id']);
$this->app->smarty->assign('refund_statuses', $this->app->components->refund->getStatuses()  );
$this->app->smarty->assign('refund_types', $this->app->components->refund->getTypes());
$this->app->smarty->assign('refund_details', $refund_details);
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->getVatTaxCodes() );
$this->app->smarty->assign('client_display_name', $this->app->components->client->getRecord($refund_details['client_id'], 'display_name'));