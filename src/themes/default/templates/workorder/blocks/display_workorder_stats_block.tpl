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
        <td>{$workorder_stats.open_count}</td>
        <td>{$workorder_stats.assigned_count}</td>
        <td>{$workorder_stats.waiting_for_parts_count}</td>
        <td>{$workorder_stats.scheduled_count}</td>
        <td>{$workorder_stats.with_client_count}</td>
        <td>{$workorder_stats.on_hold_count}</td>
        <td>{$workorder_stats.management_count}</td>                                                  
    </tr>
</table>