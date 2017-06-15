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
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$unpaid_workorders[u].WORK_ORDER_ID}&customer_id={$unpaid_workorders[u].CUSTOMER_ID}';" class="row1">

            <!-- ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$unpaid_workorders[u].WORK_ORDER_ID}&customer_id={$unpaid_workorders[u].CUSTOMER_ID}">{$unpaid_workorders[u].WORK_ORDER_ID}<a/></td>

            <!-- Opened -->
            <td class="olotd4"> {$unpaid_workorders[u].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Phone{/t}: </b>{$unpaid_workorders[u].CUSTOMER_PHONE}<br><b>{t}Fax{/t}: </b>{$unpaid_workorders[u].CUSTOMER_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$unpaid_workorders[u].CUSTOMER_MOBILE_PHONE}<br><b>{t}Address{/t}: </b><br>{$unpaid_workorders[u].CUSTOMER_ADDRESS}<br>{$unpaid_workorders[u].CUSTOMER_CITY}, {$unpaid_workorders[u].CUSTOMER_STATE}<br>{$unpaid_workorders[u].CUSTOMER_ZIP}');" onMouseOut="hideddrivetip();">                         
                <a class="link1" href="index.php?page=customer:details&customer_id={$unpaid_workorders[u].CUSTOMER_ID}">{$unpaid_workorders[u].CUSTOMER_DISPLAY_NAME}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$unpaid_workorders[u].WORK_ORDER_SCOPE}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {if $unpaid_workorders[u].WORK_ORDER_STATUS == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                {if $unpaid_workorders[u].WORK_ORDER_STATUS == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                {if $unpaid_workorders[u].WORK_ORDER_STATUS == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                {if $unpaid_workorders[u].WORK_ORDER_STATUS == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                {if $unpaid_workorders[u].WORK_ORDER_STATUS == '7'}{t}WORKORDER_STATUS_7{/t}{/if}
                {if $unpaid_workorders[u].WORK_ORDER_STATUS == '8'}{t}WORKORDER_STATUS_8{/t}{/if}
                {if $unpaid_workorders[u].WORK_ORDER_STATUS == '9'}{t}WORKORDER_STATUS_9{/t}{/if}
                {if $unpaid_workorders[u].WORK_ORDER_STATUS == '10'}{t}WORKORDER_STATUS_10{/t}{/if}
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                {if $unpaid_workorders[u].EMPLOYEE_DISPLAY_NAME == ""}
                    {t}Not Assigned{/t}
                {else}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact Info{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$unpaid_workorders[u].EMPLOYEE_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$unpaid_workorders[u].EMPLOYEE_MOBILE_PHONE}<br><b>{t}Home{/t}: </b>{$unpaid_workorders[u].EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();">                          
                    <a class="link1" href="index.php?page=employee:details&employee_id={$unpaid_workorders[u].EMPLOYEE_ID}">{$unpaid_workorders[u].EMPLOYEE_DISPLAY_NAME}</a>
                {/if}
            </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?page=workorder:print&workorder_id={$unpaid_workorders[u].WORK_ORDER_ID}&customer_id={$unpaid_workorders[u].CUSTOMER_ID}&theme=off">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('{t}Print The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$unpaid_workorders[u].WORK_ORDER_ID}&customer_id={$unpaid_workorders[u].CUSTOMER_ID}">
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