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
            <table width="100%" cellpadding="4" cellspacing="0" border="0">                
                <tr>                    
                    <td class="menuhead2" width="80%">{t}Other Income Details{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?component=otherincome&page_tpl=edit&otherincome_id={$otherincome_id}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a>&nbsp;
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}OTHERINCOME_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}OTHERINCOME_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                        <td class="menuhead2">&nbsp;{t}Other Income ID{/t} {$otherincome_id}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Payee{/t}</b></td>
                                            <td class="menutd">{$otherincome_details.payee}</td>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd">&nbsp;</td>                                                                                      
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Date{/t}</b></td>
                                            <td class="menutd" >{$otherincome_details.date|date_format:$date_format}</td>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd">&nbsp;</td>                                                                                    
                                        </tr>
                                        <tr>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd">&nbsp;</td>
                                            {if '/^vat_/'|preg_match:$otherincome_details.tax_system}
                                                <td class="menutd"><b>{t}Net{/t}</b></td>
                                                <td class="menutd">{$currency_sym}{$otherincome_details.unit_net|string_format:"%.2f"}</td>
                                            {else}
                                                <td class="menutd">&nbsp;</td>
                                                <td class="menutd">&nbsp;</td>
                                            {/if}                                          
                                        </tr> 
                                        <tr>
                                            <td class="menutd"><b>{t}Item Type{/t}</b></td>
                                            <td class="menutd">              
                                                {section name=s loop=$otherincome_types}
                                                    {if $otherincome_details.type == $otherincome_types[s].type_key}{t}{$otherincome_types[s].display_name}{/t}{/if}        
                                                {/section}   
                                            </td>
                                            {if '/^vat_/'|preg_match:$otherincome_details.tax_system}
                                                <td class="menutd"><b>{t}VAT{/t}</b></td>
                                                <td class="menutd">{$currency_sym}{$otherincome_details.unit_tax|string_format:"%.2f"}</td>
                                            {else}
                                                <td class="menutd">&nbsp;</td>
                                                <td class="menutd">&nbsp;</td>
                                            {/if}
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"></td>
                                            <td class="menutd"></td>
                                            <td class="menutd"><b>{t}Gross{/t}</b></td>
                                            <td class="menutd">{$currency_sym}{$otherincome_details.unit_gross|string_format:"%.2f"}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Balance{/t}</b></td>
                                            <td class="menutd">{$currency_sym}{$otherincome_details.balance|string_format:"%.2f"}</td>
                                            <td class="menutd"><b>{t}Status{/t}</b></td>
                                            <td class="menutd">
                                                {section name=s loop=$otherincome_statuses}    
                                                    {if $otherincome_details.status == $otherincome_statuses[s].status_key}{t}{$otherincome_statuses[s].display_name}{/t}{/if}        
                                                {/section} 
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Opened On{/t}</b></td>
                                            <td class="menutd">{$otherincome_details.opened_on|date_format:$date_format}</td>
                                            <td class="menutd"><b>{t}Closed On{/t}</b></td>
                                            <td class="menutd">{$otherincome_details.closed_on|date_format:$date_format}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Last Active{/t}</b></td>
                                            <td class="menutd">{$otherincome_details.last_active|date_format:$date_format}</td>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd">&nbsp;</td>
                                        </tr>
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Items{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$otherincome_details.items}</td>
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
                                            <td class="menutd" colspan="3">{$otherincome_details.note}</td>
                                            <td class="menutd"></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td>
                        {if $otherincome_details.status == 'unpaid' || $otherincome_details.status == 'partially_paid'}
                            <button type="button" class="olotd4" onclick="window.location.href='index.php?component=payment&page_tpl=new&type=otherincome&otherincome_id={$otherincome_details.otherincome_id}';">{t}Submit Payment{/t}</button>
                        {/if}
                    </td>
                </tr>
                <!-- Payments -->                                    
                <tr>
                    <td>                                                
                        {include file='payment/blocks/display_payments_block.tpl' display_payments=$display_payments block_title=_gettext("Payments")}
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>