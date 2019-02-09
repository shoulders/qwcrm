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
                    <td class="menuhead2" width="80%">{t}Refund Details {/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a href="index.php?component=refund&page_tpl=edit&refund_id={$refund_id}" ><img src="{$theme_images_dir}icons/edit.gif"  alt="" height="16" border="0">{t}Edit{/t}</a>&nbsp;
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REFUND_DETAILS_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REFUND_DETAILS_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                        <td class="menuhead2">&nbsp;{t}Refund ID{/t} {$refund_id}</td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Client ID{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=client&page_tpl=details&client_id={$refund_details.client_id}">{$refund_details.client_id}</a></td>
                                            <td class="menutd"><b>{t}Net Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym}{$refund_details.net_amount}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Date{/t}</b></td>
                                            <td class="menutd" >{$refund_details.date|date_format:$date_format}</td>
                                            <td class="menutd" ><b>{t}VAT Tax Code{/t}</b></td>
                                            <td class="menutd">
                                                {section name=s loop=$vat_tax_codes}
                                                    {if $refund_details.vat_tax_code == $vat_tax_codes[s].tax_key}{t}{$vat_tax_codes[s].display_name}{/t}{/if}
                                                {/section}
                                            </td> 
                                        </tr>
                                        <tr>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd">&nbsp;</td>
                                            <td class="menutd"><b>{t}VAT{/t} {t}Rate{/t}</b></td>
                                            <td class="menutd">{$refund_details.vat_rate|string_format:"%.2f"}}%</td>
                                        </tr> 
                                        <tr>
                                            <td class="menutd"><b>{t}Item Type{/t}</b></td>
                                            <td class="menutd">              
                                                {section name=s loop=$refund_types}
                                                    {if $refund_details.item_type == $refund_types[s].type_key}{t}{$refund_types[s].display_name}{/t}{/if}        
                                                {/section}   
                                            </td>
                                            <td class="menutd"><b>{t}VAT{/t} {t}Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym}{$refund_details.vat_amount}</td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Payment Method{/t}</b></td>
                                            <td class="menutd">
                                                {section name=s loop=$payment_methods}    
                                                    {if $refund_details.payment_method == $payment_methods[s].method_key}{t}{$payment_methods[s].display_name}{/t}{/if}   
                                                {/section}
                                            </td>
                                            <td class="menutd"><b>{t}Gross Amount{/t}</b></td>
                                            <td class="menutd">{$currency_sym}{$refund_details.gross_amount}</td>
                                        </tr>
                                        <tr>
                                            <td class="menutd"><b>{t}Invoice ID{/t}</b></td>
                                            <td class="menutd"><a href="index.php?component=invoice&page_tpl=details&invoice_id={$refund_details.invoice_id}">{$refund_details.invoice_id}</a></td>
                                            <td colspan="2">&nbsp;</td>
                                            
                                        </tr>                                        
                                        <tr class="row2">
                                            <td class="menutd" colspan="4"></td>
                                        </tr>                                        
                                        <tr>
                                            <td class="menutd"><b>{t}Note{/t}</b></td>
                                            <td class="menutd" colspan="3"></td>
                                        </tr>
                                        <tr>
                                            <td class="menutd" colspan="3">{$refund_details.note}</td>
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