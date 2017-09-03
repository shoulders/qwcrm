<!-- details_workorder_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{t}Open Work Orders{/t}</b>
<table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td class="olohead">{t}WO ID{/t}</td>
        <td class="olohead"><b>{t}INV ID{/t}</b></td>
        <td class="olohead">{t}Date Opened{/t}</td>
        <td class="olohead">{t}Customer{/t}</td>
        <td class="olohead">{t}Scope{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Technician{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=o loop=$open_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$open_workorders[o].workorder_id}&customer_id={$open_workorders[o].customer_id}';" class="row1">
            
            <!-- WO ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$open_workorders[o].workorder_id}">{$open_workorders[o].workorder_id}</a></td>

            <!-- INV ID -->
            <td class="olotd4"><a href="index.php?page=invoice:details&invoice_id={$open_workorders[o].invoice_id}">{$open_workorders[o].invoice_id}</a></td>

            <!-- Opened -->
            <td class="olotd4">{$open_workorders[o].workorder_open_date|date_format:$date_format}</td>
            
            <!-- Customer -->
            <td class="olotd4">{$open_workorders[o].customer_display_name}</td>
            
            <!-- Scope -->
            <td class="olotd4">{$open_workorders[o].workorder_scope}</td>
            
            <!-- Status -->
            <td class="olotd4">
                {section name=s loop=$workorder_statuses}    
                    {if $open_workorders[o].workorder_status == $workorder_statuses[s].status_key}{t}{$workorder_statuses[s].display_name}{/t}{/if}        
                {/section}               
            </td>
            
            <!-- Employee -->
            <td class="olotd4">
                {if $open_workorders[o].employee_id != ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Technician Info{/t}</b></center><hr><b>{t}Employee{/t}: </b>{$open_workorders[o].employee_display_name}<br><b>{t}Mobile{/t}: </b>{$open_workorders[o].employee_work_mobile_phone}<br><b>{t}Home{/t}: </b>{$open_workorders[o].employee_home_primary_phone}<br><b>{t}Email{/t}: </b>{$open_workorders[o].employee_email}');" onMouseOut="hideddrivetip();">
                {else}
                    {t}Not Assigned{/t}
                {/if}
            </td>
            
            <!-- Action -->
            <td class="olotd4" align="center">
                <a href="index.php?page=workorder:print&workorder_id={$open_workorders[o].workorder_id}&customer_id={$open_workorders[o].customer_id}&theme=off" target="new">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{t}Print{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$open_workorders[o].workorder_id}&customer_id={$open_workorders[o].customer_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif"  alt="" border="0" onMouseOver="ddrivetip('{t}View Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>
            </td>
            
        </tr>
    {/section}
</table>
<br>
<b>{t}Closed Work Orders{/t}</b>
<table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td class="olohead">{t}WO ID{/t}</td>
        <td class="olohead"><b>{t}INV ID{/t}</b></td>
        <td class="olohead">{t}Date Opened{/t}</td>
        <td class="olohead">{t}Customer{/t}</td>
        <td class="olohead">{t}Scope{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Technician{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=c loop=$closed_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$closed_workorders[c].workorder_id}&customer_id={$closed_workorders[c].customer_id}';" class="row1">
            
            <!-- WO ID -->
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$closed_workorders[c].workorder_id}">{$closed_workorders[c].workorder_id}</a></td>

            <!-- INV ID -->
            <td class="olotd4"><a href="index.php?page=invoice:details&invoice_id={$closed_workorders[c].invoice_id}">{$closed_workorders[c].invoice_id}</a></td>
                                                                
            <!-- Opened -->
            <td class="olotd4">{$closed_workorders[c].workorder_open_date|date_format:$date_format}</td>
            
            <!-- Customer -->
            <td class="olotd4">{$closed_workorders[c].customer_display_name}</td>
            
            <!-- Scope -->
            <td class="olotd4">{$closed_workorders[c].workorder_scope}</td>
            
            <!-- Status -->
            <td class="olotd4">
                {section name=s loop=$workorder_statuses}    
                    {if $closed_workorders[c].workorder_status == $workorder_statuses[s].status_key}{t}{$workorder_statuses[s].display_name}{/t}{/if}        
                {/section}                 
            </td>
            
            <!-- Employee -->
            <td class="olotd4">
                {if $closed_workorders[c].employee_id != ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Technician Info{/t}</b></center><hr><b>{t}Employee{/t}: </b>{$closed_workorders[c].employee_display_name}<br><b>{t}Mobile{/t}: </b>{$closed_workorders[c].employee_work_mobile_phone}<br><b>{t}Home{/t}: </b>{$closed_workorders[c].employee_home_primary_phone}<br><b>{t}Email{/t}: </b>{$closed_workorders[c].employee_email}');" onMouseOut="hideddrivetip();">
                    <a class="link1" href="index.php?page=user:details&user_id={$closed_workorders[c].employee_id}">{$closed_workorders[c].employee_display_name}</a>
                {else}
                    {t}Not Assigned{/t}
                {/if}
            </td>
            
            <!-- Action -->
            <td class="olotd4" align="center">
                <a href="index.php?page=workorder:print&workorder_id={$closed_workorders[c].workorder_id}&customer_id={$closed_workorders[c].customer_id}&theme=off" target="new">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{t}Print{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$closed_workorders[c].workorder_id}&customer_id={$closed_workorders[c].Customer_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{t}View Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>
            </td>
            
        </tr>
    {/section}
</table>