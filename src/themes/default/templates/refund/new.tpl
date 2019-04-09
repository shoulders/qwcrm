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
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REFUND_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REFUND_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form action="index.php?component=refund&page_tpl=new" method="post" name="new_refund" id="new_refund" autocomplete="off">                                                
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
                                                                                <input id="client_id" name="client_id" class="olotd5" size="5" value="{$refund_details.client_id}" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Date{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <input id="date" name="date" class="olotd5" size="10" value="{$refund_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);">
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
                                                                                <input id="invoice_id" name="invoice_id" value="{$refund_details.invoice_id}" type="hidden">
                                                                            </td>
                                                                        </tr>                                                                        
                                                                        <tr>
                                                                            <td align="right"><b>{t}Item Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                {section name=s loop=$refund_types}    
                                                                                    {if $refund_details.item_type == $refund_types[s].type_key}{t}{$refund_types[s].display_name}{/t}{/if}                                                                                        
                                                                                {/section}
                                                                                <input id="item_type" name="item_type" class="olotd5" size="5" value="{$refund_details.item_type}" type="hidden">                                                                                
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Payment Method{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select id="payment_method" name="payment_method" class="olotd5">
                                                                                    {section name=s loop=$payment_methods}    
                                                                                        <option value="{$payment_methods[s].method_key}"{if $refund_details.payment_method == $payment_methods[s].method_key} selected{/if}>{t}{$payment_methods[s].display_name}{/t}</option>
                                                                                    {/section} 
                                                                                </select>                                                                            
                                                                            </td>
                                                                        </tr>                                                                                                          
                                                                        <tr>
                                                                            <td align="right"><b>{t}Net Amount{/t}</b><span style="color: #ff0000"> *</span> </td>                                                                               
                                                                            <td>
                                                                                 {$currency_sym}{$refund_details.net_amount}
                                                                                <input id="net_amount" name="net_amount" value="{$refund_details.net_amount}" type="hidden"  onkeyup="calculateTotals('net_amount');">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Tax{/t} {t}Amount{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                {$currency_sym}{$refund_details.tax_amount}
                                                                                <input id="tax_amount" name="tax_amount" value="{$refund_details.tax_amount}" type="hidden">
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Gross Amount{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                 {$currency_sym}{$refund_details.gross_amount}
                                                                                 <input id="gross_amount" name="gross_amount" value="{$refund_details.gross_amount}" type="hidden">
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
                                                                            <td><textarea id="note" name="note" class="olotd5" cols="50" rows="15">{$refund_details.note}</textarea></td>
                                                                        </tr>                                                                        
                                                                    </tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <button type="submit" name="submit" value="submit">{t}Submit{/t}</button>                                                                                
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