<?php

// Set the minmu length before lookups occur
if(!strlen($_POST['queryString']) >= 4) {
    die();
}

// required include files
require('configuration.php');
require('includes/defines.php');
require(INCLUDES_DIR.'security.php');
//require(INCLUDES_DIR.'include.php');
//require(INCLUDES_DIR.'email.php');
require(INCLUDES_DIR.'adodb.php');
//require(INCLUDES_DIR.'smarty.php');
//require(FRAMEWORK_DIR.'qwframework.php');

//require ('../../configuration.php');
//require ('../../includes/defines.php');

$user = QFactory::getUser();


// Logged in and not Public or Guest
if (QFactory::getUser()->login_token && QFactory::getUser()->usergroup_id != '8' && QFactory::getUser()->usergroup_id != '9'){    

    
    if(!$db = new mysqli( $db_host, $db_user, $db_pass, $db_name)){
        echo 'ERROR: Could not connect to the database.';
    } else {

        // Is there a posted query string?
        if(isset($_POST['queryString'])) {

            // This might not be needed if not passed through security
            $queryString = $db->real_escape_string($_POST['queryString']);

            // Is the string length greater than 0?
            if(strlen($queryString) > 0) {
                /* 
                 * Run the query: We use LIKE '$queryString%'
                 * The percentage sign is a wild-card, in my example of countries it works like this...
                 * $queryString = 'Uni';
                 * Returned data = 'United States, United Kingdom';

                 * YOU NEED TO ALTER THE QUERY TO MATCH YOUR DATABASE.
                 * eg: SELECT yourColumnName FROM yourTable WHERE yourColumnName LIKE '$queryString%' LIMIT 10
                 * 
                 */

                $query = $db->query("SELECT scope FROM ".PRFX."workorder WHERE scope LIKE '$queryString%' LIMIT 10");

                if($query) {

                    // While there are results loop through them - fetching an Object (i like PHP5 btw!).
                    while ($result = $query ->fetch_object()) {

                        /* 
                         * Format the results, im using <li> for the list, you can change it.
                         * The onClick function fills the textbox with the result.
                         * YOU MUST CHANGE: $result->value to $result->your_column
                         */
                         echo '<li onClick="fill(\''.$result->scope.'\');">'.$result->scope.'</li>';

                     }
                } else {
                    echo 'ERROR: There was a problem with the query.';
                }
            } else {
                    // Dont do anything.
            }
        } else {
            echo 'There should be no direct access to this script!';
        }
    }
} else {
    //echo '<!DOCTYPE html><title></title>'; //Joomla Version
    header('HTTP/1.1 404 Not Found');
}