<!-- display_schedules_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<b>{$block_title}</b>
<table class="olotable" width="100%" border="0" cellpadding="4" cellspacing="0">
    <tr>
        <td class="olohead"><b>{t}Schedule ID{/t}</b></td>
        <td class="olohead"><b>{t}WO ID{/t}</b></td>
        <td class="olohead"><b>{t}Employee{/t}</b></td>
        <td class="olohead"><b>{t}Client{/t}</b></td>        
        <td class="olohead"><b>{t}Start Time{/t}</b></td>
        <td class="olohead"><b>{t}End Time{/t}</b></td>
        <td class="olohead"><b>{t}Note{/t}</b></td>
        <td class="olohead"><b>{t}Action{/t}</b></td>
    </tr>
    {section name=s loop=$display_schedules}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?component=schedule&page_tpl=details&schedule_id={$display_schedules[s].schedule_id}';" class="row1">

            <!-- Schedule ID -->
            <td class="olotd4"><a href="index.php?component=schedule&page_tpl=details&schedule_id={$display_schedules[s].schedule_id}">{$display_schedules[s].schedule_id}</a></td>
            
            <!-- WO ID -->
            <td class="olotd4"><a href="index.php?component=workorder&page_tpl=details&workorder_id={$display_schedules[s].workorder_id}">{$display_schedules[s].workorder_id}</a></td>
            
            <!-- Employee -->
            <td class="olotd4" nowrap>                
                <a class="link1" href="index.php?component=user&page_tpl=details&user_id={$display_schedules[s].employee_id}">{$display_schedules[s].employee_display_name}</a>
            </td> 
            
            <!-- Client -->
            <td class="olotd4" nowrap>                
                <a class="link1" href="index.php?component=client&page_tpl=details&client_id={$display_schedules[s].client_id}">{$display_schedules[s].client_display_name}</a>
            </td>            

            <!-- Start Time -->
            <td class="olotd4"> {$display_schedules[s].start_time|date_format:$date_format}</td>

            <!-- End time -->
            <td class="olotd4">{$display_schedules[s].end_time|date_format:$date_format}</td>
            
            <!-- Note -->            
            <td class="olotd4" nowrap>
                {if $display_schedules[s].note}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<div><strong>{t}Note{/t}</strong></div><hr><div>{$display_schedules[s].note|htmlentities|regex_replace:"/[\t\r\n']/":" "}</div>');" onMouseOut="hideddrivetip();">
                {/if}
             </td>

            <!-- Action -->
            <td class="olotd4" align="center" nowrap>
                <a href="index.php?component=schedule&page_tpl=details&schedule_id={$display_schedules[s].schedule_id}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif" border="0" onMouseOver="ddrivetip('{t}Details{/t}');" onMouseOut="hideddrivetip();"></a>
                <a href="index.php?component=schedule&page_tpl=edit&schedule_id={$display_schedules[s].schedule_id}"><img src="{$theme_images_dir}icons/16x16/small_edit_employee.gif" border="0" onMouseOver="ddrivetip('{t}Edit{/t}');" onMouseOut="hideddrivetip();"></a>&nbsp;
                <a href="index.php?component=schedule&page_tpl=icalendar&schedule_id={$display_schedules[s].schedule_id}&theme=print" target="_blank"><img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('{t}Export{/t}');" onMouseOut="hideddrivetip();"></a>
                <a href="index.php?component=schedule&page_tpl=delete&schedule_id={$display_schedules[s].schedule_id}" onclick="return confirmChoice('{t}Are you Sure you want to delete this Schedule?{/t}');"><img src="{$theme_images_dir}icons/delete.gif" alt="" border="0" height="14" width="14" onMouseOver="ddrivetip('<b>{t}Delete{/t}</b>');" onMouseOut="hideddrivetip();"></a>                 
            </td>            

        </tr>
    {sectionelse}
        <tr>
            <td colspan="9" class="error">{t}There are no schedules.{/t}</td>
        </tr>        
    {/section}
</table>