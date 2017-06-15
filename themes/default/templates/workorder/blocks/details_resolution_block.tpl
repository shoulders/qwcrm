<!-- details_resolution_block.tpl -->
<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Resolution{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    {if $single_workorder.WORK_ORDER_STATUS != 6}
                                        <a href="index.php?page=workorder:details_edit_resolution&workorder_id={$single_workorder.WORK_ORDER_ID}">
                                            <img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('{t}Click to edit resolution{/t}');" onMouseOut="hideddrivetip();">
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        </table>
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
                        {if $single_workorder.WORK_ORDER_CLOSE_BY != "" }
                            <p>
                                <b>{t}Closed By{/t}: </b>{$single_workorder.EMPLOYEE_DISPLAY_NAME}<br>
                                <b>{t}Date{/t}: </b>{$single_workorder.WORK_ORDER_CLOSE_DATE|date_format:$date_format}<br>
                            </p>
                        {/if}
                        <div>{$single_workorder.WORK_ORDER_RESOLUTION}</div>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>