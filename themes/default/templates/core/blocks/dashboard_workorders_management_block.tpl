<!-- overview_workorders_Management_block.tpl -->
<b>{t}Management{/t} ({$login_display_name})</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olohead" width="6"><b>{t}WO ID{/t}</b></td>
        <td class="olohead"><b>{t}Opened{/t}</b></td>
        <td class="olohead"><b>{t}Customer{/t}</b></td>
        <td class="olohead"><b>{t}Scope{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
        <td class="olohead"><b>{t}Technician{/t}</b></td>
        <td class="olohead"><b>{t}Action{/t}</b></td>
    </tr>
    {section name=m loop=$management_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$management_workorders[m].workorder_id}&customer_id={$management_workorders[m].customer_id}';" class="row1">

            <!-- ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$management_workorders[m].workorder_id}">{$management_workorders[m].workorder_id}<a/></td>

            <!-- Opened -->
            <td class="olotd4"> {$management_workorders[m].workorder_open_date|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Customer Info{/t}</b></center><hr><b>{t}Contact{/t}:</b> {$management_workorders[m].customer_first_name} {$management_workorders[m].customer_last_name}<br><b>{t}Phone{/t}: </b>{$management_workorders[m].customer_phone}<br><b>{t}Mobile{/t}: </b>{$management_workorders[m].customer_mobile_phone}<br><b>{t}Fax{/t}: </b>{$management_workorders[m].customer_phone}<br><b>{t}Address{/t}: </b><br>{$management_workorders[m].customer_address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$management_workorders[m].customer_city}<br>{$management_workorders[m].customer_state}<br>{$management_workorders[m].customer_zip}<br>{$management_workorders[m].customer_country}');" onMouseOut="hideddrivetip();">                         
                <a class="link1" href="index.php?page=customer:details&customer_id={$management_workorders[m].customer_id}">{$management_workorders[m].customer_display_name}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$management_workorders[m].workorder_scope}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {if $management_workorders[m].workorder_status == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                {if $management_workorders[m].workorder_status == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                {if $management_workorders[m].workorder_status == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                {if $management_workorders[m].workorder_status == '4'}{t}WORKORDER_STATUS_4{/t}{/if}
                {if $management_workorders[m].workorder_status == '5'}{t}WORKORDER_STATUS_5{/t}{/if}
                {if $management_workorders[m].workorder_status == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                {if $management_workorders[m].workorder_status == '7'}{t}WORKORDER_STATUS_7{/t}{/if}                
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                {if $management_workorders[m].employee_display_name == ""}
                    {t}Not Assigned{/t}
                {else}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Technician Info{/t}</b></center><hr><b>{t}Employee{/t}: </b>{$management_workorders[m].employee_display_name}<br><b>{t}Mobile{/t}: </b>{$management_workorders[m].employee_work_mobile_phone}<br><b>{t}Home{/t}: </b>{$management_workorders[m].employee_home_primary_phone}<br><b>{t}Email{/t}: </b>{$management_workorders[m].employee_email}');" onMouseOut="hideddrivetip();">
                    <a class="link1" href="index.php?page=user:details&user_id={$management_workorders[m].employee_id}">{$management_workorders[m].employee_display_name}</a>
                {/if}
            </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?page=workorder:print&workorder_id={$management_workorders[m].workorder_id}&print_content=technician_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?page=workorder:print&workorder_id={$management_workorders[m].workorder_id}&print_content=technician_job_sheet&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Job Sheet{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?page=workorder:print&workorder_id={$management_workorders[m].workorder_id}&print_content=customer_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Customer Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />                                                        
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$management_workorders[m].workorder_id}&customer_id={$management_workorders[m].customer_id}">
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