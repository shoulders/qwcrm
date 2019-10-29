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
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No Voucher ID supplied."));
    $this->app->system->general->force_page('otherincome', 'search');
}

// Update Voucher Status
if(isset(\CMSApplication::$VAR['change_status'])){
    $this->app->components->otherincome->update_otherincome_status(\CMSApplication::$VAR['otherincome_id'], \CMSApplication::$VAR['assign_status']);    
    $this->app->system->general->force_page('otherincome', 'status&otherincome_id='.\CMSApplication::$VAR['otherincome_id']);
}

// Build the page with the current status from the database
$this->app->smarty->assign('allowed_to_change_status',     false       );
$this->app->smarty->assign('otherincome_status',              $this->app->components->otherincome->get_otherincome_details(\CMSApplication::$VAR['otherincome_id'], 'status')             );
$this->app->smarty->assign('otherincome_statuses',            $this->app->components->otherincome->get_otherincome_statuses() );
$this->app->smarty->assign('allowed_to_cancel',            $this->app->components->otherincome->check_otherincome_can_be_cancelled(\CMSApplication::$VAR['otherincome_id'])    );
$this->app->smarty->assign('allowed_to_delete',            $this->app->components->otherincome->check_otherincome_can_be_deleted(\CMSApplication::$VAR['otherincome_id'])              );
$this->app->smarty->assign('otherincome_selectable_statuses',     $this->app->components->otherincome->get_otherincome_statuses(true));