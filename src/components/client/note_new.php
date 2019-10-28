<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

// Check if we have a client_id
if(!isset(\CMSApplication::$VAR['client_id']) || !\CMSApplication::$VAR['client_id']) {
    systemMessagesWrite('danger', _gettext("No Client ID supplied."));
    force_page('client', 'search');
    exit;
}

// Insert the client note
if(isset(\CMSApplication::$VAR['submit'])) {   
    
    insert_client_note(\CMSApplication::$VAR['client_id'], \CMSApplication::$VAR['note']);    
    force_page('client', 'details&client_id='.\CMSApplication::$VAR['client_id']);    

} else {  

}

