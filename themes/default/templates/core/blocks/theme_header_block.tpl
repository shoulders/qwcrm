<!-- theme_header_block.tpl -->
<!DOCTYPE html>
<html lang="en-GB">
<head>
    
    <title>{$page_title}</title>
    
    <meta charset="utf-8">
    <!--<base href="http://quantumwarp.com/">--> 
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <meta name="description" content="{$meta_description}">
    <meta name="keywords" content="{$meta_keywords}">
    
    <link rel="icon" href="favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">
    <link rel="stylesheet" href="{$theme_css_dir}sdmenu.css">
    
    <script src="{$theme_js_dir}modules/core/sdmenu.js"></script>
    <script src="{$theme_js_dir}modules/core/tabs.js"></script>
    <script src="{$theme_js_dir}modules/core/jquery.min.js"></script>
    {literal}
    <script>
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
    <script src="{$theme_js_dir}modules/core/dhtml.js"></script>
    <div class="text4">
        <table width="900px" border="0" cellspacing="0" cellpadding="0">
            <tr class="text4">
                <td width="450" class="text4" align="left">{$greeting_msg}</td>         
                <td class="text4" align="right">{$todays_date}</td>
            </tr>
        </table>  
    </div>

    <!-- Information Message -->
    {if $information_msg != ''}
    <table width="900px" border="0" cellpadding="4" cellspacing="4">
        <tr>
            <td>
                <table class="olotablegreen" width="100%" border="0" cellpadding="5" cellspacing="5" style="text-align: center;">
                    <tr>
                        <td valign="middle">{$information_msg}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {/if}

    <!-- Warning Message -->
    {if $warning_msg != ''}
    <table width="900px" border="0" cellpadding="4" cellspacing="4">
        <tr>
            <td>
                <table width="100%" border="0" cellpadding="5" cellspacing="5" style="text-align: center;">
                    <tr>
                        <td valign="middle"class="error">{$warning_msg}</td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    {/if}