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
                    <td class="menuhead2" width="80%">{t}Status{/t} {t}for{/t} <a href="index.php?component=creditnote&page_tpl=details&creditnote_id={$creditnote_id}">{t}Credit Note{/t} {$creditnote_id}</a></td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}CREDITNOTE_STATUS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}CREDITNOTE_STATUS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>  
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">{t}Status{/t}</td>
                                <td class="olohead" align="center">{t}Assign To{/t}</td>                                
                            </tr>
                            <tr>
                            
                                <!-- Assign Status Update -->
                                <td class="olotd4" align="center" width="33%">
                                    <p><b>{t}Current Status{/t}:</b> {$creditnote_status_display_name}</p>
                                    {if $allowed_to_change_status}                                                                               
                                        <form action="index.php?component=creditnote&page_tpl=status&creditnote_id={$creditnote_id}" method="post" name="new_creditnote_status" id="new_creditnote_status">
                                            <b>{t}New Status{/t}: </b>
                                            <select class="olotd4" name="assign_status">
                                                {section name=s loop=$creditnote_statuses}    
                                                    <option value="{$creditnote_statuses[s].status_key}"{if $creditnote_status == $creditnote_statuses[s].status_key} selected{/if}>{t}{$creditnote_statuses[s].display_name}{/t}</option>
                                                {/section}                                            
                                            </select>
                                            <p>&nbsp;</p>
                                            <input type="hidden" name="updated_by" value="{$login_user_id}"> 
                                            <input class="olotd4" name="change_status" value="{t}Update{/t}" type="submit" />                                                                      
                                        </form>
                                    {else}
                                        {t}This creditnote cannot have it's status changed because it's current state does not allow it.{/t}
                                    {/if}
                                </td>

                                <!-- Update Assigned Employee -->
                                <td class="olotd4" align="center" width="33%">
                                    {if $allowed_to_change_employee}                                        
                                        <p>&nbsp;</p>  
                                        <form method="post" action="index.php?component=creditnote&page_tpl=status&creditnote_id={$creditnote_id}">
                                            <select class="olotd4" name="target_employee_id">
                                                {section name=i loop=$active_employees}
                                                    <option value="{$active_employees[i].user_id}" {if $assigned_employee_id == $active_employees[i].user_id} selected {/if}>{$active_employees[i].display_name}</option>
                                                {/section}
                                            </select>
                                            <p>&nbsp;</p>
                                            <input class="olotd4" name="change_employee" value="{t}Update{/t}" type="submit">
                                        </form>                                        
                                    {else}
                                        {t}This creditnote cannot have it's assigned employee changed because it's current state does not allow it.{/t}
                                    {/if}
                                </td>
                                
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table class="olotable" width="100%" border="0" cellpadding="2" cellspacing="0" >
                            <tr>
                                <td class="olohead" align="center">&nbsp;</td>
                                <td class="olohead" align="center">{t}Cancel{/t}</td>
                                <td class="olohead" align="center">{t}Delete{/t}</td>
                            </tr>
                            <tr>
                            
                                <!-- Unused -->
                                <td class="olotd4" align="center" width="33%" height="150"></td>

                                <!-- Cancel Button -->
                                <td class="olotd4" align="center" width="33%"> 
                                    <!-- if creditnote is open and does not have any payments -->                                        
                                    {if $allowed_to_cancel}
                                        <form method="post" action="index.php?component=creditnote&page_tpl=cancel&creditnote_id={$creditnote_id};">
                                            <textarea id="qform[reason_for_cancelling]" name="qform[reason_for_cancelling]" class="olotd5 mceNoEditor" cols="25" rows="3" maxlength="100" onkeydown="return onlyAlphaNumeric(event);" required placeholder="{t}Reason for Cancelling{/t}"/></textarea>
                                            <p>&nbsp;</p>
                                            <input class="olotd4" name="cancel_creditnote" value="{t}Cancel{/t}" type="submit" onclick="confirm('{t}Are you sure you want to cancel this creditnote? All records relating to this creditnote will be kept but will be removed from the relevant financial calculations.{/t}');">
                                        </form>
                                    {else}
                                        {t}This creditnote cannot be cancelled. You can only cancel the creditnote if it is open and does not have any payments.{/t}
                                    {/if}                                
                                </td> 

                                <!-- Delete Button -->                        
                                <td class="olotd4" align="center" width="33%"> 
                                    <!-- if creditnote is open and does not have any payments -->                                        
                                    {if $allowed_to_delete}
                                        <button type="button" class="olotd4" onclick="if (confirm('{t}Are you sure you want to delete this creditnote? All records relating to this creditnote will be removed.{/t}')) window.location.href='index.php?component=creditnote&page_tpl=delete&creditnote_id={$creditnote_id}';">{t}Delete{/t}</button>
                                    {else}
                                        {t}This creditnote cannot be deleted. You can only delete the creditnote if it is open and does not have any payments.{/t}
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