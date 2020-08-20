<!-- new.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>

<table width="100%"   border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}New Refund{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}REFUND_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}REFUND_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">                        
                        <table class="olotable" width="100%" border="0" cellpadding="5" cellspacing="0">
                            <tr>
                                <td class="menutd">
                                    <table class="menutable" width="100%" border="0" cellpadding="2" cellspacing="2" >
                                        <tr>
                                            <td>                                               
                                                <form method="post" name="new_refund" id="new_refund" autocomplete="off">                                                
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td class="menuhead" colspan="3">{t}First Group{/t}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Client{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td colspan="3">                                                                                
                                                                                <a href="index.php?component=client&page_tpl=details&client_id={$refund_details.client_id}">{$client_display_name}</a>
                                                                                <input id="client_id" name="qform[client_id]" class="olotd5" size="5" value="{$refund_details.client_id}" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Date{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <input id="date" name="qform[date]" class="olotd5" size="10" value="{$refund_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
                                                                                <button type="button" id="date_button">+</button>
                                                                                <script>                                                                                
                                                                                    Calendar.setup( {
                                                                                        trigger     : "date_button",
                                                                                        inputField  : "date",
                                                                                        dateFormat  : "{$date_format}"
                                                                                    } );                                                                                  
                                                                                </script>                                                                                
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                        <tr>
                                                                            <td align="right"><b>{t}Invoice ID{/t}</b></td>
                                                                            <td colspan="3">
                                                                                <a href="index.php?component=invoice&page_tpl=details&invoice_id={$refund_details.invoice_id}">{$refund_details.invoice_id}</a>
                                                                                <input id="invoice_id" name="qform[invoice_id]" value="{$refund_details.invoice_id}" type="hidden">
                                                                            </td>
                                                                        </tr>                                                                        
                                                                        <tr>
                                                                            <td align="right"><b>{t}Item Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                {section name=s loop=$refund_types}    
                                                                                    {if $refund_details.item_type == $refund_types[s].type_key}{t}{$refund_types[s].display_name}{/t}{/if}                                                                                        
                                                                                {/section}
                                                                                <input id="item_type" name="qform[item_type]" class="olotd5" size="5" value="{$refund_details.item_type}" type="hidden">                                                                                
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"></td>
                                                                            <td></td>
                                                                        </tr>                                                                                                          
                                                                        <tr{if !'/^vat_/'|preg_match:$qw_tax_system} hidden{/if}>
                                                                            <td align="right"><b>{t}Net{/t}</b><span style="color: #ff0000"> *</span></td>                                                                               
                                                                            <td>
                                                                                 {$currency_sym}{$refund_details.unit_net}
                                                                                <input id="unit_net" name="qform[unit_net]" value="{$refund_details.unit_net|string_format:"%.2f"}" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                        <tr{if !'/^vat_/'|preg_match:$qw_tax_system} hidden{/if}>
                                                                            <td align="right"><b>{t}VAT Tax Code{/t}</b><span style="color: #ff0000"> *</span></td>                                                                               
                                                                            <td>
                                                                                {section name=s loop=$vat_tax_codes}
                                                                                       {if $refund_details.vat_tax_code == $vat_tax_codes[s].tax_key}{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t} @ {$vat_tax_codes[s].rate|string_format:"%.2f"}%{/if}
                                                                                {/section} 
                                                                                <input id="vat_tax_code" name="qform[vat_tax_code]" value="{$refund_details.vat_tax_code}" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                        <tr{if $qw_tax_system == 'no_tax'} hidden{/if}>
                                                                            <td align="right"><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b><span style="color: #ff0000"> *</span></td>                                                                               
                                                                            <td>
                                                                                {if $refund_details.vat_tax_code == 'TVM'}{t}n/a{/t}{else}{$currency_sym}{$refund_details.unit_tax_rate|string_format:"%.2f"}{/if}
                                                                                <input id="unit_tax_rate" name="qform[unit_tax_rate]" value="{$refund_details.unit_tax_rate|string_format:"%.2f"}" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                        <tr{if $qw_tax_system == 'no_tax'} hidden{/if}>
                                                                            <td align="right"><b>{if '/^vat_/'|preg_match:$qw_tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                {$currency_sym}{$refund_details.unit_tax|string_format:"%.2f"}
                                                                                <input id="unit_tax" name="qform[unit_tax]" value="{$refund_details.unit_tax|string_format:"%.2f"}" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Gross{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                 {$currency_sym}{$refund_details.unit_gross|string_format:"%.2f"}
                                                                                 <input id="unit_gross" name="qform[unit_gross]" value="{$refund_details.unit_gross|string_format:"%.2f"}" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menuhead" colspan="2">{t}Additional Group{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td align="right"><b>{t}Note{/t}</b></td>
                                                                            <td><textarea id="note" name="qform[note]" class="olotd5" cols="50" rows="15">{$refund_details.note}</textarea></td>
                                                                        </tr>                                                                        
                                                                    </tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <input type="hidden" name="qform[workorder_id]" value="{$refund_details.workorder_id}">
                                                                                <input type="hidden" name="qform[tax_system]" value="{$refund_details.tax_system}">
                                                                                <button type="submit" name="submit" value="submit" onclick="return confirm('{t}Are You sure you want to continue without payment?{/t}');">{t}Submit{/t}</button> 
                                                                                <button type="submit" name="submit" value="submitandpayment">{t}Submit and Payment{/t}</button>
                                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=refund&page_tpl=search';">{t}Cancel{/t}</button>
                                                                            </td>
                                                                        </tr>
                                                                </table>
                                                            </td>
                                                        </tr>                                  
                                                    </table>
                                                </form>
                                            </td>
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