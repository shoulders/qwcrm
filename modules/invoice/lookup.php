<?php
//look up the record based on email and get the firstname and lastname
...

//build the JSON array for return
$json = array(array('field' => 'first_name', 
                    'value' => $firstName), 
              array('field' => 'last_name', 
                    'value' => $last_name));
echo json_encode($json );
?>

