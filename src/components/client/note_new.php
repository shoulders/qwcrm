<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');

// Check if we have a client_id
if(!isset($VAR['client_id']) || !$VAR['client_id']) {
    force_page('client', 'search', 'warning_msg='._gettext("No Client ID supplied."));
    exit;
}

// Insert the client note
if(isset($VAR['submit'])) {   
    
    insert_client_note($VAR['client_id'], $VAR['note']);    
    force_page('client', 'details&client_id='.$VAR['client_id']);    

// Build the page  
} else {  

    $BuildPage .= $smarty->fetch('client/note_new.tpl');

}

