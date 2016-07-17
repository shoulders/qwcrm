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
    <link rel="stylesheet" href="css/default.css" type="text/css"/>
    <link rel="stylesheet" href="css/sdmenu.css" type="text/css"/>
    <script type="text/javascript" src="js/sdmenu.js"></script>
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

<div id="dhtmltooltip"></div>
<script type="text/javascript" src="js/dhtml.js">
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>

        <td class="menutd" align="right">
        {if $error_msg != ""}<br/>
        {include file="core/error_header.tpl"}<br/>
        {/if}
        </td>

    </tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr class="text4" width="100%">
        <td>
            <span class="text4"></span>
        </td>
        <td width="100%" class="text4" align="center">
        >IP {$ip}<|>{$today}<|>{if $mine != 0 || $mine2 != 0 || $mine3 != 0 || $mine4 != 0}
            and {$translate_main_you_have} {/if}
            {if $mine != 0}&rArr;{$mine} {$translate_core_open_workorders}{/if}
        {if $mine2 != 0}& &rArr;{$mine2} {$translate_core_assigned_workorders}{/if}
        {if $mine3 != 0}& &rArr;{$mine3} {$translate_main_payment}{/if}
        {if $mine4 != 0}& &rArr;{$mine4} {$translate_core_waiting_payment}{/if}
        </td>
    </tr>
    <tr>
        <td width="150" class="left_window" valign="top">

      
