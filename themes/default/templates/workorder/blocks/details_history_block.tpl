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
    {section name=c loop=$workorder_history}
        <tr>
            <td class="menutd">
                <table width="100%" cellpadding="4" cellspacing="0" border="0">
                    <tr>
                        <td>
                            <b>{$translate_workorder_entered_by} </b>
                            <a href="?page=employee:employee_details&employee_id={$workorder_history[c].WORK_ORDER_HISTORY_ENTERED_BY}&page_title={$translate_workorder_employee} {$workorder_history[c].EMPLOYEE_DISPLAY_NAME}">{$workorder_history[c].EMPLOYEE_DISPLAY_NAME}</a> 
                            <b>{$translate_workorder_date} </b>{$workorder_history[c].WORK_ORDER_HISTORY_DATE|date_format:"$date_format %r"}<br>
                            {$workorder_history[c].WORK_ORDER_HISTORY_NOTES}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    {/section}
</table>