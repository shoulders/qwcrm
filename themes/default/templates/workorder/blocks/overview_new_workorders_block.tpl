<!-- overview_new_workorders_block.tpl -->
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
    {section name=n loop=$new_workorders}      
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='?page=workorder:details&workorder_id={$new_workorders[n].WORK_ORDER_ID}&customer_id={$new_workorders[n].CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$new_workorders[n].WORK_ORDER_ID}';" class="row1">

            <!-- ID -->
            <td class="olotd4"><a href="?page=workorder:details&workorder_id={$new_workorders[n].WORK_ORDER_ID}&customer_id={$new_workorders[n].CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$new_workorders[n].WORK_ORDER_ID}">{$new_workorders[n].WORK_ORDER_ID}</a></td>

            <!-- Opened -->
            <td class="olotd4"> {$new_workorders[n].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{$translate_workorder_contact_info_tooltip_title}</b></center><hr><b>{$translate_workorder_phone}: </b>{$new_workorders[n].CUSTOMER_PHONE}<br> <b>{$translate_workorder_fax}: </b>{$new_workorders[n].CUSTOMER_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$new_workorders[n].CUSTOMER_MOBILE_PHONE}<br><b>{$translate_workorder_address}:</b><br>{$new_workorders[n].CUSTOMER_ADDRESS}<br>{$new_workorders[n].CUSTOMER_CITY}, {$new_workorders[n].CUSTOMER_STATE}<br>{$new_workorders[n].CUSTOMER_ZIP}');" onMouseOut="hideddrivetip();">                         
                <a class="link1" href="?page=customer:details&customer_id={$new_workorders[n].CUSTOMER_ID}&page_title={$new_workorders[n].CUSTOMER_DISPLAY_NAME}">{$new_workorders[n].CUSTOMER_DISPLAY_NAME}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$new_workorders[n].WORK_ORDER_SCOPE}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {if $new_workorders[n].WORK_ORDER_STATUS == '1'}{$translate_workorder_created}{/if}
                {if $new_workorders[n].WORK_ORDER_STATUS == '2'}{$translate_workorder_assigned}{/if}
                {if $new_workorders[n].WORK_ORDER_STATUS == '3'}{$translate_workorder_waiting_for_parts}{/if}
                {if $new_workorders[n].WORK_ORDER_STATUS == '6'}{$translate_workorder_closed}{/if}
                {if $new_workorders[n].WORK_ORDER_STATUS == '7'}{$translate_workorder_waiting_for_payment}{/if}
                {if $new_workorders[n].WORK_ORDER_STATUS == '8'}{$translate_workorder_payment_made}{/if}
                {if $new_workorders[n].WORK_ORDER_STATUS == '9'}{$translate_workorder_pending}{/if}
                {if $new_workorders[n].WORK_ORDER_STATUS == '10'}{$translate_workorder_open}{/if}
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                {if $new_workorders[n].EMPLOYEE_DISPLAY_NAME == ""}
                    {$translate_workorder_not_assigned}
                {else}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact_info_tooltip_title}</b></center><hr><b>{$translate_workorder_fax}: </b>{$new_workorders[n].EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$new_workorders[n].EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$new_workorders[n].EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();">                              
                    <a class="link1" href="?page=employee:details&employee_id={$new_workorders[n].EMPLOYEE_ID}&page_title={$new_workorders[n].EMPLOYEE_DISPLAY_NAME}">{$new_workorders[n].EMPLOYEE_DISPLAY_NAME}</a>
                {/if}
            </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="?page=workorder:print&workorder_id={$new_workorders[n].WORK_ORDER_ID}&customer_id={$new_workorders[n].CUSTOMER_ID}&page_title={$translate_workorder_print_work_order_id} {$new_workorders[n].WORK_ORDER_ID}&theme=off">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('{$translate_workorder_print_the_work_order_button_tooltip}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="?page=workorder:details&workorder_id={$new_workorders[n].WORK_ORDER_ID}&customer_id={$new_workorders[n].CUSTOMER_ID}&page_title={$translate_workorder_work_order_id} {$new_workorders[n].WORK_ORDER_ID}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('{$translate_workorder_view_the_work_order_button_tooltip}');" onMouseOut="hideddrivetip();">
                </a>                                            
            </td>
        </tr>
    {sectionelse}
        <tr>
            <td colspan="7" class="error">{$translate_workorder_msg_there_are_no_new_work_orders}</td>
        </tr>        
    {/section}
</table>