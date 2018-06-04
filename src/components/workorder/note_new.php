<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'components/customer.php');
require(INCLUDES_DIR.'components/workorder.php');

// Check if we have a workorder_id
if($VAR['workorder_id'] == '') {
    force_page('workorder', 'search', 'warning_msg='._gettext("No Workorder ID supplied."));
}

// If a note is submitted
if(isset($VAR['submit'])){
    
    // insert the note into the database
    insert_workorder_note($db, $VAR['workorder_id'], $VAR['workorder_note']);
    
    // load the workorder details page    
    force_page('workorder', 'details&workorder_id='.$VAR['workorder_id'], 'information_msg='._gettext("The note has been inserted."));
    
}
    
// Build the page
$BuildPage .= $smarty->fetch('workorder/note_new.tpl');