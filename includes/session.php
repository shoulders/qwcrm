<?php

defined('_QWEXEC') or die;

class Session {

    function Session(){
        session_start();
    }

    function set($name, $value){
        $_SESSION[$name] = $value;
    }

    function get($name){
        if (isset($_SESSION[$name])){
            return $_SESSION[$name];
        } else {
            return false;
        }
    }

    function del($name){
        unset($_SESSION[$name]);
    }

    function destroy(){
        
        // Destroy Session Cookie
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
        
        // Destroy Session - removes session data stored on the server for that session id and requests the client to delete that cookie
        session_destroy();
    }
    
}