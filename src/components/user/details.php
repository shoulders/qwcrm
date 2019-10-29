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

// Check if we have an user_id
if(!isset(\CMSApplication::$VAR['user_id']) || !\CMSApplication::$VAR['user_id']) {
    $this->app->system->variables->systemMessagesWrite('danger', _gettext("No User ID supplied."));
    $this->app->system->general->force_page('user', 'search');
}

// Build the page

$this->app->smarty->assign('user_details',             $this->app->components->user->get_user_details(\CMSApplication::$VAR['user_id'])                                                                            );
$this->app->smarty->assign('client_display_name',      $this->app->components->client->get_client_details($this->app->components->user->get_user_details(\CMSApplication::$VAR['user_id'], 'client_id'), 'client_display_name')                    );
$this->app->smarty->assign('usergroups',               $this->app->components->user->get_usergroups()                                                                                             );
$this->app->smarty->assign('user_workorders',          $this->app->components->workorder->display_workorders('workorder_id', 'DESC', false, '25', \CMSApplication::$VAR['page_no'], null, null, 'open', \CMSApplication::$VAR['user_id']));
$this->app->smarty->assign('user_locations',           $this->app->components->user->get_user_locations());