<!-- details_note_block.tpl -->
<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_details_notes_title}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr> 
                                <td width="33%" align="right">   
                                    <a href="?page=workorder:note_new&workorder_id={$workorder_notes[i].WORK_ORDER_ID}">
                                        <img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" border="0" onMouseOver="ddrivetip('{$translate_workorder_details_new_note_button_tooltip}');" onMouseOut="hideddrivetip();">
                                    </a>                    
                                </td>  
                            </tr>
                        </table>
                        </a>
                    </td>
                </tr>
            </table>       
        </td>
    </tr>   
    <tr>
        <td class="menutd">
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                {section name=n loop=$workorder_notes}
                    <tr>
                        <td class="menutd">
                            <table width="100%" cellpadding="4" cellspacing="0" style="border-collapse: collapse;">                
                                <tr style="border: 1px black solid; background-color: #ededed;">
                                    <td><b>Workorder Note ID: {$workorder_notes[n].WORK_ORDER_NOTES_ID}</b></td>
                                    <td width="33%" align="right">
                                        {if $login_account_type_id == 1}
                                            <a href="index.php?page=workorder:note_edit&workorder_note_id={$workorder_notes[n].WORK_ORDER_NOTES_ID}">
                                                <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('Edit the Note');" onMouseOut="hideddrivetip();">                                                 
                                            </a>
                                            <a href="index.php?page=workorder:note_delete&workorder_note_id={$workorder_notes[n].WORK_ORDER_NOTES_ID}">
                                                <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('Delete the Note');" onMouseOut="hideddrivetip();">                                                 
                                            </a>
                                        {/if}
                                    </td>
                                </tr>                                
                            </table>    
                        </td>    
                    </tr> 
                    <tr>                    
                        <td>
                            <b>{$translate_workorder_employee}: </b>{$workorder_notes[n].EMPLOYEE_DISPLAY_NAME}<br>                           
                            <b>{$translate_workorder_date}: </b>{$workorder_notes[n].WORK_ORDER_NOTES_DATE|date_format:$date_format}<br>
                            <b>{$translate_workorder_time}: </b>{$workorder_notes[n].WORK_ORDER_NOTES_DATE|date_format:"%H:%M"}<br>
                            <b>{$translate_workorder_details_history_notes}:</b>
                            <div>{$workorder_notes[n].WORK_ORDER_NOTES_DESCRIPTION}<br></div>
                        </td>
                    </tr>
                {/section}
            </table>                            
        </td>          
    </tr>
</table>
