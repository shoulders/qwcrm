<!-- overview_workorders_waiting_for_parts_block.tpl -->
<b>{t}Waiting For Parts{/t} ({$login_display_name})</b>
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
    {section name=w loop=$waiting_for_parts_workorders}        
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$waiting_for_parts_workorders[w].workorder_id}&customer_id={$waiting_for_parts_workorders[w].customer_id}';" class="row1">

            <!-- ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$waiting_for_parts_workorders[w].workorder_id}">{$waiting_for_parts_workorders[w].workorder_id}</a></td>

            <!-- Opened -->
            <td class="olotd4"> {$waiting_for_parts_workorders[w].workorder_open_date|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Customer Info{/t}</b></center><hr><b>{t}Contact{/t}:</b> {$waiting_for_parts_workorders[w].customer_first_name} {$waiting_for_parts_workorders[w].customer_last_name}<br><b>{t}Phone{/t}: </b>{$waiting_for_parts_workorders[w].customer_phone}<br><b>{t}Mobile{/t}: </b>{$waiting_for_parts_workorders[w].customer_mobile_phone}<br><b>{t}Fax{/t}: </b>{$waiting_for_parts_workorders[w].customer_phone}<br><b>{t}Address{/t}: </b><br>{$waiting_for_parts_workorders[w].customer_address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$waiting_for_parts_workorders[w].customer_city}<br>{$waiting_for_parts_workorders[w].customer_state}<br>{$waiting_for_parts_workorders[w].customer_zip}<br>{$waiting_for_parts_workorders[w].customer_country}');" onMouseOut="hideddrivetip();">
                <a class="link1" href="index.php?page=customer:details&customer_id={$waiting_for_parts_workorders[w].customer_id}">{$waiting_for_parts_workorders[w].customer_display_name}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$waiting_for_parts_workorders[w].workorder_scope}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {if $waiting_for_parts_workorders[w].workorder_status == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                {if $waiting_for_parts_workorders[w].workorder_status == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                {if $waiting_for_parts_workorders[w].workorder_status == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                {if $waiting_for_parts_workorders[w].workorder_status == '4'}{t}WORKORDER_STATUS_4{/t}{/if}
                {if $waiting_for_parts_workorders[w].workorder_status == '5'}{t}WORKORDER_STATUS_5{/t}{/if}
                {if $waiting_for_parts_workorders[w].workorder_status == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                {if $waiting_for_parts_workorders[w].workorder_status == '7'}{t}WORKORDER_STATUS_7{/t}{/if}                
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                {if $waiting_for_parts_workorders[w].employee_display_name == ''}
                    {t}Not Assigned{/t}
                {else}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Technician Info{/t}</b></center><hr><b>{t}Employee{/t}: </b>{$waiting_for_parts_workorders[w].employee_display_name}<br><b>{t}Mobile{/t}: </b>{$waiting_for_parts_workorders[w].employee_work_mobile_phone}<br><b>{t}Home{/t}: </b>{$waiting_for_parts_workorders[w].employee_home_primary_phone}<br><b>{t}Email{/t}: </b>{$waiting_for_parts_workorders[w].employee_email}');" onMouseOut="hideddrivetip();">
                    <a class="link1" href="index.php?page=user:details&user_id={$waiting_for_parts_workorders[w].employee_id}">{$waiting_for_parts_workorders[w].employee_display_name}</a>
                {/if}
            </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?page=workorder:print&workorder_id={$waiting_for_parts_workorders[w].workorder_id}&print_content=technician_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?page=workorder:print&workorder_id={$waiting_for_parts_workorders[w].workorder_id}&print_content=technician_job_sheet&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Job Sheet{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?page=workorder:print&workorder_id={$waiting_for_parts_workorders[w].workorder_id}&print_content=customer_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Customer Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />                                                        
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$waiting_for_parts_workorders[w].workorder_id}&customer_id={$waiting_for_parts_workorders[w].customer_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('{t}View The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>                                        
            </td>
            
        </tr>
    {sectionelse}
        <tr>
            <td colspan="7" class="error">{t}There are No Work Orders Waiting for Parts{/t}</td>
        </tr>        
    {/section}
</table>