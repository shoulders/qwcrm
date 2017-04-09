<?php

require(INCLUDES_DIR.'modules/company.php');

// Update Company details
if(isset($VAR['submit'])) {
    
    // Upload Logo
    upload_company_logo($db);
    
    // Submit data to the database
    update_company_details($db, $VAR);    

}
     
// Fetch page
$smarty->assign('country', get_country_codes($db));
$smarty->assign('company', get_company_details($db));
$BuildPage .= $smarty->fetch('company/edit.tpl');
