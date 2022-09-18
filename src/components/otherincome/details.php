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
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Other Income ID supplied."));
    $this->app->system->page->forcePage('otherincome', 'search');
} 

// Payment Details
$this->app->smarty->assign('payment_types',            $this->app->components->payment->getTypes()                                                                                 );
$this->app->smarty->assign('payment_methods',          $this->app->components->payment->getMethods()                                                             ); 
$this->app->smarty->assign('payment_directions',       $this->app->components->payment->getDirections());
$this->app->smarty->assign('payment_statuses',         $this->app->components->payment->getStatuses()                                                                              );
$this->app->smarty->assign('payment_creditnote_action_types', $this->app->components->payment->getCreditnoteActionTypes());
$this->app->smarty->assign('display_payments',         $this->app->components->payment->getRecords('payment_id', 'DESC', 0, false, null, null, null, 'otherincome', null, null, null, null, null, null, null, \CMSApplication::$VAR['otherincome_id']));

// Build the page
$this->app->smarty->assign('otherincome_statuses', $this->app->components->otherincome->getStatuses());
$this->app->smarty->assign('otherincome_types', $this->app->components->otherincome->getTypes());
$this->app->smarty->assign('otherincome_details', $this->app->components->otherincome->getRecord(\CMSApplication::$VAR['otherincome_id']));