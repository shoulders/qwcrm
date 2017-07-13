<!-- details_header_block.tpl -->
<table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
    <tr>
        <td class="olohead" align="center">{t}Workorder ID{/t}</td>
        <td class="olohead" align="center">{t}Opened{/t}</td>        
        <td class="olohead" align="center">{t}Scope{/t}</td>                
        <td class="olohead" align="center">{t}Status{/t}</td>
        <td class="olohead" align="center">{t}Assigned To{/t}</td>
        <td class="olohead" align="center">{t}Last Change{/t}</td>
    </tr>
    <tr>
        
        <!-- ID -->
        <td class="olotd4" align="center">{$single_workorder.WORK_ORDER_ID}</td>
        
        <!-- Opened -->
        <td class="olotd4" align="center">{$single_workorder.WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>        
        
        <!-- Scope -->
        <td class="olotd4" valign="middle" align="center">{$single_workorder.WORK_ORDER_SCOPE}</td>
        
        <!-- Status -->
        <td class="olotd4" align="center">
            {if $single_workorder.WORK_ORDER_STATUS == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
            {if $single_workorder.WORK_ORDER_STATUS == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
            {if $single_workorder.WORK_ORDER_STATUS == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
            {if $single_workorder.WORK_ORDER_STATUS == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
            {if $single_workorder.WORK_ORDER_STATUS == '7'}{t}WORKORDER_STATUS_7{/t}{/if}
            {if $single_workorder.WORK_ORDER_STATUS == '8'}{t}WORKORDER_STATUS_8{/t}{/if}
            {if $single_workorder.WORK_ORDER_STATUS == '9'}{t}WORKORDER_STATUS_9{/t}{/if}
            {if $single_workorder.WORK_ORDER_STATUS == '10'}{t}WORKORDER_STATUS_10{/t}{/if}
        </td>
        
        <!-- Assigned To -->
        <td class="olotd4" align="center">
            <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$single_workorder.EMPLOYEE_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$single_workorder.EMPLOYEE_MOBILE_PHONE}<br><b>{t}Home{/t}: </b>{$single_workorder.EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();">                
           <a class="link1" href="index.php?page=user:details&user_id={$single_workorder.EMPLOYEE_ID}">{$single_workorder.EMPLOYEE_DISPLAY_NAME}</a>
        </td>
        
        <!-- Last Change -->
        <td class="olotd4" align="center">{$single_workorder.LAST_ACTIVE|date_format:$date_format}</td>  
        
     </tr>
</table>