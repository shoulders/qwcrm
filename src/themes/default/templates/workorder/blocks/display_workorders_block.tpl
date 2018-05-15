<!-- display_workorders_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olohead"><b>{t}WO ID{/t}</b></td>
        <td class="olohead"><b>{t}INV ID{/t}</b></td>
        <td class="olohead"><b>{t}Opened{/t}</b></td>
        <td class="olohead"><b>{t}Closed{/t}</b></td>
        <td class="olohead"><b>{t}Customer{/t}</b></td>
        <td class="olohead"><b>{t}Scope{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
        <td class="olohead"><b>{t}Technician{/t}</b></td>
        <td class="olohead"><b>{t}Action{/t}</b></td>
    </tr>
    {section name=w loop=$display_workorders}        
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?component=workorder&page_tpl=details&workorder_id={$display_workorders[w].workorder_id}';" class="row1">

            <!-- WO ID -->
            <td class="olotd4"><a href="index.php?component=workorder&page_tpl=details&workorder_id={$display_workorders[w].workorder_id}">{$display_workorders[w].workorder_id}</a></td>

            <!-- INV ID -->
            <td class="olotd4"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$display_workorders[w].invoice_id}">{$display_workorders[w].invoice_id}</a></td>

            <!-- Opened -->
            <td class="olotd4"> {$display_workorders[w].workorder_open_date|date_format:$date_format}</td>

            <!-- Closed -->
            <td class="olotd4">{$display_workorders[w].workorder_close_date|date_format:$date_format}</td>

            <!-- Customer -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Customer Info{/t}</b></center><hr><b>{t}Contact{/t}:</b> {$display_workorders[w].customer_first_name} {$display_workorders[w].customer_last_name}<br><b>{t}Phone{/t}: </b>{$display_workorders[w].customer_phone}<br><b>{t}Mobile{/t}: </b>{$display_workorders[w].customer_mobile_phone}<br><b>{t}Fax{/t}: </b>{$display_workorders[w].customer_phone}<br><b>{t}Address{/t}: </b><br>{$display_workorders[w].customer_address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$display_workorders[w].customer_city}<br>{$display_workorders[w].customer_state}<br>{$display_workorders[w].customer_zip}<br>{$display_workorders[w].customer_country}');" onMouseOut="hideddrivetip();">
                <a class="link1" href="index.php?component=customer&page_tpl=details&customer_id={$display_workorders[w].customer_id}">{$display_workorders[w].customer_display_name}</a>
            </td>

            <!-- Scope -->
            <td class="olotd4" nowrap>{$display_workorders[w].workorder_scope}</td>

            <!-- Status -->
            <td class="olotd4" align="center">
                {section name=s loop=$workorder_statuses}    
                    {if $display_workorders[w].workorder_status == $workorder_statuses[s].status_key}{t}{$workorder_statuses[s].display_name}{/t}{/if}        
                {/section}                                                                     
            </td>

            <!-- Employee -->
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Technician Info{/t}</b></center><hr><b>{t}Employee{/t}: </b>{$display_workorders[w].employee_display_name}<br><b>{t}Mobile{/t}: </b>{$display_workorders[w].employee_work_mobile_phone}<br><b>{t}Home{/t}: </b>{$display_workorders[w].employee_home_primary_phone}<br><b>{t}Email{/t}: </b>{$display_workorders[w].employee_email}');" onMouseOut="hideddrivetip();">
                <a class="link1" href="index.php?component=user&page_tpl=details&user_id={$display_workorders[w].employee_id}">{$display_workorders[w].employee_display_name}</a>
            </td>            

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?component=workorder&page_tpl=print&workorder_id={$display_workorders[w].workorder_id}&print_content=technician_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?component=workorder&page_tpl=print&workorder_id={$display_workorders[w].workorder_id}&print_content=technician_job_sheet&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Job Sheet{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?component=workorder&page_tpl=print&workorder_id={$display_workorders[w].workorder_id}&print_content=customer_workorder_slip&print_type=print_html&theme=print" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Customer Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />                                                        
                </a>
                <a href="index.php?component=workorder&page_tpl=details&workorder_id={$display_workorders[w].workorder_id}&customer_id={$display_workorders[w].customer_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('{t}View The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>    
            </td>            

        </tr>
    {sectionelse}
        <tr>
            <td colspan="6" class="error">{t}There are no work orders.{/t}</td>
        </tr>        
    {/section}
</table>