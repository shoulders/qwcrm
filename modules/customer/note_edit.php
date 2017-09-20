<?php
/**
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
 */

defined('_QWEXEC') or die;

require(INCLUDES_DIR.'modules/customer.php');

// check if we have a customer_note_id
if($VAR['customer_note_id'] == '') {
    force_page('customer', 'search', 'warning_msg='.gettext("No Customer ID supplied."));
    exit;
}

// If record submitted for updating
if(isset($VAR['submit'])) {
               
    update_customer_note($db, $VAR['customer_note_id'], $VAR['note']);
    force_page('customer', 'details&customer_id='.$customer_id);   
    exit;
    
} else {    
    
    // Fetch and load the page
    $smarty->assign('customer_note', get_customer_note($db, $VAR['customer_note_id']));
    $BuildPage .= $smarty->fetch('customer/note_edit.tpl');
    
}


