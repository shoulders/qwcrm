<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a otherincome_id
if(!isset(\CMSApplication::$VAR['otherincome_id']) || !\CMSApplication::$VAR['otherincome_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Refund ID supplied."));
    $this->app->system->general->force_page('otherincome', 'search');
} 

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->get_payment_types()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->get_payment_methods()                                                             ); 
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->get_payment_statuses()                                                                              );
$this->app->smarty->assign('display_payments',         $this->app->components->payment->display_payments('payment_id', 'DESC', false, null, null, null, null, 'otherincome', null, null, null, null, null, null, null, \CMSApplication::$VAR['otherincome_id']));

// Build the page
$this->app->smarty->assign('otherincome_statuses', $this->app->components->otherincome->get_otherincome_statuses());
$this->app->smarty->assign('otherincome_types', $this->app->components->otherincome->get_otherincome_types());
$this->app->smarty->assign('vat_tax_codes', $this->app->components->company->get_vat_tax_codes() );
$this->app->smarty->assign('otherincome_details', $this->app->components->otherincome->get_otherincome_details(\CMSApplication::$VAR['otherincome_id']));