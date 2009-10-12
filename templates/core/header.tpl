<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" 
  "http://www.w3.org/TR/html4/loose.dtd">

<html lang="en" >
  <head>
    <title>
      {$page_title}
    </title>
    <link rel="shortcut icon" href="/favicon.ico">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" href="css/default.css" type="text/css" />
    <link rel="stylesheet" href="css/sdmenu.css" type="text/css" />
    <script type="text/javascript" src="js/sdmenu.js"></script>
    <!-- TODO - Testing out tabbed menu in customers details in IE678 -->
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/tabs.js"></script>
     {literal}
    <script type="text/javascript">
    //<![CDATA[
    var myMenu;
    window.onload = function() { 
    myMenu = new SDMenu("my_menu");
    myMenu.init();
    };   
    //]]>

    </script>
     {/literal}
   </head>
  <body>
    <a name="top"></a>
    <div id="dhtmltooltip"></div><script type="text/javascript" src="js/dhtml.js">
</script>
    <table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td>
          {$translate_core_loged_in} <a href="?page=employees:employee_details&amp;employee_id={$login_id}">{$login}</a>
          <br>
          <a>My IP is {$ip}</a>
        </td>
        <td class="menutd" align="right">
          {if $error_msg != ""}<br />
          {include file="core/error_header.tpl"}<br />
          {/if}
        </td>
        <td align="right">
            <img src="images/logo.jpg" alt="" height="55" />
        </td>
      </tr>
    </table>
    <table width="100%" border="0" cellspacing="0" cellpadding="0" >
      <tr class="text4" >
        <td>
          <span class="text4">{$today}</span>
        </td>
        <td width="100%">
        </td>
      </tr>
      <tr>
        <td width="150" class="left_window" valign="top">

      
