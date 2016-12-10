<!-- status.tpl -->
{section name=i loop=$single_workorder}
    <table width="100%" border="0" cellpadding="20" cellspacing="0">
        <tr>
            <td>
                <table width="700" cellpadding="5" cellspacing="0" border="0" >
                    <tr>
                        <td class="menuhead2" width="80%">{$translate_workorder_status_title} - {$translate_workorder_status_update_work_order_status_for_work_order_id} {$wo_id}</td>
                        <td class="menuhead2" width="20%" align="right" valign="middle">
                            <a>
                                <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" 
                                onMouseOver="ddrivetip('<b>{$translate_workorder_status_help_title|nl2br|regex_replace:"/[\r\t\n]/":" "}</b><hr><p>{$translate_workorder_status_help_content|nl2br|regex_replace:"/[\r\t\n]/":" "}</p>');" 
                                onMouseOut="hideddrivetip();">
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

                                    <!-- Assign Status Update -->
                                    <td class="olotd4" align="center" width="33%"> 
                                        <p>&nbsp;</p>                                    
                                        <form action="index.php?page=workorder:status" method="POST" name="new_workorder_status" id="new_workorder_status">
                                            <b>{$translate_workorder_status_new_status}: </b>
                                            <select class="olotd4" name="assign_status">
                                                <option value="0">{$translate_workorder_not_assigned}</option>
                                                <option value="1">{$translate_workorder_created}</option>
                                                <option value="2">{$translate_workorder_assigned}</option>
                                                <option value="3">{$translate_workorder_waiting_for_parts}</option>                                          
                                            </select>                                        
                                            <input type="hidden" name="updated_by" value="{$login_id}">
                                            <input type="hidden" name="wo_id" value="{$wo_id}">
                                            <p>&nbsp;</p>                                        
                                            <input class="olotd4" name="submit_assign_status" value="{$translate_workorder_update}" type="submit" />                                                                      
                                        </form>
                                    </td>

                                    <!-- Update Assigned Employee -->
                                    <td class="olotd4" align="center" width="33%"> 

                                        <!-- If the work Order is NOT closed -->
                                        {if $single_workorder[i].WORK_ORDER_STATUS != 6}
                                            <!-- If the employee is assigned to this work order, or if no one is assigned, or the user is an admin show update button, else show the employee details instead -->
                                            {if $single_workorder[i].EMPLOYEE_ID == "$login_id" || $single_workorder[i].EMPLOYEE_ID == '' || $single_workorder[i].EMPLOYEE_TYPE == '1'}
                                                <p>&nbsp;</p>  
                                                <form method="POST" action="">                                            
                                                    {$employee_list}
                                                    <p>&nbsp;</p>
                                                    <input type="submit" name="assign_employee" value="{$translate_workorder_update}"/>
                                                </form>                                        
                                            {else}    
                                                <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0"
                                                     onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact}</b></center><hr><b>{$translate_workorder_fax}: </b>{$single_workorder[i].EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$single_workorder[i].EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$single_workorder[i].EMPLOYEE_HOME_PHONE}');"
                                                     onMouseOut="hideddrivetip();">
                                                <a class="link1" href="?page=employee:employee_details&employee_id={$single_workorder[i].EMPLOYEE_ID}&page_title={$single_workorder[i].EMPLOYEE_DISPLAY_NAME}">{$single_workorder[i].EMPLOYEE_DISPLAY_NAME}</a>
                                            {/if}                                      
                                        {else}
                                            <!-- If the user is an admin show update button, else show the employee details instead -->
                                            {if $single_workorder[i].EMPLOYEE_ID == "$login_id" || $single_workorder[i].EMPLOYEE_ID == '' || $single_workorder[i].EMPLOYEE_TYPE == '1'}
                                                <p>&nbsp;</p>  
                                                <form method="POST" action="">
                                                    {$employee_list}
                                                    <p>&nbsp;</p>
                                                    <input type="submit" name="assign_employee" value="{$translate_workorder_update}"/>
                                                </form>                                        
                                            {else}    
                                                <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0"
                                                     onMouseOver="ddrivetip('<center><b>{$translate_workorder_contact}</b></center><hr><b>{$translate_workorder_fax}: </b>{$single_workorder[i].EMPLOYEE_WORK_PHONE}<br><b>{$translate_workorder_mobile}: </b>{$single_workorder[i].EMPLOYEE_MOBILE_PHONE}<br><b>{$translate_workorder_home}: </b>{$single_workorder[i].EMPLOYEE_HOME_PHONE}');"
                                                     onMouseOut="hideddrivetip();">
                                                <a class="link1" href="?page=employee:employee_details&employee_id={$single_workorder[i].EMPLOYEE_ID}&page_title={$single_workorder[i].EMPLOYEE_DISPLAY_NAME}">{$single_workorder[i].EMPLOYEE_DISPLAY_NAME}</a>
                                            {/if}                      
                                        {/if}

                                    </td>

                                    <!-- Delete Workorder Button -->                        
                                    <td class="olotd4" align="center" width="33%"> 
                                        <!-- if work order is created and open, you can delete it, otherwise you cannot -->
                                        {if $single_workorder[i].WORK_ORDER_CURRENT_STATUS == '1' || $single_workorder[i].WORK_ORDER_CURRENT_STATUS == '10'}
                                            <form method="POST" action="">
                                                <input type="submit" name="delete" value="{$translate_workorder_delete}"/>
                                            </form>
                                        {else}
                                            {$translate_workorder_status_this_work_order_cannot_be_deleted}
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
{/section}