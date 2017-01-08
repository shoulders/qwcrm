<!-- details_header_block.tpl -->
<table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
    <tr>
        <td class="olohead" align="center">{$translate_workorder_id}</td>
        <td class="olohead" align="center">{$translate_workorder_opened}</td>        
        <td class="olohead" align="center">{$translate_workorder_scope}</td>                
        <td class="olohead" align="center">{$translate_workorder_status}</td>
        <td class="olohead" align="center">{$translate_workorder_assigned_to}</td>
        <td class="olohead" align="center">{$translate_workorder_last_change}</td>
    </tr>
    <tr>
        <!-- ID -->
        <td class="olotd4" align="center">{$single_workorder[i].WORK_ORDER_ID}</td>
        
        <!-- Opened -->
        <td class="olotd4" align="center">{$single_workorder[i].WORK_ORDER_OPEN_DATE|date_format:$date_format}</td>        
        
        <!-- Scope -->
        <td class="olotd4" valign="middle" align="center">{$single_workorder[i].WORK_ORDER_SCOPE}</td>
        
        <!-- Status -->
        <td class="olotd4" align="center">
            {if $single_workorder[i].WORK_ORDER_STATUS == '1'}{$translate_workorder_created}{/if}
            {if $single_workorder[i].WORK_ORDER_STATUS == '2'}{$translate_workorder_assigned}{/if}
            {if $single_workorder[i].WORK_ORDER_STATUS == '3'}{$translate_workorder_waiting_for_parts}{/if}
            {if $single_workorder[i].WORK_ORDER_STATUS == '6'}{$translate_workorder_closed}{/if}
            {if $single_workorder[i].WORK_ORDER_STATUS == '7'}{$translate_workorder_waiting_for_payment}{/if}
            {if $single_workorder[i].WORK_ORDER_STATUS == '8'}{$translate_workorder_payment_made}{/if}
            {if $single_workorder[i].WORK_ORDER_STATUS == '9'}{$translate_workorder_pending}{/if}
            {if $single_workorder[i].WORK_ORDER_STATUS == '10'}{$translate_workorder_open}{/if}
        </td>
        
        <!-- Assigned To -->
        <td class="olotd4" align="center">
            <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0"
                onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact}</b></center><hr><b>{$translate_workorder_fax}: </b>{$single_workorder[i].EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$single_workorder[i].EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$single_workorder[i].EMPLOYEE_HOME_PHONE}');"
                onMouseOut="hideddrivetip();">
           <a class="link1" href="?page=employee:employee_details&employee_id={$single_workorder[i].EMPLOYEE_ID}&page_title={$single_workorder[i].EMPLOYEE_DISPLAY_NAME}">{$single_workorder[i].EMPLOYEE_DISPLAY_NAME}</a>
        </td>
        
        <!-- Last Change -->
        <td class="olotd4" align="center">{$single_workorder[i].LAST_ACTIVE|date_format:"$date_format"}</td>  
        
     </tr>
</table>