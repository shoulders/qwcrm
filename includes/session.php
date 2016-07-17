<?php

class Session {

  function Session()
  {
    session_start();
  }

  
  function set($name, $value)
  {
    $_SESSION[$name] = $value;
  }

  
  function get($name)
  {
    if (isset($_SESSION[$name])) {
      return $_SESSION[$name];
    } else {
      return false;
    }
  }

  
  function del($name)
  {
    unset($_SESSION[$name]);
  }

 
  function destroy()
  {
    $_SESSION = array();
    session_destroy();
  }
} 
?>