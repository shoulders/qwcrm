<!-- view.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Scheduled ID{/t} {$schedule_details.SCHEDULE_ID} {t}on{/t} {$schedule_details.SCHEDULE_START|date_format:$date_format}</td>
                </tr>
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Schedule Details{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">                        
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}SCHEDULE_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}SCHEDULE_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>                
                <tr>
                    <td class="menutd2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table width="100%" cellpadding="5" cellspacing="5">
                                        <tr>
                                            <td>
                                                <p><b>{t}Date{/t}: </b>{$schedule_details.SCHEDULE_START|date_format:$date_format}</p>
                                                <p>
                                                    <b>{t}Start Time{/t}: </b>{$schedule_details.SCHEDULE_START|date_format:"%H:%M"}<br>
                                                    <b>{t}End Time{/t}: </b>{$schedule_details.SCHEDULE_END|date_format:"%H:%M"}
                                                </p>
                                                <p><b>{t}Employee{/t}: </b>{$schedule_details.EMPLOYEE_DISPLAY_NAME}</p>
                                                <b>{t}Notes{/t}:</b><br />
                                                <div>{$schedule_details.SCHEDULE_NOTES}</div><br>
                                                <button type="button" onClick="window.location='index.php?page=schedule:edit&schedule_id={$schedule_details.SCHEDULE_ID}';">{t}Edit{/t}</button>
                                                <a href="index.php?page=schedule:delete&schedule_id={$workorder_schedule[a].SCHEDULE_ID}" onclick="return confirmDelete('are you sure you want to delete the schedule item');"><button type="button">{t}Delete{/t}</button></a>                                                    
                                                <button type="button" onClick="window.location='index.php?page=schedule:icalendar&schedule_id={$schedule_details.SCHEDULE_ID}&theme=print';">{t}Export{/t}</button>                                         
                                                <button type="button" onClick="window.location='index.php?page=workorder:details&workorder_id={$schedule_details.WORKORDER_ID}';">{t}Work Order Details{/t}</button>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>            
        </td>
    </tr>
</table>