<!-- details_history_block.tpl -->
<table class="olotable" width="100%" cellpadding="3" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">{$translate_workorder_details_history_title}</td>
                </tr>
            </table>
        </td>
    </tr>
    {section name=i loop=$workorder_history}
        <tr>
            <td class="menutd">
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <b>{$translate_workorder_employee}: </b><a href="?page=employee:details&employee_id={$workorder_history[i].ENTERED_BY}&page_title={$translate_workorder_employee} {$workorder_history[i].EMPLOYEE_DISPLAY_NAME}">{$workorder_history[i].EMPLOYEE_DISPLAY_NAME}</a><br>
                            <b>{$translate_workorder_date}: </b>{$workorder_history[i].DATE|date_format:"$date_format"}<br>
                            <b>{$translate_workorder_time}: </b>{$workorder_history[i].DATE|date_format:"%H:%M"}<br>
                            <b>{$translate_workorder_event}: </b>{$workorder_history[i].NOTE}                            
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    {/section}
</table>