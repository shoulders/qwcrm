<!-- overview_workorders_stats_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{t}Overall Work Order Stats{/t}</b>
<br>
<table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
    <tr class="olotd4">
        <td class="row2"><b>{t}Open{/t}</b></td>
        <td class="row2"><b>{t}Assigned{/t}</b></td>
        <td class="row2"><b>{t}Waiting For Parts{/t}</b></td>
        <td class="row2"><b>{t}Scheduled{/t}</b></td>
        <td class="row2"><b>{t}On Hold{/t}</b></td>
        <td class="row2"><b>{t}Management{/t}</b></td>
        <td class="row2"><b>{t}Closed{/t}</b></td>
    </tr>
    <tr class="olotd4">
        <td>{$overall_workorders_open_count}</td>
        <td>{$overall_workorders_assigned_count}</td>
        <td>{$overall_workorders_waiting_for_parts_count}</td>
        <td>{$overall_workorders_scheduled_count}</td>
        <td>{$overall_workorders_on_hold_count}</td>
        <td>{$overall_workorders_management_count}</td> 
        <td>{$overall_workorders_total_closed_count}</td>                                            
    </tr>
</table>