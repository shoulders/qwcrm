

<!-- theme_debug_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<div style="width: 900px; margin-bottom: 20px;">
    <table width="300" border="0" cellspacing="0" cellpadding="0" style="margin: auto auto;">
        <tr>
            <td align="center" colspan="2"><p><strong>{t}QWcrm Debug Section{/t}</span></p></td>        
        </tr>

        <!-- Visitor Details -->
        <tr>
            <td width="50%"><b><span class="text3">{t}IP Address{/t}:</span></b></td>
            <td>{$IPaddress}</td>
        </tr>

        <!-- Page -->
        <tr>
            <td><b><span class="text3">{t}Page Load Time{/t}:</span></b></td>
            <td>{$pageLoadTime} s</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Page Controller{/t}:</span></b></td>
            <td>{$pageDisplayController}</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Module{/t}:</span></b></td>
            <td>{$loadedComponent}</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Page{/t}:</span></b></td>
            <td>{$loadedPageTpl}</td>
        </tr>    

        <!-- Memory Usage -->
        <tr>
            <td><b><span class="text3">{t}Start PHP Memory{/t}:</span></b></td>
            <td>{$startMem} MB</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Current PHP Memory{/t}:</span></b></td>
            <td>{$currentMem} MB</td>
        </tr>
        <tr>
            <td><b><span class="text3">{t}Peak PHP Memory{/t}:</span></b></td>
            <td>{$peakMem} MB</td>
        </tr>
        
    </table>
</div>