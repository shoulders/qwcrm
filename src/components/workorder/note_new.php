<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(CINCLUDES_DIR.'client.php');
require(CINCLUDES_DIR.'workorder.php');

// Check if we have a workorder_id
if(!isset(\CMSApplication::$VAR['workorder_id']) || !\CMSApplication::$VAR['workorder_id']) {
    systemMessagesWrite('danger', _gettext("No Workorder ID supplied."));
    force_page('workorder', 'search');
}

// If a note is submitted
if(isset(\CMSApplication::$VAR['submit'])){
    
    // insert the note into the database
    insert_workorder_note(\CMSApplication::$VAR['workorder_id'], \CMSApplication::$VAR['workorder_note']);
    
    // load the workorder details page    
    systemMessagesWrite('success', _gettext("The note has been inserted."));
    force_page('workorder', 'details&workorder_id='.\CMSApplication::$VAR['workorder_id']);
    
}