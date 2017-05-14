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
        
        /*
         * In order to kill the session altogether, such as to log the user out, the session id
         * must also be unset. If a cookie is used to propagate the session id (default behavior),
         * then the session cookie must be deleted.
         */
        
        // Destroy Session Cookie
        //$cookie_params = session_get_cookie_params();
        //setcookie(session_name(), '', time() - 42000, $cookie_params['path'], $cookie_params['domain'], $cookie_params['secure'], $cookie_params['httponly']);
        
        // Destroy Session - removes session data stored on the server for that session id and requests the client to delete that cookie
        session_unset();
        session_destroy();
        
    }
    
}