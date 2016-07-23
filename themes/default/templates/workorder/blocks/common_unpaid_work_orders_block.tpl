<!-- common_unpaid_work_orders.tpl - Display Unpaid Word Orders (Work Orders - Open Page) -->
<b>{$translate_workorder_unpaid_title}</b>
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
    {foreach from=$payment item=payment}
    {if $payment.WORK_ORDER_ID > 0 }
    <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='?page=workorder:details&wo_id={$payment.WORK_ORDER_ID}&customer_id={$payment.CUSTOMER_ID}&page_title={$translate_workorder_page_title}  {$payment.WORK_ORDER_ID}';" class="row1">
        <td class="olotd4"><a href="?page=workorder:details&wo_id={$payment.WORK_ORDER_ID}&customer_id={$payment.CUSTOMER_ID}&page_title={$translate_workorder_page_title}  {$payment.WORK_ORDER_ID}">{$payment.WORK_ORDER_ID}<a/></td>
        <td class="olotd4"> {$payment.WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
        <td class="olotd4" nowrap>
            <img src="{$theme_images_dir}icons/16x16/view.gif" border="0"
                 onMouseOver="ddrivetip('<b><center>{$translate_workorder_contact_info}</b></center><hr><b>{$translate_workorder_phone}: </b>{$payment.CUSTOMER_PHONE}<br> <b>{$translate_workorder_work}: </b>{$payment.CUSTOMER_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$payment.CUSTOMER_MOBILE_PHONE}<br><b>{$translate_workorder_address}</b><br>{$payment.CUSTOMER_ADDRESS}<br>{$payment.CUSTOMER_CITY}, {$payment.CUSTOMER_STATE}<br>{$payment.CUSTOMER_ZIP}');"
                 onMouseOut="hideddrivetip();">
            <a class="link1" href="?page=customer:customer_details&customer_id={$payment.CUSTOMER_ID}&page_title={$payment.CUSTOMER_DISPLAY_NAME}">{$payment.CUSTOMER_DISPLAY_NAME}</a>
        </td>
        <td class="olotd4" nowrap>{$payment.WORK_ORDER_SCOPE}</td>
        <td class="olotd4">{$payment.CONFIG_WORK_ORDER_STATUS}</td>
        <td class="olotd4" nowrap>
        {if $payment.EMPLOYEE_DISPLAY_NAME == ""}
        {$translate_workorder_not_assigned}
        {else}
        <img src="{$theme_images_dir}icons/16x16/view.gif" border="0"
             onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact_info}</b></center><hr><b>{$translate_workorder_work}: </b>{$payment.EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$payment.EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$payment.EMPLOYEE_HOME_PHONE}');"
             onMouseOut="hideddrivetip();"> 
        <a class="link1" href="?page=employees:employee_details&employee_id={$payment.EMPLOYEE_ID}&page_title={$payment.EMPLOYEE_DISPLAY_NAME}">{$payment.EMPLOYEE_DISPLAY_NAME}</a>
        {/if}
        </td>
        <td class="olotd4" align="center" nowrap>
            <a href="?page=workorder:print&wo_id={$payment.WORK_ORDER_ID}&customer_id={$payment.CUSTOMER_ID}&page_title={$translate_workorder_print_title} {$payment.WORK_ORDER_ID}&escape=1">
                <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0"
                     onMouseOver="ddrivetip('{$translate_workorder_print_the_work_order}');"
                     onMouseOut="hideddrivetip();">
            </a>
            <a href="?page=workorder:details&wo_id={$payment.WORK_ORDER_ID}&customer_id={$payment.CUSTOMER_ID}&page_title={$translate_workorder_page_title} {$payment.WORK_ORDER_ID}">
                <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0"
                     onMouseOver="ddrivetip('{$translate_workorder_view_the_work_order}');"
                     onMouseOut="hideddrivetip();">
            </a>                                        
        </td>
    </tr>
    {else}
    <tr>
        <td colspan="7" class="error">{$translate_workorder_msg_3}</td>
    </tr>
    {/if}
    {/foreach}
</table>
