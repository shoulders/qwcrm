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
        <td class="olohead"><b>{t}Client{/t}</b></td>
        <td class="olohead"><b>{t}Scope{/t}</b></td>
        <td class="olohead"><b>{t}Status{/t}</b></td>
        <td class="olohead"><b>{t}Employee{/t}</b></td>
        <td class="olohead"><b>{t}Action{/t}</b></td>
    </tr>
    {section name=w loop=$display_workorders}        
        <tr class="row1" onmouseover="this.className='row2';" onmouseout="this.className='row1';"{if $display_workorders[w].workorder_status != 'deleted'} onDblClick="window.location='index.php?component=workorder&page_tpl={if $display_workorders[w].workorder_is_closed}details{else}edit{/if}&workorder_id={$display_workorders[w].workorder_id}';"{/if}>
            <td class="olotd4" nowrap>
                {if $display_workorders[w].workorder_status == 'deleted'}
                    {$display_workorders[w].workorder_id}
                {else}                    
                    <a href="index.php?component=workorder&page_tpl=details&workorder_id={$display_workorders[w].workorder_id}">{$display_workorders[w].workorder_id}</a>
                {/if}
            </td>
            <td class="olotd4"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$display_workorders[w].invoice_id}">{$display_workorders[w].invoice_id}</a></td>
            <td class="olotd4"> {$display_workorders[w].workorder_opened_on|date_format:$date_format}</td>
            <td class="olotd4">{$display_workorders[w].workorder_closed_on|date_format:$date_format}</td>
            <td class="olotd4" nowrap>
                {if $display_workorders[w].workorder_status != 'deleted'}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<b><center>{t}Client Info{/t}</b></center><hr><b>{t}Contact{/t}:</b> {$display_workorders[w].client_first_name} {$display_workorders[w].client_last_name}<br><b>{t}Phone{/t}: </b>{$display_workorders[w].client_phone}<br><b>{t}Mobile{/t}: </b>{$display_workorders[w].client_mobile_phone}<br><b>{t}Fax{/t}: </b>{$display_workorders[w].client_phone}<br><b>{t}Address{/t}: </b><br>{$display_workorders[w].client_address|nl2br|regex_replace:"/[\r\t\n]/":" "}<br>{$display_workorders[w].client_city}<br>{$display_workorders[w].client_state}<br>{$display_workorders[w].client_zip}<br>{$display_workorders[w].client_country}');" onMouseOut="hideddrivetip();">
                    <a class="link1" href="index.php?component=client&page_tpl=details&client_id={$display_workorders[w].client_id}">{$display_workorders[w].client_display_name}</a>
                {/if}
            </td>
            <td class="olotd4" nowrap>
                {if $display_workorders[w].workorder_scope}
                    {$display_workorders[w].workorder_scope|truncate:20:"..."|htmlentities|regex_replace:"/[\t\r\n']/":" "}&nbsp;
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Scope{/t}</strong></div><hr><div>{$display_workorders[w].workorder_scope|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                 {/if}
            </td>
            <td class="olotd4" align="center">
                {section name=s loop=$workorder_statuses}    
                    {if $display_workorders[w].workorder_status == $workorder_statuses[s].status_key}{t}{$workorder_statuses[s].display_name}{/t}{/if}        
                {/section}                                                                     
            </td>
            <td class="olotd4" nowrap>
                <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Employee Info{/t}</b></center><hr><b>{t}Employee{/t}: </b>{$display_workorders[w].employee_display_name}<br><b>{t}Mobile{/t}: </b>{$display_workorders[w].employee_work_mobile_phone}<br><b>{t}Home{/t}: </b>{$display_workorders[w].employee_home_primary_phone}<br><b>{t}Email{/t}: </b>{$display_workorders[w].employee_email}');" onMouseOut="hideddrivetip();">
                <a class="link1" href="index.php?component=user&page_tpl=details&user_id={$display_workorders[w].employee_id}">{$display_workorders[w].employee_display_name}</a>
            </td>            
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?component=workorder&page_tpl=print&workorder_id={$display_workorders[w].workorder_id}&commContent=technician_workorder_slip&commType=htmlBrowser" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?component=workorder&page_tpl=print&workorder_id={$display_workorders[w].workorder_id}&commContent=technician_job_sheet&commType=htmlBrowser" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Technician Job Sheet{/t}');" onMouseOut="hideddrivetip();" />
                </a>
                <a href="index.php?component=workorder&page_tpl=print&workorder_id={$display_workorders[w].workorder_id}&commContent=client_workorder_slip&commType=htmlBrowser" target="_blank">                                                    
                    <img src="{$theme_images_dir}icons/print.gif" alt="Print Works Order" border="0" height="14" width="14" onMouseOver="ddrivetip('{t}Print{/t}<br>{t}Client Work Order Slip{/t}');" onMouseOut="hideddrivetip();" />                                                        
                </a>
                <a href="index.php?component=workorder&page_tpl=details&workorder_id={$display_workorders[w].workorder_id}&client_id={$display_workorders[w].client_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('{t}View The Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>    
            </td>
        </tr>
    {sectionelse}
        <tr>
            <td colspan="9" class="error">{t}There are no work orders.{/t}</td>
        </tr>        
    {/section}
</table>