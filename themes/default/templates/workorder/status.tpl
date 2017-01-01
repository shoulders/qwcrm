<!-- status.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{$translate_workorder_status_title} {$workorder_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<b>{$translate_workorder_status_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_status_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>  
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{$translate_workorder_status}</td>
                                <td class="olohead" align="center">{$translate_workorder_assign_to}</td>
                                <td class="olohead" align="center">{$translate_workorder_delete}</td>
                            </tr>
                            <tr>
                                {section name=i loop=$single_workorder}
                                    <!-- Assign Status Update -->
                                    <td class="olotd4" align="center" width="33%"> 
                                        <p>&nbsp;</p>                                    
                                        <form action="index.php?page=workorder:status" method="POST" name="new_workorder_status" id="new_workorder_status">
                                            <b>{$translate_workorder_status_new_status}: </b>
                                            <select class="olotd4" name="assign_status">
                                                <option value="1"{if $single_workorder[i].WORK_ORDER_STATUS == 1} selected{/if}>{$translate_workorder_created}</option>
                                                <option value="2"{if $single_workorder[i].WORK_ORDER_STATUS == 2} selected{/if}>{$translate_workorder_assigned}</option>
                                                <option value="3"{if $single_workorder[i].WORK_ORDER_STATUS == 3} selected{/if}>{$translate_workorder_waiting_for_parts}</option>
                                                {*<option value="4"{if $single_workorder[i].WORK_ORDER_STATUS == 4} selected{/if}>---</option>
                                                <option value="5"{if $single_workorder[i].WORK_ORDER_STATUS == 5} selected{/if}>---</option>*}
                                                <option value="6"{if $single_workorder[i].WORK_ORDER_STATUS == 6} selected{/if}>{$translate_workorder_closed}</option>
                                                <option value="7"{if $single_workorder[i].WORK_ORDER_STATUS == 7} selected{/if}>{$translate_workorder_waiting_for_payment}</option>
                                                <option value="8"{if $single_workorder[i].WORK_ORDER_STATUS == 8} selected{/if}>{$translate_workorder_payment_made}</option>
                                                <option value="9"{if $single_workorder[i].WORK_ORDER_STATUS == 9} selected{/if}>{$translate_workorder_pending}</option>
                                                <option value="10"{if $single_workorder[i].WORK_ORDER_STATUS == 10} selected{/if}>{$translate_workorder_open}</option>  
                                            </select>                                        
                                            <input type="hidden" name="updated_by" value="{$login_id}">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <p>&nbsp;</p>                                        
                                            <input class="olotd4" name="change_status" value="{$translate_workorder_update}" type="submit" />                                                                      
                                        </form>
                                    </td>

                                    <!-- Update Assigned Employee -->
                                    <td class="olotd4" align="center" width="33%"> 
                                        <!-- If the employee is assigned to this workorder and it is not closed, or no one is assigned, or the user is an admin show a dropdown list and update button, else show the employee details instead -->
                                        {if ($single_workorder[i].EMPLOYEE_ID == $login_id && $single_workorder[i].WORK_ORDER_STATUS != 6) || $single_workorder[i].EMPLOYEE_ID == '' || $single_workorder[i].EMPLOYEE_TYPE <= 3}
                                            <p>&nbsp;</p>  
                                            <form method="POST" action="index.php?page=workorder:status">
                                                <select name="target_employee_id">
                                                    {section name=i loop=$active_employees}
                                                        <option value="{$active_employees[i].EMPLOYEE_ID}" {if $assigned_employee == $active_employees[i].EMPLOYEE_ID} selected {/if}>{$active_employees[i].EMPLOYEE_DISPLAY_NAME}</option>
                                                    {/section}
                                                </select>
                                                <p>&nbsp;</p>
                                                <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                                <input name="change_employee" value="{$translate_workorder_update}" type="submit">
                                            </form>                                       
                                        {else}    
                                            <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact}</b></center><hr><b>{$translate_workorder_fax}: </b>{$single_workorder[i].EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$single_workorder[i].EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$single_workorder[i].EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();">                                                 
                                            <a class="link1" href="?page=employee:employee_details&employee_id={$single_workorder[i].EMPLOYEE_ID}">{$single_workorder[i].EMPLOYEE_DISPLAY_NAME}</a>
                                        {/if}
                                    </td>
                                {/section}
                                
                                {section name=i loop=$single_workorder}
                                    <!-- Delete Workorder Button -->                        
                                    <td class="olotd4" align="center" width="33%"> 
                                        <!-- if work order is created and open, you can delete it, otherwise you cannot -->                                        
                                        {if $single_workorder[i].WORK_ORDER_STATUS == 1 || $single_workorder[i].WORK_ORDER_STATUS == 10}
                                            <form method="POST" action="index.php?page=workorder:status">
                                                <input name="delete" value="{$translate_workorder_delete}" type="submit" onClick="return confirmDelete('{$translate_workorder_status_confirmdelete}');">
                                                <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            </form>                                            
                                        {else}
                                            {$translate_workorder_status_this_work_order_cannot_be_deleted}
                                        {/if}                                        
                                    </td>
                                {/section}
                                
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>