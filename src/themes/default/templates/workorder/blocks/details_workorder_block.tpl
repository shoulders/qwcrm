<!-- details_workorder_block.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}

<!-- Workorder Information -->

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
        <td class="olotd4" align="center"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$workorder_details.invoice_id}">{$workorder_details.invoice_id}</a></td>                                                                
        
        <!-- Opened -->
        <td class="olotd4" align="center">{$workorder_details.open_date|date_format:$date_format}</td>        
        
        <!-- Scope -->
        <td class="olotd4" valign="middle" align="center">{$workorder_details.scope}</td>
        
        <!-- Status -->
        <td class="olotd4" align="center">
            {section name=s loop=$workorder_statuses}
                {if $workorder_details.status == $workorder_statuses[s].status_key}{t}{$workorder_statuses[s].display_name}{/t}{/if}        
            {/section}      
        </td>
        
        <!-- Assigned To -->
        <td class="olotd4" align="center">
            <img src="{$theme_images_dir}icons/16x16/view.gif" alt="" border="0" onMouseOver="ddrivetip('<center><b>{t}Contact{/t}</b></center><hr><b>{t}Fax{/t}: </b>{$employee_details.work_primary_phone}<br><b>{t}Mobile{/t}: </b>{$employee_details.mobile_phone}<br><b>{t}Home{/t}: </b>{$employee_details.home_primary_phone}');" onMouseOut="hideddrivetip();">                
           <a class="link1" href="index.php?component=user&page_tpl=details&user_id={$workorder_details.employee_id}">{$employee_details.display_name}</a>
        </td>
        
        <!-- Last Change -->
        <td class="olotd4" align="center">{$workorder_details.last_active|date_format:$date_format}</td>  
        
     </tr>
</table>
<br>

<!-- Workorder Description -->

<table class="olotable" width="100%" border="0" cellpadding="0" cellspacing="0">
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Description{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    {if $workorder_details.status != 6}
                                        <a href="index.php?component=workorder&page_tpl=details_edit_description&workorder_id={$workorder_details.workorder_id}">
                                            <img src="{$theme_images_dir}icons/16x16/small_edit.gif" alt="" border="0" onMouseOver="ddrivetip('{t}Click to edit description{/t}');" onMouseOut="hideddrivetip();">                                                 
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="olotd4">
            <table width="100%" cellspacing="0" cellpadding="4">
                <tr>
                    <td>{$workorder_details.description}<br></td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<br>

<!-- Workorder Comments -->

<table class="olotable" width="100%" border="0"  cellpadding="0" cellspacing="0" summary="Work order display">
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Comments{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    {if $workorder_details.status != 6}
                                        <a href="index.php?component=workorder&page_tpl=details_edit_comment&workorder_id={$workorder_id}">
                                            <img src="{$theme_images_dir}icons/16x16/small_edit.gif" onMouseOver="ddrivetip('{t}Click to edit comments{/t}');" onMouseOut="hideddrivetip();">                                                 
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="menutd">
            <table width="100%" cellpadding="4" cellspacing="0">
                <tr>
                    <td>{$workorder_details.comments}<br></td>
                </tr>
            </table>    
        </td>    
    </tr>
</table>
<br>

<!-- Workorder Resolution -->

<table class="olotable" border="0" width="100%" cellpadding="0" cellspacing="0" >
    <tr>
        <td class="olohead">
            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Resolution{/t}</td>
                    <td class="menuhead2" width="20%" align="right">
                        <table cellpadding="2" cellspacing="2" border="0">
                            <tr>
                                <td width="33%" align="right">
                                    {if $workorder_details.status != 6}
                                        <a href="index.php?component=workorder&page_tpl=details_edit_resolution&workorder_id={$workorder_details.workorder_id}">
                                            <img src="{$theme_images_dir}icons/16x16/small_edit.gif" border="0" onMouseOver="ddrivetip('{t}Click to edit resolution{/t}');" onMouseOut="hideddrivetip();">
                                        </a>
                                    {/if}
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="menutd">
            <table width="100%" cellpadding="4" cellspacing="0" border="0">
                <tr>
                    <td>                        
                        {if $workorder_details.closed_by != ''}
                            <p>
                                <b>{t}Closed by{/t}: </b>{$employee_details.display_name}<br>
                                <b>{t}Date{/t}: </b>{$workorder_details.close_date|date_format:$date_format}<br>
                            </p>
                        {/if}
                        <div>{$workorder_details.resolution}</div>                        
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>