<?php
// Name to use for login variable e.g. $_POST['login']
@define('USER_LOGIN_VAR', 'login');
// Name to use for password variable e.g. $_POST['password']
@define('USER_PASSW_VAR', 'password');


class Auth {
  var $session;
  var $redirect;
  var $hashKey;
  var $md5;

  function Auth($db, $redirect, $hashKey, $md5 = true)
  {
	$this->db			= $db;
    $this->redirect = $redirect;
    $this->hashKey  = $hashKey;
    $this->md5      = $md5;
    $this->session  = &new Session();
    $this->login();
  }
   
 
 
  function login()
  {
    // See if we have values already stored in the session
    if ($this->session->get('login_hash')) {
      $this->confirmAuth();
      return;
    }

    // If this is a fresh login, check $_POST variables
    if (!isset($_POST[USER_LOGIN_VAR]) ||
        !isset($_POST[USER_PASSW_VAR])) {
      $this->redirect();
    }

    if ($this->md5) {
      $password = md5($_POST[USER_PASSW_VAR]);
    } else {
      $password = $_POST[USER_PASSW_VAR];
    }

    // Escape the variables for the query
    $login 		= mysql_real_escape_string($_POST[USER_LOGIN_VAR]);
    $password 	= mysql_real_escape_string($password);
  
    // Query to count number of users with this combination
    $sql = "SELECT COUNT(*) AS num_users FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS = '1' AND EMPLOYEE_LOGIN=".$this->db->qstr($login) ." AND EMPLOYEE_PASSWD=".$this->db->qstr($password);
	 $result = $this->db->Execute($sql);
    $row = $result->FetchRow();

    // If there isn't is exactly one entry, redirect
    if ($row['num_users'] != 1) {    
      $this->writeLog('Failed Login',$login);
      $this->force_page('login.php?error_msg=Login Failed');
    // Else is a valid user; set the session variables
    } else {
		/* grab their login ID for tracking purposes */
		$sql = "SELECT EMPLOYEE_ID  FROM ".PRFX."TABLE_EMPLOYEE WHERE EMPLOYEE_STATUS = '1' AND EMPLOYEE_LOGIN='$login'";
		$result = $this->db->Execute($sql);
    	$row = $result->FetchRow();
		
		if (!isset($row['EMPLOYEE_ID'])) { /* We did not get a login ID */
			$this->writeLog('Failed Login ID For ',$login);
      	$this->force_page('login.php?error_msg=Login Failed');
		} else {
			$login_id = $row['EMPLOYEE_ID'];
		}
	
      $this->storeAuth($login, $password, $COMPANY, $login_id);
    }
  }
  

  function storeAuth($login, $password, $COMPANY, $login_id)
  {
    $this->session->set(USER_LOGIN_VAR, $login);
    $this->session->set(USER_PASSW_VAR, $password);
	$this->session->set('login_id', $login_id);
	
    // Create a session variable to use to confirm sessions
    $hashKey = md5($this->hashKey . $login . $password);
    $this->session->set('login_hash', $hashKey);
    
    $this->writeLog('Login', $login);
    
  }
  
  function writeLog ($status, $login)
  {
  // Code to log to a file
    //get current date and time
    $month = date("M");
    $day = date("d");
    $year = date("Y");
    $time =  date("H").":".date("i").":".date("s");
    //get environment variables
    $hostname = gethostbyaddr($_SERVER['REMOTE_ADDR']);
    
    // Create entry
    $data = $status.",".$login.",".$hostname.",".$month."-".$day."-".$year.",".$time.",\n";
    // Write File
    $fp = fopen(ACCESS_LOG,'a') or die("can't open access.log: $php_errormsg");
    fwrite($fp, $data);
    fclose($fp);
  }
 
  function confirmAuth()
  {
    $login = $this->session->get(USER_LOGIN_VAR);
    $password = $this->session->get(USER_PASSW_VAR);
    $hashKey = $this->session->get('login_hash');
    if (md5($this->hashKey . $login . $password) != $hashKey)
    {
      $this->logout(true);
    }
  }
  
 
  function logout($from)
  {
    $login = $this->session->get(USER_LOGIN_VAR);
    $this->writeLog('Log Out', $login);
    $this->session->del(USER_LOGIN_VAR);
    $this->session->del(USER_PASSW_VAR);
    $this->session->del('login_hash');
    $this->redirect($from);
  }
  
 
  function redirect($from = true)
  {
    if ($from) {
      header('Location: ' . $this->redirect . '?from=' .
             $_SERVER['REQUEST_URI']);
    } else {
      header('Location: ' . $this->redirect);
    }
    exit();
  }

  
	function force_page($page) {
			echo("
				<script type=\"text/javascript\">
					<!--
					window.location = \"$page\"
					//-->
				</script>");
	}
}

function force_page($module, $cur_page) {
    echo("
		<script type=\"text/javascript\">
			<!--
			window.location = \"index.php?page=$module:$cur_page\"
			//-->
		</script>");
}
?>
