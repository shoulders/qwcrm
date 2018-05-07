<!-- overview_workorders_assigned_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{t}Assigned{/t}</b>
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
    {section name=a loop=$assigned_workorders}    
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$assigned_workorders[a].workorder_id}&customer_id={$assigned_workorders[a].customer_id}';" class="row1">

            <!-- ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$assigned_workorders[a].workorder_id}">{$assigned_workorders[a].workorder_id}</a></td>

            <!-- Opened -->
            <td class="olotd4">{$assigned_workorders[a].workorder_open_date|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Customer Info{/t}</b></center><hr><b>{t}Contact{/t}:</b> {$assigned_workorders[a].customer_first_name} {$assigned_workorders[a].customer_last_name}<br><b>{t}Phone{/t}: </b>{$assigned_workorders[a].customer_phone}<br><b>{t}Mobile{/t}: </b>{$assigned_workorders[a].customer_mobile_phone}<br><b>{t}Fax{/t}: </b>{$assigned_workorders[a].customer_phone}<br><b>{t}Address{/t}: </b><br>{$assigned_workorders[a].customer_address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$assigned_workorders[a].customer_city}<br>{$assigned_workorders[a].customer_state}<br>{$assigned_workorders[a].customer_zip}<br>{$assigned_workorders[a].customer_country}');" onMouseOut="hideddrivetip();">                         
                <a class="link1" href="index.php?page=customer:details&customer_id={$assigned_workorders[a].customer_id}">{$assigned_workorders[a].customer_display_name}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$assigned_workorders[a].workorder_scope}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {section name=s loop=$workorder_statuses}    
                    {if $assigned_workorders[a].workorder_status == $workorder_statuses[s].status_key}{t}{$workorder_statuses[s].display_name}{/t}{/if}        
                {/section}              
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Technician Info{/t}</b></center><hr><b>{t}Employee{/t}: </b>{$assigned_workorders[a].employee_display_name}<br><b>{t}Mobile{/t}: </b>{$assigned_workorders[a].employee_work_mobile_phone}<br><b>{t}Home{/t}: </b>{$assigned_workorders[a].employee_home_primary_phone}<br><b>{t}Email{/t}: </b>{$assigned_workorders[a].employee_email}');" onMouseOut="hideddrivetip();">
                <a class="link1" href="index.php?page=user:details&user_id={$assigned_workorders[a].employee_id}">{$assigned_workorders[a].employee_display_name}</a>
            </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?page=workorder:print&workorder_id={$assigned_workorders[a].workorder_id}&print_content=technician_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?page=workorder:print&workorder_id={$assigned_workorders[a].workorder_id}&print_content=technician_job_sheet&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Job Sheet{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?page=workorder:print&workorder_id={$assigned_workorders[a].workorder_id}&print_content=customer_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Customer Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />                                                        
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$assigned_workorders[a].workorder_id}&customer_id={$assigned_workorders[a].customer_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('{t}View The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>    
            </td>
            
        </tr>
    {sectionelse}
        <tr>
            <td colspan="7" class="error">{t}There are no assigned work orders{/t}</td>
        </tr>        
    {/section}
</table>