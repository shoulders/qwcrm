

<!-- theme_debug_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<div style="width: 900px; margin-bottom: 20px;">
    <table width="400" border="0" cellspacing="0" cellpadding="0" style="margin: auto auto;">
        <tr>
            <td align="center" colspan="2"><h2><strong>{t}QWcrm Debug Section{/t}</span></h2></td>
        </tr>

        <!-- Visitor Details -->
        <tr>
            <td width="50%"><b><span class="text3">{t}IP Address{/t}:</span></b></td>
            <td>{$IPaddress}</td>
        </tr>

        <!-- Page -->
        <tr>
            <td><b><span class="text3">{t}Page Load Time{/t}:</span></b></td>
            <td>{$pageLoadTime|string_format:"%.4f"} {t}Seconds{/t}</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Page Controller{/t}:</span></b></td>
            <td>{$pageDisplayController}</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Component{/t}:</span></b></td>
            <td>{$loadedComponent}</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Page{/t}:</span></b></td>
            <td>{$loadedPageTpl}</td>
        </tr>

        <!-- Memory Usage -->
        <tr>
            <td><b><span class="text3">{t}Start PHP Memory{/t}:</span></b></td>
            <td>{$startMem|string_format:"%.2f"} MB</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Current PHP Memory{/t}:</span></b></td>
            <td>{$currentMem|string_format:"%.2f"} MB</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Peak PHP Memory{/t}:</span></b></td>
            <td>{$peakMem|string_format:"%.2f"} MB</td>
        </tr>

    </table>
</div>

{if $qwcrmAdvancedDebug}
    <div>
        <!-- Qwcrm Advanced Debug -->
        <h2><strong>{t}QWcrm Advanced Debug Section{/t}</strong></h2>
        <p>{t}Use Ctrl+F to search for the following sections or click on the links below.{/t}</p>
        <ul>
            <li><a href="#last-php-error">{t}Last PHP Error{/t}</a></il>
            <li><a href="#php-debug-backtrace">{t}PHP Debug Backtrace{/t}</a></il>
            <li><a href="#defined-php-variables">{t}Defined PHP Variables{/t}</a></il>
            <li><a href="#defined-php-constants">{t}Defined PHP Constants{/t}</a></il>
            <li><a href="#defined-php-functions">{t}Defined PHP Functions{/t}</a></il>
            <li><a href="#declared-php-classes">{t}Declared PHP Classes{/t}</a></il>
            <li><a href="#server-environmental-variables">{t}Server Environmental Variables{/t}.</a></il>
        </ul>

        <!-- PHP Information -->
        <h3 id="last-php-error"><strong>{t}Last PHP Error{/t}:</strong></h3>
        <pre>{$phpErrorGetLast}</pre>
        <h3 id="php-debug-backtrace"><strong>{t}PHP Debug Backtrace{/t}:</strong></h3>
        <pre>{$phpDebugBacktrace}</pre>
        <h3 id="defined-php-variables"><strong>{t}Defined PHP Variables{/t}:</strong></h3>
        <pre>{$definedPhpVariables}</pre>;
        <h3 id="defined-php-constants"><strong>{t}Defined PHP Constants{/t}:</strong></h3>
        <pre>{$definedPhpConstants}</pre>;
        <h3 id="defined-php-functions"><strong>{t}Defined PHP Functions{/t}:</strong></h3>
        <pre>{$definedPhpFunctions}</pre>
        <h3 id="declared-php-classes"><strong>{t}Declared PHP Classes{/t}:</strong></h3>
        <pre>{$declaredPhpClasses}</pre>

        <!-- Server Information -->
        <h3 id="server-environmental-variables"><strong>{t}Server Environmental Variables{/t}:</strong></h3>
        <pre>{$serverEnvironmentalVariables}</pre>
    </div>
{/if}
