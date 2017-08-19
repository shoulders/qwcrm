<!-- details_workorder_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
    <tr>
        <td class="olohead" align="center">{t}WO ID{/t}</td>
        <td class="olohead" align="center">{t}INV ID{/t}</b></td>
        <td class="olohead" align="center">{t}Opened{/t}</td>        
        <td class="olohead" align="center">{t}Scope{/t}</td>                
        <td class="olohead" align="center">{t}Status{/t}</td>
        <td class="olohead" align="center">{t}Assigned To{/t}</td>
        <td class="olohead" align="center">{t}Last Change{/t}</td>
    </tr>
    <tr>
        
        <!-- WO ID -->
        <td class="olotd4" align="center">{$workorder_details.workorder_id}</td>
        
        <!-- INV ID -->
        <td class="olotd4" align="center"><a href="index.php?page=invoice:details&invoice_id={$workorder_details.invoice_id}">{$workorder_details.invoice_id}</a></td>                                                                
        
        <!-- Opened -->
        <td class="olotd4" align="center">{$workorder_details.open_date|date_format:$date_format}</td>        
        
        <!-- Scope -->
        <td class="olotd4" valign="middle" align="center">{$workorder_details.scope}</td>
        
        <!-- Status -->
        <td class="olotd4" align="center">
            {if $workorder_details.status == '1'}{t}WORKORDER_STATUS_1{/t}{/if}
            {if $workorder_details.status == '2'}{t}WORKORDER_STATUS_2{/t}{/if}
            {if $workorder_details.status == '3'}{t}WORKORDER_STATUS_3{/t}{/if}
            {if $workorder_details.status == '4'}{t}WORKORDER_STATUS_4{/t}{/if}
            {if $workorder_details.status == '5'}{t}WORKORDER_STATUS_5{/t}{/if}
            {if $workorder_details.status == '6'}{t}WORKORDER_STATUS_6{/t}{/if}
            {if $workorder_details.status == '7'}{t}WORKORDER_STATUS_7{/t}{/if}            
        </td>
        
        <!-- Assigned To -->
        <td class="olotd4" align="center">
            <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$employee_details.work_primary_phone}<br><b>{t}Mobile{/t}: </b>{$employee_details.mobile_phone}<br><b>{t}Home{/t}: </b>{$employee_details.home_primary_phone}');" onMouseOut="hideddrivetip();">                
           <a class="link1" href="index.php?page=user:details&user_id={$workorder_details.employee_id}">{$employee_details.display_name}</a>
        </td>
        
        <!-- Last Change -->
        <td class="olotd4" align="center">{$workorder_details.last_active|date_format:$date_format}</td>  
        
     </tr>
</table>