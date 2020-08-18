<!-- status.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}Status{/t} {t}for{/t} <a href="index.php?component=workorder&page_tpl=details&workorder_id={$workorder_id}">{t}Work order{/t} {$workorder_id}</a></td>                    
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
                                    <p><b>{t}Current Status{/t}:</b> {$workorder_status_display_name}</p>
                                    {if $allowed_to_change_status}
                                        <p>&nbsp;</p>
                                        <form method="post" action="index.php?component=workorder&page_tpl=status&workorder_id={$workorder_id}" name="new_workorder_status" id="new_workorder_status">
                                            <b>{t}New Status{/t}: </b>
                                            <select class="olotd4" name="assign_status">
                                                {section name=s loop=$workorder_statuses}    
                                                    <option value="{$workorder_statuses[s].status_key}"{if $workorder_status == $workorder_statuses[s].status_key} selected{/if}>{t}{$workorder_statuses[s].display_name}{/t}</option>
                                                {/section}                                            
                                            </select>                                                                                        
                                            <p>&nbsp;</p>
                                            <input type="hidden" name="updated_by" value="{$login_user_id}">
                                            <input class="olotd4" name="change_status" value="{t}Update{/t}" type="submit" />                                                                      
                                        </form>
                                    {else}
                                        {t}This work order cannot have it's status changed because it's current state does not allow it.{/t}
                                    {/if}
                                </td>

                                <!-- Update Assigned Employee -->
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_change_employee}
                                        <!-- If the employee is assigned to this workorder and it is not closed, or no one is assigned, or the user is an admin - show a dropdown list and update button, else show the assigned employee details instead -->
                                        {if ($assigned_employee_id == $login_user_id && $workorder_status != 6) || $assigned_employee_id == '' || $login_usergroup_id <= 3}
                                            <p>&nbsp;</p>  
                                            <form method="post" action="index.php?component=workorder&page_tpl=status&workorder_id={$workorder_id}">
                                                <select class="olotd4" name="target_employee_id">
                                                    {section name=i loop=$active_employees}
                                                        <option value="{$active_employees[i].user_id}" {if $assigned_employee_id == $active_employees[i].user_id} selected {/if}>{$active_employees[i].display_name}</option>
                                                    {/section}
                                                </select>
                                                <p>&nbsp;</p>
                                                <input class="olotd4" name="change_employee" value="{t}Update{/t}" type="submit">
                                            </form>                                       
                                        {else}    
                                            <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$assigned_employee_details.employee_work_primary_phone}<br><b>{t}Mobile{/t}: </b>{$assigned_employee_details.employee_mobile_phone}<br><b>{t}Home{/t}: </b>{$assigned_employee_details.employee_home_primary_phone}');" onMouseOut="hideddrivetip();">                                                 
                                            <a class="link1" href="index.php?component=user&page_tpl=details&user_id={$assigned_employee_id}">{$assigned_employee_details.employee_display_name}</a>
                                        {/if}
                                    {else}
                                        {t}This work order cannot have it's assigned employee changed because it's current state does not allow it.{/t}
                                    {/if}
                                </td>

                                <!-- Delete Workorder Button -->                        
                                <td class="olotd4" align="center" width="33%"> 
                                    <!-- if work order is created and open, you can delete it, otherwise you cannot -->                                        
                                    {if $allowed_to_delete}
                                        <button type="button" class="olotd4" onclick="if (confirm('{t}Are you sure you want to delete this Workorder?{/t}')) window.location.href='index.php?component=workorder&page_tpl=delete&workorder_id={$workorder_id}';">{t}Delete{/t}</button>                                                                                
                                    {else}
                                        {t}This work order cannot be deleted. You can only delete the work order if it's status is either unassigned or management and the work order has no invoice.{/t}
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