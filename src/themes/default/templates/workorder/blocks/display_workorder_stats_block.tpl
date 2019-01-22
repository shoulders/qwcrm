<!-- display_workorder_stats_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<br>
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="row2"><b>{t}Open{/t}</b></td>
        <td class="row2"><b>{t}Assigned{/t}</b></td>
        <td class="row2"><b>{t}Waiting for Parts{/t}</b></td>
        <td class="row2"><b>{t}Scheduled{/t}</b></td>
        <td class="row2"><b>{t}With Client{/t}</b></td>
        <td class="row2"><b>{t}On Hold{/t}</b></td>
        <td class="row2"><b>{t}Management{/t}</b></td>        
    </tr>
    <tr class="olotd4">
        <td>{$workorder_stats.count_open}</td>
        <td>{$workorder_stats.count_assigned}</td>
        <td>{$workorder_stats.count_waiting_for_parts}</td>
        <td>{$workorder_stats.count_scheduled}</td>
        <td>{$workorder_stats.count_with_client}</td>
        <td>{$workorder_stats.count_on_hold}</td>
        <td>{$workorder_stats.count_management}</td>                                                  
    </tr>
</table>