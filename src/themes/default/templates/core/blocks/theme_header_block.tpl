<!-- theme_header_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<!DOCTYPE html>
<html lang="en-GB">
<head>
    <meta charset="utf-8">
    <title>{$page_title}</title>
    <!--<base href="//quantumwarp.com/">-->
    <!--<meta name="viewport" content="width=device-width, initial-scale=1.0">-->
    <meta name="description" content="{$meta_description}">
    <meta name="keywords" content="{$meta_keywords}">
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" href="{$base_path}favicon.ico">
    <link rel="stylesheet" href="{$theme_css_dir}template.css">
    <script src="{$theme_js_dir}jquery-3.1.1.min.js"></script>
    <script src="{$theme_js_dir}sdmenu.js"></script>
    <script>
        var myMenu;
        window.onload = function() {
            mainMenu = new SDMenu('main_menu');
            mainMenu.init();
        } ;
    </script>
    <script src="{$theme_js_dir}template.js"></script>
    {if $display_clock}
        <script>

            // Display a 12/24 Hour Clock
            function updateClientClock(elementId) {

                let currentTime = new Date();
                let currentHours = currentTime.getHours();
                let currentMinutes = currentTime.getMinutes();
                let currentSeconds = currentTime.getSeconds();
                {if $display_clock == 24}
                    currentHours = (currentHours < 10 ? "0" : "") + currentHours;
                {/if}
                currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
                currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;

                // Convert to 12 hour clock
                {if $display_clock == 12}
                    let meridian = (currentHours < 12) ? "AM" : "PM";
                    currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;
                    currentHours = (currentHours == 0) ? 12 : currentHours;
                {/if}

                // Build Time string
                {if $display_clock == 12}
                    let currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + meridian;
                {else}
                    let currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds;
                {/if}

                // Update Clock element
                jQuery('#' + elementId).html(currentTimeString);

            }

            // Run the clock
            jQuery(document).ready(function() {

                // Run clock immediately
                updateClientClock("client-clock");

                // Register the clock to update every second
                setInterval(function() {
                    updateClientClock("client-clock")
                }, 1000);
            } );

        </script>
    {/if}
</head>

<body>
    <div id="dhtmltooltip"></div>
    <script src="{$theme_js_dir}dhtmltooltip.js"></script>
    <div class="text4">
        <table width="900px" border="0" cellspacing="0" cellpadding="0">
            <tr class="text4">
                <td width="450" class="text4" align="left">{$greeting_msg}</td>
                <td class="text4" align="right">{if $display_clock}<span id="client-clock"></span> - {/if}{$todays_display_date}</td>
        </table>
    </div>

    <!-- System Messages -->
    <div id="system_messages" style="display: none;"></div>

