<?php

 // Delete - do i need to add the $VAR check
if($VAR['action'] == 'delete') {
    delete_giftcert($db, $giftcert_id);
}
