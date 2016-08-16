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
                force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
                exit;    
            }

            $values = '';

        }

    }
    
    // Make Error Page (core:error) always be accessible to all account types 
    $q = "UPDATE ".PRFX."ACL SET `Administrator`= 1, `Manager`=1, `Supervisor`=1,`Technician`=1, `Client`=1, `Guest`=1 WHERE`page`= 'core:error'";
    if(!$rs = $db->execute($q)) {
        force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
        exit;    
    }
        
    force_page('control', 'acl&msg=Permisions Updated');

} else {
    $q = "SELECT * FROM ".PRFX."ACL ORDER BY page";
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        }
    $arr = $rs->GetArray();
    //print_r($arr);
    $smarty->assign( 'acl', $arr );
    $smarty->display('control'.SEP.'acl.tpl');
}