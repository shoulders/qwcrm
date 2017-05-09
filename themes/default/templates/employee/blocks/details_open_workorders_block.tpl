<!-- details_open_workorders_block.tpl -->
<table class="olotable" width="100%" border="0" cellpadding="5">
    <tr>
        <td class="olohead">{$translate_employee_workorder_id}</td>
        <td class="olohead">{$translate_employee_date_open}</td>
        <td class="olohead">{$translate_employee_customer}</td>
        <td class="olohead">{$translate_employee_scope}</td>
        <td class="olohead">{$translate_employee_status}</td>
        <td class="olohead">Action</td>
    </tr>
    {section name=a loop=$open_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='?page=workorder:details&workorder_id={$open_workorders[a].WORK_ORDER_ID}';" class="row1">
            <td class="olotd4"><a href="?page=workorder:details&workorder_id={$open_workorders[a].WORK_ORDER_ID}">{$open_workorders[a].WORK_ORDER_ID}</a></td>
            <td class="olotd4">{$open_workorders[a].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>
            <td class="olotd4"><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>Customer Contact</b><hr></center><b>Home: </b>{$open_workorders[a].CUSTOMER_PHONE}<br><b>Work: </b>{$open_workorders[a].CUSTOMER_WORK_PHONE}<br><b>Mobile: </b>{$open_workorders[a].CUSTOMER_MOBILE_PHONE}');"onMouseOut="hideddrivetip();">{$open_workorders[a].CUSTOMER_DISPLAY_NAME}</td>                                                        
            <td class="olotd4"><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b> Description</b><hr></center>{$open_workorders[a].WORK_ORDER_DESCRIPTION}');" onMouseOut="hideddrivetip();">{$open_workorders[a].WORK_ORDER_SCOPE}</td>
            <td class="olotd4">
                {if $open_workorders[a].WORK_ORDER_STATUS == '1'}{$translate_workorder_created}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '2'}{$translate_workorder_assigned}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '3'}{$translate_workorder_waiting_for_parts}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '6'}{$translate_workorder_closed}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '7'}{$translate_workorder_waiting_for_payment}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '8'}{$translate_workorder_payment_made}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '9'}{$translate_workorder_pending}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '10'}{$translate_workorder_open}{/if}           
            </td>            
            <td class="olotd4" align="center">
                <a href="?page=workorder:print&workorder_id={$open_workorders[a].WORK_ORDER_ID}&customer_id={$open_workorders[a].CUSTOMER_ID}&theme=off" target="new"><img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('Print The Work Order');" onMouseOut="hideddrivetip();"></a>
                <a href="?page=workorder:details&workorder_id={$open_workorders[a].WORK_ORDER_ID}&customer_id={$open_workorders[a].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('View The Work Order');" onMouseOut="hideddrivetip();"></a>
            </td>
        </tr>
    {/section}
</table>