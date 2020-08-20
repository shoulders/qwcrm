<!-- details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<table width="700" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="100%" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}Supplier Details{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?component=supplier&page_tpl=edit&supplier_id={$supplier_id}"><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a>
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}SUPPLIER_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}SUPPLIER_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table class="olotable" border="0" cellpadding="5" cellspacing="5" width="100%" summary="Client Contact">
                                        <tr>
                                            <td class="olohead" colspan="4">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td class="menuhead2">&nbsp;{t}Supplier ID{/t} {$supplier_id}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Name{/t}</b></td>
                                            <td class="menutd">{$supplier_details.display_name}</td>
                                            <td class="menutd"><b>{t}Phone{/t}</b></td>
                                            <td class="menutd">{$supplier_details.primary_phone}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Contact{/t}</b></td>
                                            <td class="menutd" >{$supplier_details.full_name}</td>                                            
                                            <td class="menutd"><b>{t}Mobile{/t}</b></td>
                                            <td class="menutd">{$supplier_details.mobile_phone}</td>
                                        </tr>                                      
                                        <tr>
                                            <td class="menutd"><b>{t}Type{/t}</b></td>
                                            <td class="menutd">
                                                {section name=s loop=$supplier_types}    
                                                    {if $supplier_details.type == $supplier_types[s].type_key}{t}{$supplier_types[s].display_name}{/t}{/if}        
                                                {/section}    
                                            </td>
                                            <td class="menutd" ><b>{t}Fax{/t}</b></td>
                                            <td class="menutd">{$supplier_details.fax}</td>
                                        </tr>                                    
                                        <tr>
                                            <td class="menutd"><b>{t}Website{/t}</b></td>
                                            <td class="menutd"><a href="{$supplier_details.website}" target="_blank">{$supplier_details.website|regex_replace:"/^https?:\/\//":""}</a></td>
                                            <td class="menutd"><b>{t}Email{/t}</b></td>
                                            <td class="menutd"><a href="mailto: {$supplier_details.email}">{$supplier_details.email}</a></td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                      
                                        <tr>
                                            <td class="menutd"><b>{t}Address{/t}</b></td>
                                            <td class="menutd">{$supplier_details.address|nl2br}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>                                      
                                        <tr>
                                            <td class="menutd"><b>{t}City{/t}</b></td>
                                            <td class="menutd">{$supplier_details.city}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>                                       
                                        <tr>
                                            <td class="menutd"><b>{t}State{/t}</b></td>
                                            <td class="menutd">{$supplier_details.state}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>                                    
                                        <tr>
                                            <td class="menutd"><b>{t}Zip{/t}</b></td>
                                            <td class="menutd">{$supplier_details.zip}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Country{/t}</b></td>
                                            <td class="menutd">{$supplier_details.country}</td>
                                            <td class="menutd" colspan="2"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd"><b>{t}Status{/t}</b></td>
                                            <td class="menutd">
                                                {section name=r loop=$supplier_statuses}    
                                                    {if $supplier_details.status == $supplier_statuses[r].status_key}{t}{$supplier_statuses[r].display_name}{/t}{/if}        
                                                {/section} 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Opened On{/t}</b></td>
                                            <td class="menutd">{$supplier_details.opened_on|date_format:$date_format}</td>
                                            <td class="menutd"><b>{t}Closed On{/t}</b></td>
                                            <td class="menutd">{$supplier_details.closed_on|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Last Active{/t}</b></td>
                                            <td class="menutd">{$supplier_details.last_active|date_format:$date_format}</td>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd">&nbsp;</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Description{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$supplier_details.description}</td>
                                            <td class="menutd"></td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>  
                                        <tr>
                                            <td class="menutd"><b>{t}Note{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$supplier_details.note}</td>
                                            <td class="menutd"></td>
                                        </tr>                                                                                                                                                                
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>