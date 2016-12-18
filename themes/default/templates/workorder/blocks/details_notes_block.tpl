<!-- details_notes_block.tpl -->
<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_details_notes_title}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr> 
                                <td width="33%" align="right" >
                                    <a href="?page=workorder:details_new_note&workorder_id={$single_workorder[i].WORK_ORDER_ID}&page_title={$translate_workorder_details_new_note_title}">
                                        <img src="{$theme_images_dir}icons/16x16/small_new_work_order.gif" border="0"
                                             onMouseOver="ddrivetip('{$translate_workorder_details_new_note_button_tooltip}');"
                                             onMouseOut="hideddrivetip();">
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
                <tr>
                    <td>
                        {section name=b loop=$workorder_notes}
                            <b>{$translate_workorder_entered_by} </b>{$workorder_notes[b].EMPLOYEE_DISPLAY_NAME}  <b>{$translate_workorder_date} </b> {$workorder_notes[b].WORK_ORDER_NOTES_DATE|date_format:"$date_format"}<br>
                            {$workorder_notes[b].WORK_ORDER_NOTES_DESCRIPTION}                            
                        {/section}
                    </td>
                </tr>
            </table>
        </td>          
    </tr>
</table>