<!-- overview_workorders_stats_block.tpl -->
<b>{t}Work Order Stats{/t}</b>
<table class="olotable" width="700" border="0" cellpadding="5" cellspacing="0">
    <!-- Currently Logged In Employee Workorder Stats -->
    <tr>
        <td>                
            <b>{t}Overall Work Order Stats{/t}</b>
            <br>
            <table width="100%" cellpadding="4" cellspacing="0" border="0" class="olotable">
                <tr class="olotd4">
                    <td class="row2"><b>{t}Open{/t}</b></td>
                    <td class="row2"><b>{t}WORKORDER_STATUS_2{/t}</b></td>
                    <td class="row2"><b>{t}WORKORDER_STATUS_3{/t}</b></td>
                    <td class="row2"><b>{t}WORKORDER_STATUS_4{/t}</b></td>
                     <td class="row2"><b>{t}WORKORDER_STATUS_5{/t}</b></td>
                    <td class="row2"><b>{t}Closed{/t}</b></td>
                </tr>
                <tr class="olotd4">
                    <td>{$overall_workorders_open_count}</td>
                    <td>{$overall_workorders_assigned_count}</td>
                    <td>{$overall_workorders_waiting_for_parts_count}</td>
                    <td>{$overall_workorders_on_hold_count}</td>
                    <td>{$overall_workorders_management_count}</td> 
                    <td>{$overall_workorders_total_closed_count}</td>                                            
                </tr>
            </table>
            <br>                                    
        </td>
    </tr>
</table>