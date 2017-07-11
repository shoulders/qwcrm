<!-- details_open_workorders_block.tpl -->
<table class="olotable" width="100%" border="0" cellpadding="5">
    <tr>
        <td class="olohead">{t}WO ID{/t}</td>
        <td class="olohead">{t}Date Opened{/t}</td>
        <td class="olohead">{t}Customer{/t}</td>
        <td class="olohead">{t}Scope{/t}</td>
        <td class="olohead">{t}Status{/t}</td>
        <td class="olohead">{t}Action{/t}</td>
    </tr>
    {section name=a loop=$open_workorders}
        <tr onmouseover="this.className='row2';" onmouseout="this.className='row1';" onDblClick="window.location='index.php?page=workorder:details&workorder_id={$open_workorders[a].WORK_ORDER_ID}';" class="row1">
            <td class="olotd4"><a href="index.php?page=workorder:details&workorder_id={$open_workorders[a].WORK_ORDER_ID}">{$open_workorders[a].WORK_ORDER_ID}</a></td>
            <td class="olotd4">{$open_workorders[a].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>
            <td class="olotd4"><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>Customer Contact</b><hr></center><b>Home: </b>{$open_workorders[a].CUSTOMER_PHONE}<br><b>{t}Work{/t}: </b>{$open_workorders[a].CUSTOMER_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$open_workorders[a].CUSTOMER_MOBILE_PHONE}');"onMouseOut="hideddrivetip();">{$open_workorders[a].CUSTOMER_DISPLAY_NAME}</td>                                                        
            <td class="olotd4"><img src="{$theme_images_dir}icons/16x16/view.gif" border="0" onMouseOver="ddrivetip('<center><b>{t}Description{/t}</b><hr></center>{$open_workorders[a].WORK_ORDER_DESCRIPTION}');" onMouseOut="hideddrivetip();">{$open_workorders[a].WORK_ORDER_SCOPE}</td>
            <td class="olotd4">
                {if $open_workorders[a].WORK_ORDER_STATUS == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '7'}{t}WORKORDER_STATUS_7{/t}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '8'}{t}WORKORDER_STATUS_8{/t}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '9'}{t}WORKORDER_STATUS_9{/t}{/if}
                {if $open_workorders[a].WORK_ORDER_STATUS == '10'}{t}WORKORDER_STATUS_10{/t}{/if}           
            </td>            
            <td class="olotd4" align="center">
                <a href="index.php?page=workorder:print&workorder_id={$open_workorders[a].WORK_ORDER_ID}&customer_id={$open_workorders[a].CUSTOMER_ID}&theme=off" target="new"><img src="{$theme_images_dir}icons/16x16/fileprint.gif" border="0" onMouseOver="ddrivetip('{t}Print The Work Order{/t}');" onMouseOut="hideddrivetip();"></a>
                <a href="index.php?page=workorder:details&workorder_id={$open_workorders[a].WORK_ORDER_ID}&customer_id={$open_workorders[a].CUSTOMER_ID}"><img src="{$theme_images_dir}icons/16x16/viewmag.gif"  border="0" onMouseOver="ddrivetip('{t}View The Work Order{/t}');" onMouseOut="hideddrivetip();"></a>
            </td>
        </tr>
    {/section}
</table>