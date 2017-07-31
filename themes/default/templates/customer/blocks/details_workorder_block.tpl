<!-- details_workorder_block.tpl -->
<b>{t}Open Work Orders{/t}</b>
<table class="olotable" width="100%" border="0" cellpadding="1" cellspacing="0">
    <tr>
        <td class="olohead">{t}WO ID{/t}</td>
        <td class="olohead">{t}Date Opened{/t}</td>
        <td class="olohead">{t}Customer{/t}</td>
        <td class="olohead">{t}Scope{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Tech{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=a loop=$open_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$open_workorders[a].workorder_id}&customer_id={$open_workorders[a].customer_id}';" class="row1">
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$open_workorders[a].workorder_id}&customer_id={$open_workorders[a].customer_id}">{$open_workorders[a].workorder_id}</a></td>
            <td class="olotd4">{$open_workorders[a].open_date|date_format:$date_format}</td>
            <td class="olotd4">{$open_workorders[a].display_name}</td>
            <td class="olotd4">{$open_workorders[a].scope}</td>
            <td class="olotd4">{$open_workorders[a].status}</td>
            <td class="olotd4">
                {if $open_workorders[a].employee_id != ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Work{/t} </b>{$open_workorders[a].employee_work_phone}<br><b>{t}Mobile{/t} </b>{$open_workorders[a].employee_mobile_phone}<br><b>{t}Home{/t}:</b> {$open_workorders[a].employee_home_phone}');" onMouseOut="hideddrivetip();"><a class="link1" href="index.php?page=user:details&user_id={$open_workorders[a].employee_id}">{$open_workorders[a].employee_display_name}</a>
                {else}
                    {t}Not Assigned{/t}
                {/if}
            </td>
            <td class="olotd4" align="center">
                <a href="index.php?page=workorder:print&workorder_id={$open_workorders[a].workorder_id}&customer_id={$open_workorders[a].customer_id}&theme=off" target="new">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{t}Print{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$open_workorders[a].workorder_id}&customer_id={$open_workorders[a].customer_id}">
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
        <td class="olohead">{t}Date Opened{/t}</td>
        <td class="olohead">{t}Customer{/t}</td>
        <td class="olohead">{t}Scope{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Tech{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=b loop=$closed_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$closed_workorders[b].workorder_id}&customer_id={$closed_workorders[b].customer_id}';" class="row1">
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$closed_workorders[b].workorder_id}&customer_id={$closed_workorders[b].customer_id}">{$closed_workorders[b].workorder_id}</a></td>
            <td class="olotd4">{$closed_workorders[b].open_date|date_format:$date_format}</td>
            <td class="olotd4">{$closed_workorders[b].display_name}</td>
            <td class="olotd4">{$closed_workorders[b].scope}</td>
            <td class="olotd4">{$closed_workorders[b].status}</td>
            <td class="olotd4">
                {if $closed_workorders[b].employee_id != ''}
                    <img src="{$theme_images_dir}icons/16x16/view.gif" border="0" alt="" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Work{/t} </b>{$open_workorders[a].employee_work_phone}<br><b>{t}Mobile{/t} </b>{$open_workorders[a].employee_mobile_phone}<br><b>{t}Home{/t}</b> {$closed_workorders[a].employee_home_phone}');" onMouseOut="hideddrivetip();">
                    <a class="link1" href="index.php?page=user:details&user_id={$closed_workorders[b].employee_id}">{$closed_workorders[b].employee_display_name}</a>
                {else}
                    {t}Not Assigned{/t}
                {/if}
            </td>
            <td class="olotd4" align="center">
                <a href="index.php?page=workorder:print&workorder_id={$closed_workorders[b].workorder_id}&customer_id={$closed_workorders[b].customer_id}&theme=off" target="new">
                    <img src="{$theme_images_dir}icons/16x16/fileprint.gif" alt="" border="0" onMouseOver="ddrivetip('{t}Print{/t}');" onMouseOut="hideddrivetip();">
                </a>
                <a href="index.php?page=workorder:details&workorder_id={$closed_workorders[b].workorder_id}&customer_id={$closed_workorders[b].Customer_id}">
                    <img src="{$theme_images_dir}icons/16x16/viewmag.gif" alt="" border="0" onMouseOver="ddrivetip('{t}View Work Order{/t}');" onMouseOut="hideddrivetip();">
                </a>
            </td>
        </tr>
    {/section}
</table>