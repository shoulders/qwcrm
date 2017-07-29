<!-- overview_unpaid_workorders_block.tpl -->
<b>{t}Unpaid{/t}</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olohead" width="6"><b>{t}Workorder ID{/t}</b></td>
        <td class="olohead"><b>{t}Opened{/t}</b></td>
        <td class="olohead"><b>{t}Customer{/t}</b></td>
        <td class="olohead"><b>{t}Scope{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
        <td class="olohead"><b>{t}Technician{/t}</b></td>
        <td class="olohead"><b>{t}Action{/t}</b></td>
    </tr>
    {section name=u loop=$unpaid_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$unpaid_workorders[u].work_order_id}&customer_id={$unpaid_workorders[u].customer_id}';" class="row1">

            <!-- ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$unpaid_workorders[u].work_order_id}&customer_id={$unpaid_workorders[u].customer_id}">{$unpaid_workorders[u].work_order_id}<a/></td>

            <!-- Opened -->
            <td class="olotd4"> {$unpaid_workorders[u].work_order_open_date|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Phone{/t}: </b>{$unpaid_workorders[u].customer_phone}<br><b>{t}Fax{/t}: </b>{$unpaid_workorders[u].customer_work_phone}<br><b>{t}Mobile{/t}: </b>{$unpaid_workorders[u].customer_mobile_phone}<br><b>{t}Address{/t}: </b><br>{$unpaid_workorders[u].customer_address}<br>{$unpaid_workorders[u].customer_city}, {$unpaid_workorders[u].customer_state}<br>{$unpaid_workorders[u].customer_zip}');" onMouseOut="hideddrivetip();">                         
                <a class="link1" href="index.php?page=customer:details&customer_id={$unpaid_workorders[u].customer_id}">{$unpaid_workorders[u].customer_display_name}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$unpaid_workorders[u].WORK_ORDER_SCOPE}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {if $unpaid_workorders[u].work_order_status == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                {if $unpaid_workorders[u].work_order_status == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                {if $unpaid_workorders[u].work_order_status == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                {if $unpaid_workorders[u].work_order_status == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                {if $unpaid_workorders[u].work_order_status == '7'}{t}WORKORDER_STATUS_7{/t}{/if}
                {if $unpaid_workorders[u].work_order_status == '8'}{t}WORKORDER_STATUS_8{/t}{/if}
                {if $unpaid_workorders[u].work_order_status == '9'}{t}WORKORDER_STATUS_9{/t}{/if}
                {if $unpaid_workorders[u].work_order_status == '10'}{t}WORKORDER_STATUS_10{/t}{/if}
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                {if $unpaid_workorders[u].employee_display_name == ""}
                    {t}Not Assigned{/t}
                {else}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact Info{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$unpaid_workorders[u].employee_work_phone}<br><b>{t}Mobile{/t}: </b>{$unpaid_workorders[u].employee_mobile_phone}<br><b>{t}Home{/t}: </b>{$unpaid_workorders[u].employee_home_phone}');" onMouseOut="hideddrivetip();">                          
                    <a class="link1" href="index.php?page=user:details&user_id={$unpaid_workorders[u].employee_id}">{$unpaid_workorders[u].employee_display_name}</a>
                {/if}
            </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?page=workorder:print&workorder_id={$unpaid_workorders[u].work_order_id}&customer_id={$unpaid_workorders[u].customer_id}&theme=off">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('{t}Print The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$unpaid_workorders[u].work_order_id}&customer_id={$unpaid_workorders[u].customer_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>                                        
            </td>
        </tr>
    {sectionelse}
        <tr>
            <td colspan="7" class="error">{t}There are No Unpaid Work Orders{/t}</td>
        </tr>
    {/section}
</table>