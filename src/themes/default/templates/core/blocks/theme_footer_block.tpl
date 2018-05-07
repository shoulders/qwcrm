

<!-- theme_footer_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
    <div style="color: #FFFFFF; font-weight: bold; background-color: #6699FF;">
        <table width="900px" border="0" cellspacing="0" cellpadding="0">
            <tr class="text4">
                <td width="450px" class="text4" align="center">&nbsp;</td>
            </tr>
            <tr class="text4">
                <td width="450px" class="text4" align="center">QWcrm {$qwcrm_version}</td>
            </tr>                
        </table>
    </div>
    <div id="system_message_functions">
        <script>processSystemMessages('{$information_msg}', '{$warning_msg}');</script>
    </div>
