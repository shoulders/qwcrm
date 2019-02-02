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
                    <td class="menuhead2" width="80%">{t}Expense Details {/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?component=expense&page_tpl=edit&expense_id={$expense_id}"><img src="{$theme_images_dir}icons/edit.gif" alt="" height="16" border="0">{t}Edit{/t}</a>
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}EXPENSE_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}EXPENSE_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">                        
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
                                                        <td class="menuhead2">&nbsp;{t}Expense ID{/t} {$expense_details.expense_id}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Payee{/t}</b></td>
                                            <td class="menutd">{$expense_details.payee}</td>
                                            <td class="menutd"><b>{t}Net Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$expense_details.net_amount}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Date{/t}</b></td>
                                            <td class="menutd" >{$expense_details.date|date_format:$date_format}</td>
                                            <td class="menutd" ><b>{t}VAT{/t} {t}Rate{/t}</b></td>
                                            <td class="menutd">&nbsp;&nbsp;&nbsp;{$expense_details.vat_rate} %</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Type{/t}</b></td>
                                            <td class="menutd">              
                                                {section name=s loop=$expense_types}    
                                                    {if $expense_details.type == $expense_types[s].type_key}{t}{$expense_types[s].display_name}{/t}{/if}        
                                                {/section}   
                                            </td>
                                            <td class="menutd"><b>{t}VAT{/t} {t}Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$expense_details.vat_amount}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Payment Method{/t}</b></td>
                                            <td class="menutd">
                                                {section name=s loop=$payment_methods}    
                                                    {if $expense_details.payment_method == $payment_methods[s].method_key}{t}{$payment_methods[s].display_name}{/t}{/if}   
                                                {/section}
                                            </td>
                                            <td class="menutd"><b>{t}Gross Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym} {$expense_details.gross_amount}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Invoice ID{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$expense_details.invoice_id}">{$expense_details.invoice_id}</a></td>
                                            <td></td>
                                            <td></td>
                                        </tr>                                        
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Items{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                         </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$expense_details.items}</td>
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
                                            <td class="menutd" colspan="3">{$expense_details.note}</td>
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