<!-- overview_assigned_workorders_block.tpl -->
<b>{t}workorder_assigned{/t}</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olohead" width="6"><b>{t}Workorder ID{/t}</b></td>
        <td class="olohead"><b>{t}Opened{/t}</b></td>
        <td class="olohead"><b>{t}Customer{/t}</b></td>
        <td class="olohead"><b>{t}Scope{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
        <td class="olohead"><b>{t}Tech{/t}</b></td>
        <td class="olohead"><b>{t}Action{/t}</b></td>
    </tr>
    {section name=a loop=$assigned_workorders}    
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$assigned_workorders[a].WORK_ORDER_ID}&customer_id={$assigned_workorders[a].CUSTOMER_ID}';" class="row1">

            <!-- ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$assigned_workorders[a].WORK_ORDER_ID}&customer_id={$assigned_workorders[a].CUSTOMER_ID}">{$assigned_workorders[a].WORK_ORDER_ID}</a></td>

            <!-- Opened -->
            <td class="olotd4"> {$assigned_workorders[a].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Contact Info{/t}</b></center><hr><b>{t}Phone{/t}: </b>{$assigned_workorders[a].CUSTOMER_PHONE}<br> <b>{t}Fax{/t}: </b>{$assigned_workorders[a].CUSTOMER_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$assigned_workorders[a].CUSTOMER_MOBILE_PHONE}<br><b>{t}Address{/t}: </b><br>{$assigned_workorders[a].CUSTOMER_ADDRESS}<br>{$assigned_workorders[a].CUSTOMER_CITY}, {$assigned_workorders[a].CUSTOMER_STATE}<br>{$assigned_workorders[a].CUSTOMER_ZIP}');" onMouseOut="hideddrivetip();">                                                  
                <a class="link1" href="index.php?page=customer:details&customer_id={$assigned_workorders[a].CUSTOMER_ID}">{$assigned_workorders[a].CUSTOMER_DISPLAY_NAME}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$assigned_workorders[a].WORK_ORDER_SCOPE}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {if $assigned_workorders[a].WORK_ORDER_STATUS == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                {if $assigned_workorders[a].WORK_ORDER_STATUS == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                {if $assigned_workorders[a].WORK_ORDER_STATUS == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                {if $assigned_workorders[a].WORK_ORDER_STATUS == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                {if $assigned_workorders[a].WORK_ORDER_STATUS == '7'}{t}WORKORDER_STATUS_7{/t}{/if}
                {if $assigned_workorders[a].WORK_ORDER_STATUS == '8'}{t}WORKORDER_STATUS_8{/t}{/if}
                {if $assigned_workorders[a].WORK_ORDER_STATUS == '9'}{t}WORKORDER_STATUS_9{/t}{/if}
                {if $assigned_workorders[a].WORK_ORDER_STATUS == '10'}{t}WORKORDER_STATUS_10{/t}{/if}
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact Info{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$assigned_workorders[a].EMPLOYEE_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$assigned_workorders[a].EMPLOYEE_MOBILE_PHONE}<br><b>{t}Home{/t}: </b>{$assigned_workorders[a].EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();">                                                  
                <a class="link1" href="index.php?page=employee:details&employee_id={$assigned_workorders[a].EMPLOYEE_ID}">{$assigned_workorders[a].EMPLOYEE_DISPLAY_NAME}</a>
            </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?page=workorder:print&workorder_id={$assigned_workorders[a].WORK_ORDER_ID}&customer_id={$assigned_workorders[a].CUSTOMER_ID}&theme=off" target="new" >
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('{t}Print The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$assigned_workorders[a].WORK_ORDER_ID}&customer_id={$assigned_workorders[a].CUSTOMER_ID}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('{t}View The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>    
            </td>
        </tr>
    {sectionelse}
        <tr>
            <td colspan="7" class="error">{t}There are No Assigned Work Orders{/t}</td>
        </tr>        
    {/section}
</table>