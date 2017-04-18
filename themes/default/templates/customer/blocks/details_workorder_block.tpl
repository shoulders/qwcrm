<!-- details_workorder_block.tpl -->
<b>{$translate_customer_open_work_orders}</b>
<table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td class="olohead">{$translate_customer_workorder_id}</td>
        <td class="olohead">{$translate_customer_date_open}</td>
        <td class="olohead">{$translate_customer}</td>
        <td class="olohead">{$translate_customer_scope}</td>
        <td class="olohead">{$translate_customer_status}</td>
        <td class="olohead">{$translate_customer_tech}</td>
        <td class="olohead">{$translate_customer_action}</td>
    </tr>
    {section name=a loop=$open_work_orders}
        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:details&workorder_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$open_work_orders[a].WORK_ORDER_ID},';" class="row1">
            <td class="olotd4"><a href="?page=workorder:details&workorder_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$open_work_orders[a].WORK_ORDER_ID}">{$open_work_orders[a].WORK_ORDER_ID}</a></td>
            <td class="olotd4">{$open_work_orders[a].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
            <td class="olotd4">{section name=i loop=$customer_details}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{/section}</td>
            <td class="olotd4">{$open_work_orders[a].WORK_ORDER_SCOPE}</td>
            <td class="olotd4">{$open_work_orders[a].WORK_ORDER_STATUS}</td>
            <td class="olotd4">
                {if $open_work_orders[a].EMPLOYEE_ID != ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" {literal}onMouseOver="ddrivetip('<center><b>{/literal}{$translate_contact}{literal}</b></center>
                    <hr>
                    <b>{/literal}{$translate_work}{literal} </b>{/literal}{$open_work_orders[a].EMPLOYEE_WORK_PHONE}{literal}<br>
                    <b>{/literal}{$translate_mobile} {literal}</b>{/literal}{$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}{literal}<br>
                    <b>{/literal}{$translate_home} {literal}</b>{/literal}{$open_work_orders[a].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()"><a class="link1" href="?page=employee:details&employee_id={$open_work_orders[a].EMPLOYEE_ID}&page_title={$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}">{$open_work_orders[a].EMPLOYEE_DISPLAY_NAME}</a>
                {else}
                    Not Assigned
                {/if}
            </td>
            <td class="olotd4" align="center">
                <a href="?page=workorder:print&workorder_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}&theme=off" target="new">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()">
                </a>
                <a href="?page=workorder:details&workorder_id={$open_work_orders[a].WORK_ORDER_ID}&customer_id={$open_work_orders[a].CUSTOMER_ID}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view_wo}')" onMouseOut="hideddrivetip()">
                </a>
            </td>
        </tr>
    {/section}
</table>
<br>
<b>{$translate_customer_closed_work_orders}</b>
<table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td class="olohead">{$translate_customer_workorder_id}</td>
        <td class="olohead">{$translate_customer_date_open}</td>
        <td class="olohead">{$translate_customer}</td>
        <td class="olohead">{$translate_customer_scope}</td>
        <td class="olohead">{$translate_customer_status}</td>
        <td class="olohead">{$translate_customer_tech}</td>
        <td class="olohead">{$translate_customer_action}</td>
    </tr>
    {section name=b loop=$closed_work_orders}
        <tr onmouseover="this.className='row2'" onmouseout="this.className='row1'" onDblClick="window.location='?page=workorder:details&workorder_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$closed_work_orders[b].WORK_ORDER_ID},';" class="row1">
            <td class="olotd4"><a href="?page=workorder:details&workorder_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&page_title={$translate_customer_work_order_id} {$closed_work_orders[b].WORK_ORDER_ID}">{$closed_work_orders[b].WORK_ORDER_ID}</a></td>
            <td class="olotd4">{$closed_work_orders[b].WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
            <td class="olotd4">{section name=i loop=$customer_details}{$customer_details[i].CUSTOMER_DISPLAY_NAME}{/section}</td>
            <td class="olotd4">{$closed_work_orders[b].WORK_ORDER_SCOPE}</td>
            <td class="olotd4">{$closed_work_orders[b].WORK_ORDER_STATUS}</td>
            <td class="olotd4">
                {if $closed_work_orders[b].EMPLOYEE_ID != ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" {literal}onMouseOver="ddrivetip('<center><b>{$translate_contact}</b></center>
                            <hr>
                            <b>{$translate_work} </b>{$open_work_orders[a].EMPLOYEE_WORK_PHONE}<br>
                            <b>{$translate_mobile} </b>{$open_work_orders[a].EMPLOYEE_MOBILE_PHONE}<br>
                            <b>{$translate_home} </b> {literal}{$closed_work_orders[a].EMPLOYEE_HOME_PHONE}')" onMouseOut="hideddrivetip()">{/literal}
                    <a class="link1" href="?page=employee:details&employee_id={$closed_work_orders[b].EMPLOYEE_ID}&page_title={$closed_work_orders[b].EMPLOYEE_DISPLAY_NAME}">{$closed_work_orders[b].EMPLOYEE_DISPLAY_NAME}</a>
                {else}
                    Not Assigned
                {/if}
            </td>
            <td class="olotd4" align="center">
                <a href="?page=workorder:print&workorder_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}&theme=off" target="new">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_print}')" onMouseOut="hideddrivetip()">
                </a>
                <a href="?page=workorder:details&workorder_id={$closed_work_orders[b].WORK_ORDER_ID}&customer_id={$closed_work_orders[b].CUSTOMER_ID}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{$translate_customer_view_wo}')" onMouseOut="hideddrivetip()">
                </a>
            </td>
        </tr>
    {/section}
</table>