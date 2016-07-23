<!-- closed_work_orders_block.tpl -->
<table width="100%" cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td class="menuhead2" width="80%">{$translate_workorder_open_work_orders}</td>
        <td class="menuhead2" width="20%" align="right" valign="middle">
            <table cellpadding="2" cellspacing="2" border="0">
                <tr>
                    <td width="33%" align="center" class="button"><a href=""  class="button" onClick="document.cookie='hide_open_work_order=1; path=/';">-</a></td>
                    <td width="33%" align="center" class="button"><a href="" class="button" onClick="document.cookie='hide_open_work_order=0; path=/';">+</a></td>
                    <td width="33%" align="center" class="button">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" 
                            onMouseOver="ddrivetip('<b>{$translate_workorder_closed_work_orders_block_help_title}</b><hr><p>{$translate_workorder_closed_work_orders_block_help_content}</p>');" 
                            onMouseOut="hideddrivetip();"></td>
                </tr>
            </table>
        </td>
    </tr>
    {if $hide_open_work_order == 1}
    {else}    
    <tr>
        <td colspan="2">
            <table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td class="row3"><b>{$translate_workorder_id}</b></td>
                    <td class="row3"><b>{$translate_workorder_opened}</b></td>
                    <td class="row3"><b>{$translate_workorder_customer}</b></td>
                    <td class="row3"><b>{$translate_workorder_scope}</b></td>
                    <td class="row3"><b>{$translate_workorder_status}</b></td>
                    <td class="row3"><b>{$translate_workorder_tech}</b></td>
                    <td class="row3"><b>translate_workorder_action}</b></td>
                </tr>
                {foreach from=$open_workorders item=open_workorders}
                <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='?page=workorder:view&wo_id={$open_workorders.WORK_ORDER_ID}&customer_id={$open_workorders.CUSTOMER_ID}&page_title=Work Order ID {$open_workorders.WORK_ORDER_ID}';" class="row1">
                    <td class="olotd4">{$open_workorders.WORK_ORDER_ID}</td>
                    <td class="olotd4"> {$open_workorders.WORK_ORDER_OPEN_DATE|date_format:"$date_format"}</td>
                    <td class="olotd4" nowrap>
                        <img src="{$theme_images_dir}icons/16x16/view.gif" border="0"
                             onMouseOver="ddrivetip('<b><center>{$translate_workorder_contact_info_title}</b></center><hr><b>{$translate_workorder_phone}: </b>{$open_workorders.CUSTOMER_PHONE}<br> <b>{$translate_workorder_work}: </b>{$open_workorders.CUSTOMER_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$open_workorders.CUSTOMER_MOBILE_PHONE}<br><b>{$translate_workorder_address}</b><br>{$open_workorders.CUSTOMER_ADDRESS}<br>{$open_workorders.CUSTOMER_CITY}, {$open_workorders.CUSTOMER_STATE}<br>{$open_workorders.CUSTOMER_ZIP}');"
                             onMouseOut="hideddrivetip();">
                        <a class="link1" href="?page=customer:customer_details&customer_id={$open_workorders.CUSTOMER_ID}&page_title={$open_workorders.CUSTOMER_DISPLAY_NAME}">{$open_workorders.CUSTOMER_DISPLAY_NAME}</a>
                    </td>
                    <td class="olotd4" nowrap>{$open_workorders.WORK_ORDER_SCOPE}</td>
                    <td class="olotd4">{$open_workorders.WORK_ORDER_CURRENT_STATUS}</td>
                    <td class="olotd4" nowrap>
                        <img src="{$theme_images_dir}icons/16x16/view.gif" border="0"
                             onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact_info_title}</b></center><hr><b>{$translate_workorder_work}: </b>{$open_workorders.EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$open_workorders.EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$open_workorders.EMPLOYEE_HOME_PHONE}');"
                             onMouseOut="hideddrivetip();"> 
                        <a class="link1" href="?page=employees:employee_details&employee_id={$open_workorders.EMPLOYEE_ID}&page_title={$open_workorders.EMPLOYEE_DISPLAY_NAME}">{$open_workorders.EMPLOYEE_DISPLAY_NAME}</a>
                    </td>
                    <td class="olotd4" align="center" nowrap>
                        <a href="?page=workorder:print&wo_id={$open_workorders.WORK_ORDER_ID}&customer_id={$open_workorders.CUSTOMER_ID}&page_title=Print Work Order ID {$open_workorders.WORK_ORDER_ID}&escape=1">
                            <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0"
                                 onMouseOver="ddrivetip('{$translate_workorder_print_the_work_order}');"
                                 onMouseOut="hideddrivetip();">
                        </a>
                        <a href="?page=workorder:view&wo_id={$open_workorders.WORK_ORDER_ID}&customer_id={$open_workorders.CUSTOMER_ID}&page_title=Work Order ID {$open_workorders.WORK_ORDER_ID}">
                            <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0"
                                 onMouseOver="ddrivetip('{$translate_workorder_view_the_work_order}');"
                                 onMouseOut="hideddrivetip();">
                        </a>                                            
                    </td>
                </tr>
                {/foreach}
            </table>
        </td>
    </tr>
    {/if}
</table>