<!-- open_awaiting_parts_workorders_block.tpl -->
<b>{$translate_workorder_awaiting_parts}</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olohead" width="6"><b>{$translate_workorder_id}</b></td>
        <td class="olohead"><b>{$translate_workorder_opened}</b></td>
        <td class="olohead"><b>{$translate_workorder_customer}</b></td>
        <td class="olohead"><b>{$translate_workorder_scope}</b></td>
        <td class="olohead"><b>{$translate_workorder_status}</b></td>
        <td class="olohead"><b>{$translate_workorder_tech}</b></td>
        <td class="olohead"><b>{$translate_workorder_action}</b></td>
    </tr>
    {foreach from=$awaiting item=awaiting}
        {if $awaiting.WORK_ORDER_ID > 0 }
            <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='?page=workorder:details&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$awaiting.WORK_ORDER_ID}';" class="row1">
                <td class="olotd4"><a href="?page=workorder:details&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$awaiting.WORK_ORDER_ID}">{$awaiting.WORK_ORDER_ID}</a></td>
                <td class="olotd4"> {$awaiting.WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
                <td class="olotd4" nowrap>
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0"
                         onMouseOver="ddrivetip('<b><center>{$translate_workorder_contact_info_tooltip_title}</b></center><hr><b>{$translate_workorder_phone}: </b>{$awaiting.CUSTOMER_PHONE}<br> <b>{$translate_workorder_fax}: </b>{$awaiting.CUSTOMER_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$awaiting.CUSTOMER_MOBILE_PHONE}<br><b>{$translate_workorder_address}:</b><br>{$awaiting.CUSTOMER_ADDRESS}<br>{$awaiting.CUSTOMER_CITY}, {$awaiting.CUSTOMER_STATE}<br>{$awaiting.CUSTOMER_ZIP}');"
                         onMouseOut="hideddrivetip();">
                    <a class="link1" href="?page=customer:customer_details&customer_id={$awaiting.CUSTOMER_ID}&page_title={$awaiting.CUSTOMER_DISPLAY_NAME}">{$awaiting.CUSTOMER_DISPLAY_NAME}</a>
                </td>
                <td class="olotd4" nowrap>
                {$awaiting.WORK_ORDER_SCOPE}</td>
                <td class="olotd4">{$awaiting.CONFIG_WORK_ORDER_STATUS}</td>
                <td class="olotd4" nowrap>
                {if $awaiting.EMPLOYEE_DISPLAY_NAME == ""}
                    {$translate_workorder_not_assigned}
                {else}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0"
                         onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact_info_tooltip_title}</b></center><hr><b>{$translate_workorder_fax}: </b>{$awaiting.EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$awaiting.EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$awaiting.EMPLOYEE_HOME_PHONE}');"
                         onMouseOut="hideddrivetip();"> 
                    <a class="link1" href="?page=employee:employee_details&employee_id={$awaiting.EMPLOYEE_ID}&page_title={$awaiting.EMPLOYEE_DISPLAY_NAME}">{$awaiting.EMPLOYEE_DISPLAY_NAME}</a>
                {/if}
                </td>
                <td class="olotd4" align="center" nowrap>
                    <a href="?page=workorder:print&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title=$translate_workorder_print_work_order_id} {$awaiting.WORK_ORDER_ID}&theme=off">
                        <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0"
                        onMouseOver="ddrivetip('{$translate_workorder_print_the_work_order_button_tooltip}');"
                        onMouseOut="hideddrivetip();">
                    </a>
                    <a href="?page=workorder:details&wo_id={$awaiting.WORK_ORDER_ID}&customer_id={$awaiting.CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$awaiting.WORK_ORDER_ID}">
                        <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0"
                            onMouseOver="ddrivetip('{$translate_workorder_view_the_work_order_button_tooltip}');"
                            onMouseOut="hideddrivetip();">
                    </a>                                        
                </td>
            </tr>
        {else}
            <tr>
                <td colspan="7" class="error">{$translate_workorder_msg_there_are_no_work_orders_waiting_for_parts}</td>
            </tr>
        {/if}
    {/foreach}
</table>