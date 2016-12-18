<?php
####################################################
#  This program is distributed under the terms and    #
#  conditions of the GPL                                        #
#  Schedule Delete                                                #
#  Version 0.0.2    2:18 PM Monday, 6 April 2009        #
#                                                                    #
####################################################
$schedule_id = $VAR['schedule_id'];
$y =    $VAR['y'];
$m =    $VAR['m'];
$d =    $VAR['d'];

    $q = "DELETE FROM ".PRFX."TABLE_SCHEDULE WHERE SCHEDULE_ID =".$db->qstr($schedule_id);
        
        if(!$rs = $db->execute($q)) {
            force_page('core', 'error&error_msg=MySQL Error: '.$db->ErrorMsg().'&menu=1&type=database');
            exit;
        } else {
            force_page('schedule', 'main&y='.$y.'&m='.$m.'&d='.$d.'&workorder_id='.$VAR['workorder_id']);
            exit;
        }