<?php
if(isset($VAR['submit'])) {
    
    foreach($_POST as $acl_page => $val){

        if($acl_page != 'submit') {            
                
            foreach($val as $perm => $acl) {
                $values .= $perm."='".$acl."',";                
            }

            // Enforce Administrators always have access to everything
            $values .= "Administrator='1' ";

            $q = "UPDATE ".PRFX."ACL SET ".$values."WHERE page='".$acl_page."'";

            if(!$rs = $db->execute($q)) {
                force_page('core', 'error','error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                exit;    
            }

            $values = '';

        }

    }    
   
    // Make these pages permissions available to all User Account Types - This prevents systems errors
    $q = "UPDATE ".PRFX."ACL SET `Administrator`= 1, `Manager`=1, `Supervisor`=1,`Technician`=1, `Clerical`=1, `Counter`=1, `Customer`=1, `Guest`=1, `Public`=1
            WHERE `page`= 'core:error'
            OR `page`= 'core:404'
            OR `page`= 'core:login'
            OR `page`= 'core:maintenance'
            OR `page`= 'user:password_reset'            
            ";            

    if(!$rs = $db->execute($q)) {
        force_page('core', 'error','error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;    
    }   
    
    force_page('administrator', 'acl', 'information_msg=Permisions Updated');

} else {
    $q = "SELECT * FROM ".PRFX."ACL ORDER BY page";
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error', 'error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }
    $arr = $rs->GetArray();    
    $smarty->assign('acl', $arr);
    
    $smarty->display('administrator'.SEP.'acl.tpl');
}