<!-- open_new_work_orders_block.tpl - Display New Work Orders (Work Orders - Open Page) -->
<b>{$translate_workorder_new}</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olohead"><b>{$translate_workorder_id}</b></td>
        <td class="olohead"><b>{$translate_workorder_opened}</b></td>
        <td class="olohead"><b>{$translate_workorder_customer}</b></td>
        <td class="olohead"><b>{$translate_workorder_scope}</b></td>
        <td class="olohead"><b>{$translate_workorder_status}</b></td>
        <td class="olohead"><b>{$translate_workorder_tech}</b></td>
        <td class="olohead"><b>{$translate_workorder_action}</b></td>
    </tr>                
    {foreach from=$new item=new}
    {if $new.WORK_ORDER_ID > 0}
    <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='?page=workorder:details&wo_id={$new.WORK_ORDER_ID}&customer_id={$new.CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$new.WORK_ORDER_ID}';" class="row1">
        <td class="olotd4"><a href="?page=workorder:details&wo_id={$new.WORK_ORDER_ID}&customer_id={$new.CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$new.WORK_ORDER_ID}">{$new.WORK_ORDER_ID}</a>
        </td>
        <td class="olotd4"> {$new.WORK_ORDER_OPEN_DATE|date_format:"$date_format"}
        </td>
        <td class="olotd4" nowrap>
            <img src="{$theme_images_dir}icons/16x16/view.gif" border="0"
                 onMouseOver="ddrivetip('<b><center>{$translate_workorder_contact_info_tooltip_title}</b></center><hr><b>{$translate_workorder_phone}: </b>{$new.CUSTOMER_PHONE}<br> <b>{$translate_workorder_fax}: </b>{$new.CUSTOMER_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$new.CUSTOMER_MOBILE_PHONE}<br><b>{$translate_workorder_address}:</b><br>{$new.CUSTOMER_ADDRESS}<br>{$new.CUSTOMER_CITY}, {$new.CUSTOMER_STATE}<br>{$new.CUSTOMER_ZIP}');"
                 onMouseOut="hideddrivetip();">
            <a class="link1" href="?page=customer:customer_details&customer_id={$new.CUSTOMER_ID}&page_title={$new.CUSTOMER_DISPLAY_NAME}">{$new.CUSTOMER_DISPLAY_NAME}</a>
        </td>
        <td class="olotd4" nowrap>
        {$new.WORK_ORDER_SCOPE}
        </td>
        <td class="olotd4">{$new.CONFIG_WORK_ORDER_STATUS}</td>
        <td class="olotd4" nowrap>
        {if $new.EMPLOYEE_DISPLAY_NAME == ""}
            {$translate_workorder_not_assigned}
        {else}
            <img src="{$theme_images_dir}icons/16x16/view.gif" border="0"
                 onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact_info_tooltip_title}</b></center><hr><b>{$translate_workorder_fax}: </b>{$new.EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$new.EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$new.EMPLOYEE_HOME_PHONE}');"
                 onMouseOut="hideddrivetip();"> 
            <a class="link1" href="?page=employees:employee_details&employee_id={$new.EMPLOYEE_ID}&page_title={$new.EMPLOYEE_DISPLAY_NAME}">{$new.EMPLOYEE_DISPLAY_NAME}</a>
        {/if}
        </td>
        <td class="olotd4" align="center" nowrap>
            <a href="?page=workorder:print&wo_id={$new.WORK_ORDER_ID}&customer_id={$new.CUSTOMER_ID}&page_title={$translate_workorder_print_work_order_id} {$new.WORK_ORDER_ID}&theme=off">
                <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0"
                     onMouseOver="ddrivetip('{$translate_workorder_print_the_work_order_button_tooltip}');"
                     onMouseOut="hideddrivetip();">
            </a>
            <a href="?page=workorder:details&wo_id={$new.WORK_ORDER_ID}&customer_id={$new.CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$new.WORK_ORDER_ID}">
                <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0"
                     onMouseOver="ddrivetip('{$translate_workorder_view_the_work_order_button_tooltip}');"
                     onMouseOut="hideddrivetip();">
            </a>                                            
        </td>
    </tr>
    {else}
    <tr>
        <td colspan="7" class="error">{$translate_workorder_msg_there_are_no_new_work_orders}</td>
    </tr>
    {/if}
    {/foreach}
</table>
    
