<?php

################################
# Display Customer Details     #
################################

function display_customer_info($db, $customer_id){
    
    global $smarty;
    
    $sql = "SELECT * FROM ".PRFX."TABLE_CUSTOMER WHERE CUSTOMER_ID=".$db->qstr($customer_id);
    
    if(!$rs = $db->Execute($sql)) {
        force_error_page($_GET['page'], 'database', __FILE__, __FUNCTION__, $db->ErrorMsg(), $sql, $smarty->get_template_vars('translate_workorder_error_message_function_'.__FUNCTION__.'_failed'));
        exit;
    } else {
        
       return $rs->GetArray();  
       
    }   
    
}