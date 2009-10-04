<!-- Begin Login.tpl--><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html>
  <head>
    <title>Login</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <link href="css/default.css" rel="stylesheet" type="text/css">
  </head>
  <body>
    <center>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <img src="images/logo.jpg" alt="" height="114">
          </td>
        </tr>
      </table>
      <table width="100%"  border="0" cellspacing="0" cellpadding="2">
        <tr>
          <td colspan="3" >
            <img src="images/index03.gif" alt="" width="100%" height="40">
          </td>
        </tr>
        <tr>
          <td align="center">
            <p>
               &nbsp;
            </p>
            <table class="olotable"  border="0" align="center">
              <tr>
                <td class="olohead">
                   &nbsp;Login
                </td>
              </tr>
              <tr>
                <td class="olotd">
                  <table  cellspacing="5" border="0" cellpadding="5" align="center">
                    <tr>
                      <td>
                        <form action="index.php" method="POST">
                          <table width="25%" cellspacing="0" border="0" cellpadding="5" align="center">
                            <tr>
                              <td>
                                 Login
                              </td>
                              <td>
                                <input type="text" name="login" size="25" class="olotd5" alt="login">
                              </td>
                            </tr>
                            <tr>
                              <td>
                                 Password
                              </td>
                              <td>
                                <input type="password" name="password" size="25"  class="olotd5" alt="password">
                              </td>
                            </tr>
                            <tr align="center">
                              <td colspan="2">
                                <button type="submit" name="submit"><img src="images/tick.png" alt=""> Login</button>
                              </td>
                            </tr>
                             { if $error_msg != "" }
                            <tr>
                              <td colspan="2" class="error">
                                 {$error_msg}
                              </td>
                            </tr>
							{ /if}
                          </table>
                        </form>
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td align="center" >
                  <span class="text3"><a href="http://www.myitcrm.com"><b>MyIT CRM Version - {$VERSION}</b></a><br><a href="http://www.myitcrm.com"><b>The Best Open Source IT CRM program available!</b></a><br><a>This software is distributed under the GNU General Public License V3</a><br></span>
                </td>
              </tr>              
            </table>
          </td>
        </tr>
      </table>
    </center>
  </body>
</html>
