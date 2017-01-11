<!-- view.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            {section name=a loop=$single_schedule}
                <table width="700" cellpadding="4" cellspacing="0" border="0" >
                    <tr>
                        <td class="menuhead2" width="80%">&nbsp;Scheduled ID {$single_schedule[a].SCHEDULE_ID} on {$single_schedule[a].SCHEDULE_START|date_format:"$date_format"}</td>
                    </tr>
                    <tr>
                        <td class="menutd2">
                            <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                                <tr>
                                    <td class="menutd">
                                        <table width="100%" cellpadding="5" cellspacing="5">
                                            <tr>
                                                <td>
                                                    <p><b>Date: </b>{$single_schedule[a].SCHEDULE_START|date_format:"$date_format"}</p>
                                                    <p>
                                                        <b>{$translate_schedule_start}: </b>{$single_schedule[a].SCHEDULE_START|date_format:"%H:%M"}<br>
                                                        <b>{$translate_schedule_end}: </b>{$single_schedule[a].SCHEDULE_END|date_format:"%H:%M"}
                                                    </p>
                                                    <p><b>{$translate_schedule_tech}: </b>{$single_schedule[a].EMPLOYEE_DISPLAY_NAME}</p>
                                                    <b>Notes:</b><br />
                                                    <div>{$single_schedule[a].SCHEDULE_NOTES}</div><br>
                                                    <button type="button" onClick="window.location='?page=schedule:edit&schedule_id={$single_schedule[a].SCHEDULE_ID}';">{$translate_schedule_edit}</button>
                                                    <a href="?page=schedule:delete&schedule_id={$workorder_schedule[a].SCHEDULE_ID}" onclick="return confirmDelete('are you sure you want to delete the schedule item');"><button type="button">{$translate_schedule_delete}</button></a>                                                    
                                                    <button type="button" onClick="window.location='?page=schedule:icalendar&schedule_id={$single_schedule[a].SCHEDULE_ID}&theme=print';">Export</button>                                         
                                                    <button type="button" onClick="window.location='?page=workorder:details&workorder_id={$single_schedule[a].WORKORDER_ID}';">View Work Order</button>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            {/section}
        </td>
    </tr>
</table>