<!-- details_resolution_block.tpl - Display Work Order Resolution With Edit Button If Open (Work Orders - Details Page) -->
<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{$translate_workorder_details_resolution_title}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    <a href="?page=workorder:resolution&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_workorder_details_edit_resolution_title}">
                                        <img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0"
                                            onMouseOver="ddrivetip('{$translate_workorder_details_edit_resolution_button_tooltip}');"
                                            onMouseOut="hideddrivetip();">
                                    </a>
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
                        {section name=v loop=$resolution}
                        {if $single_workorder_array[i].WORK_ORDER_CLOSE_BY != "" }
                            <p><b>{$translate_workorder_closed_by}: </b>{$resolution[v].EMPLOYEE_DISPLAY_NAME}  <b>{$translate_workorder_date}: </b>
                            {$resolution[v].WORK_ORDER_CLOSE_DATE|date_format:"$date_format"} <br>
                            {/if}
                            {$resolution[v].WORK_ORDER_RESOLUTION}
                            </p>
                        {/section}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>