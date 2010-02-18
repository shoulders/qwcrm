<?php
if(!xml2php("workorder")) {
    $smarty->assign('error_msg',"Error in language file");
}
require_once ("include.php");

$submit      = $VAR['submit'];
$email      = $VAR['email'];
$customer_id = $VAR['customer_id'];

/* Lets Grab Technicians Names */
$q = "SELECT EMPLOYEE_LOGIN, EMPLOYEE_ID FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS=1";
	if(!$rs = $db->execute($q)) {
		force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
		exit;
	}
        $tech=$rs->GetMenu2('created_by', $login,$login_id);
$smarty->assign('tech', $tech);

if (isset($VAR['submit'])) {
    if (!insert_new_workorder($db,$VAR)) {
        $smarty->display('workorder'.SEP.'new.tpl');
    }

} else {
    // Grab customers Information
    if(!isset($customer_id)) {
        // redirect to customer search page
        //header ("location", "?page=customer:view");
    } else {
        $smarty->assign('customer_details', display_customer_info($db, $customer_id));
    }

    $smarty->display('workorder'.SEP.'new.tpl');
}
if (isset($VAR['email'])) {
    if (!email_new_workorder($db,$VAR)) {
        $smarty->display('workorder'.SEP.'new.tpl');
    }
}


?>
