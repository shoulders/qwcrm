<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'client.php');

// check if we have a client_note_id
if(!isset(\QFactory::$VAR['client_note_id']) || !\QFactory::$VAR['client_note_id']) {
    force_page('client', 'search', 'warning_msg='._gettext("No Client Note ID supplied."));
}

// If record submitted for updating
if(isset(\QFactory::$VAR['submit'])) {
               
    update_client_note(\QFactory::$VAR['client_note_id'], \QFactory::$VAR['note']);
    force_page('client', 'details&client_id='.get_client_note(\QFactory::$VAR['client_note_id'], 'client_id'));   
    
} else {    
    
    // Fetch and load the page
    $smarty->assign('client_note', get_client_note(\QFactory::$VAR['client_note_id']));
    \QFactory::$BuildPage .= $smarty->fetch('client/note_edit.tpl');
    
}


