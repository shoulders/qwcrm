<!-- overview_workorders_on_hold_block.tpl -->
<b>{t}On Hold{/t} ({$login_display_name})</b>
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
    {section name=o loop=$on_hold_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$on_hold_workorders[o].workorder_id}&customer_id={$on_hold_workorders[o].customer_id}';" class="row1">

            <!-- ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$on_hold_workorders[o].workorder_id}">{$on_hold_workorders[o].workorder_id}<a/></td>

            <!-- Opened -->
            <td class="olotd4"> {$on_hold_workorders[o].workorder_open_date|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Customer Info{/t}</b></center><hr><b>{t}Contact{/t}:</b> {$on_hold_workorders[o].customer_first_name} {$on_hold_workorders[o].customer_last_name}<br><b>{t}Phone{/t}: </b>{$on_hold_workorders[o].customer_phone}<br><b>{t}Mobile{/t}: </b>{$on_hold_workorders[o].customer_mobile_phone}<br><b>{t}Fax{/t}: </b>{$on_hold_workorders[o].customer_phone}<br><b>{t}Address{/t}: </b><br>{$on_hold_workorders[o].customer_address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$on_hold_workorders[o].customer_city}<br>{$on_hold_workorders[o].customer_state}<br>{$on_hold_workorders[o].customer_zip}<br>{$on_hold_workorders[o].customer_country}');" onMouseOut="hideddrivetip();">                         
                <a class="link1" href="index.php?page=customer:details&customer_id={$on_hold_workorders[o].customer_id}">{$on_hold_workorders[o].customer_display_name}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$on_hold_workorders[o].workorder_scope}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {if $on_hold_workorders[o].workorder_status == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                {if $on_hold_workorders[o].workorder_status == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                {if $on_hold_workorders[o].workorder_status == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                {if $on_hold_workorders[o].workorder_status == '4'}{t}WORKORDER_STATUS_4{/t}{/if}
                {if $on_hold_workorders[o].workorder_status == '5'}{t}WORKORDER_STATUS_5{/t}{/if}
                {if $on_hold_workorders[o].workorder_status == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                {if $on_hold_workorders[o].workorder_status == '7'}{t}WORKORDER_STATUS_7{/t}{/if}                
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                {if $on_hold_workorders[o].employee_display_name == ""}
                    {t}Not Assigned{/t}
                {else}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Technician Info{/t}</b></center><hr><b>{t}Employee{/t}: </b>{$on_hold_workorders[o].employee_display_name}<br><b>{t}Mobile{/t}: </b>{$on_hold_workorders[o].employee_work_mobile_phone}<br><b>{t}Home{/t}: </b>{$on_hold_workorders[o].employee_home_primary_phone}<br><b>{t}Email{/t}: </b>{$on_hold_workorders[o].employee_email}');" onMouseOut="hideddrivetip();">
                    <a class="link1" href="index.php?page=user:details&user_id={$on_hold_workorders[o].employee_id}">{$on_hold_workorders[o].employee_display_name}</a>
                {/if}
            </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?page=workorder:print&workorder_id={$on_hold_workorders[o].workorder_id}&print_content=technician_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?page=workorder:print&workorder_id={$on_hold_workorders[o].workorder_id}&print_content=technician_job_sheet&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Job Sheet{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?page=workorder:print&workorder_id={$on_hold_workorders[o].workorder_id}&print_content=customer_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Customer Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />                                                        
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$on_hold_workorders[o].workorder_id}&customer_id={$on_hold_workorders[o].customer_id}">
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