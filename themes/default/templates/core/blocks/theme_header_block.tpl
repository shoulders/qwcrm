<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
        "http://www.w3.org/TR/html4/loose.dtd">
<meta http-equiv="X-UA-Compatible" content="IE=8" />
<html lang="en">
<head>
    <title>
    {$page_title}
    </title>
    <link rel="shortcut icon" href="/favicon.ico">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <link rel="stylesheet" href="{$theme_css_dir}template.css" type="text/css"/>
    <link rel="stylesheet" href="{$theme_css_dir}sdmenu.css" type="text/css"/>
    <script type="text/javascript" src="js/sdmenu.js"></script>
    <script type="text/javascript" src="js/jquery.min.js"></script>
    <script type="text/javascript" src="js/tabs.js"></script>
{literal}
    <script type="text/javascript">
        var myMenu;
        window.onload = function() {
            myMenu = new SDMenu("my_menu");
            myMenu.init();
        };
    </script>
{/literal}
</head>
<body>
<a name="top"></a>

<div id="dhtmltooltip"></div>
<script type="text/javascript" src="js/dhtml.js">
</script>

<div class="text4">
  <table width="900px" border="0" cellspacing="0" cellpadding="0">
    <tr class="text4">
        <td width="450" class="text4" align="left">{$greeting_msg}</td>         
        <td class="text4" align="right">{$today}</td>
    </tr>
</table>  
</div>


<!-- BOF Wrapping Table -->
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td width="150" class="left_window" valign="top">
            
            <!-- Menu Goes Here -->