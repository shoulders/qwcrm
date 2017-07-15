<!-- status.tpl -->
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}Status for Workorder{/t} {$workorder_id}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}WORKORDER_STATUS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}WORKORDER_STATUS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>  
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{t}Status{/t}</td>
                                <td class="olohead" align="center">{t}Assign To{/t}</td>
                                <td class="olohead" align="center">{t}Delete{/t}</td>
                            </tr>
                            <tr>
                            
                                <!-- Assign Status Update -->
                                <td class="olotd4" align="center" width="33%"> 
                                    <p>&nbsp;</p>                                    
                                    <form action="index.php?page=workorder:status" method="POST" name="new_workorder_status" id="new_workorder_status">
                                        <b>{t}New Status{/t}: </b>
                                        <select class="olotd4" name="assign_status">
                                            <option value="1"{if $workorder_status == 1} selected{/if}>{t}WORKORDER_STATUS_1{/t}</option>
                                            <option value="2"{if $workorder_status == 2} selected{/if}>{t}WORKORDER_STATUS_2{/t}</option>
                                            <option value="3"{if $workorder_status == 3} selected{/if}>{t}WORKORDER_STATUS_3{/t}</option>                                            
                                            <option value="6"{if $workorder_status == 6} selected{/if}>{t}WORKORDER_STATUS_6{/t}</option>
                                            <option value="7"{if $workorder_status == 7} selected{/if}>{t}WORKORDER_STATUS_7{/t}</option>
                                            <option value="8"{if $workorder_status == 8} selected{/if}>{t}WORKORDER_STATUS_8{/t}</option>
                                            <option value="9"{if $workorder_status == 9} selected{/if}>{t}WORKORDER_STATUS_9{/t}</option>
                                            <option value="10"{if $workorder_status == 10} selected{/if}>{t}WORKORDER_STATUS_10{/t}</option>  
                                        </select>                                        
                                        <input type="hidden" name="updated_by" value="{$login_user_id}">
                                        <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                        <p>&nbsp;</p>                                        
                                        <input class="olotd4" name="change_status" value="{t}Update{/t}" type="submit" />                                                                      
                                    </form>
                                </td>

                                <!-- Update Assigned Employee -->
                                <td class="olotd4" align="center" width="33%"> 
                                    <!-- If the employee is assigned to this workorder and it is not closed, or no one is assigned, or the user is an admin - show a dropdown list and update button, else show the assigned employee details instead -->
                                    {if ($assigned_employee_id == $login_user_id && $workorder_status != 6) || $assigned_employee_id == '' || $login_usergroup_id <= 3}
                                        <p>&nbsp;</p>  
                                        <form method="POST" action="index.php?page=workorder:status">
                                            <select name="target_employee_id">
                                                {section name=i loop=$active_employees}
                                                    <option value="{$active_employees[i].user_id}" {if $assigned_employee_id == $active_employees[i].user_id} selected {/if}>{$active_employees[i].display_name}</option>
                                                {/section}
                                            </select>
                                            <p>&nbsp;</p>
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                            <input name="change_employee" value="{t}Update{/t}" type="submit">
                                        </form>                                       
                                    {else}    
                                        <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$assigned_employee_details.EMPLOYEE_WORK_PHONE}<br><b>{t}Mobile{/t}: </b>{$assigned_employee_details.EMPLOYEE_MOBILE_PHONE}<br><b>{t}Home{/t}: </b>{$assigned_employee_details.EMPLOYEE_HOME_PHONE}');" onMouseOut="hideddrivetip();">                                                 
                                        <a class="link1" href="index.php?page=user:details&user_id={$assigned_employee_id}">{$assigned_employee_details.EMPLOYEE_DISPLAY_NAME}</a>
                                    {/if}
                                </td>

                                <!-- Delete Workorder Button -->                        
                                <td class="olotd4" align="center" width="33%"> 
                                    <!-- if work order is created and open, you can delete it, otherwise you cannot -->                                        
                                    {if $workorder_status == 1 || $workorder_status == 10}
                                        <form method="POST" action="index.php?page=workorder:status">
                                            <input name="delete" value="{t}Delete{/t}" type="submit" onClick="return confirmDelete('{t}Are you sure you want to delete this Workorder?{/t}');">
                                            <input type="hidden" name="workorder_id" value="{$workorder_id}">
                                        </form>                                            
                                    {else}
                                        {t}This Work order cannot be deleted. You can only delete the workorder if it's status is either Created or Open and the Work Order has no invoice.{/t}
                                    {/if}                                        
                                </td>                                
                                
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>