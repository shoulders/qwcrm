<!-- resolution.tpl - Display Work Order Resolution With Edit Button If Open (Work Orders - Details Page) -->
<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">{$translate_workorder_resolution}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    <a href="?page=workorder:close&wo_id={$single_workorder_array[i].WORK_ORDER_ID}&page_title={$translate_workorder_edit_resolution}">
                                        <img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0"
                                            onMouseOver="ddrivetip('{$translate_workorder_edit_resolution}');"
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
                            <p><b>{$translate_workorder_print_closed_by}: </b>{$resolution[v].EMPLOYEE_DISPLAY_NAME}  <b>{$translate_workorder_date}: </b>
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