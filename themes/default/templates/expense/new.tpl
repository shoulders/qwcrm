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

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}New Expense Page{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}EXPENSE_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}EXPENSE_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form action="index.php?page=expense:new" method="post" name="new_expense" id="new_expense">                                                
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td class="menuhead" colspan="3">{t}First Group{/t}</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Expense ID{/t}</b></td>
                                                                            <td>{$new_record_id}</td>
                                                                            <td colspan="2"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Payee{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td colspan="3"><input id="payee" name="payee" class="olotd5" size="50" type="text" maxlength="50" required onkeydown="return onlyName(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Date{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <input id="date" name="date" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,2}(\/|-)[0-9]{1,2}(\/|-)[0-9]{2,2}([0-9]{2,2})?${/literal}" required onkeydown="return onlyDate(event);">
                                                                                <input id="date_button" type="button" value="+">                                                                                
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
                                                                            <td colspan="3"><input id="invoice_id" name="invoice_id" class="olotd5" size="5" type="text" maxlength="10" onkeydown="return onlyNumber(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select id="type" name="type" class="olotd5">
                                                                                    {section name=s loop=$expense_types}    
                                                                                        <option value="{$expense_types[s].expense_type_id}"{if $expense_details.type == $expense_types[s].expense_type_id} selected{/if}>{t}{$expense_types[s].display_name}{/t}</option>
                                                                                    {/section} 
                                                                                </select>
                                                                            </td>                                                                                
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Payment Method{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <select id="payment_method" name="payment_method" class="olotd5">
                                                                                    {section name=s loop=$payment_methods}    
                                                                                        <option value="{$payment_methods[s].manual_method_id}"{if $expense_details.payment_method == $payment_methods[s].manual_method_id} selected{/if}>{t}{$payment_methods[s].display_name}{/t}</option>
                                                                                    {/section} 
                                                                                </select>                                                                            
                                                                            </td>
                                                                        </tr>                                                                                                          
                                                                        <tr>
                                                                            <td align="right"><b>{t}Net Amount{/t}</b></td>
                                                                            <td><a><input name="net_amount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);"></b></a></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><span style="color: #ff0000"></span><b>{t}VAT/Tax{/t} {t}Rate{/t}</td>
                                                                            <td><input name="tax_rate" class="olotd5" size="5" value="{$tax_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumberPeriod(event);"/><b>%</b></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}VAT/Tax{/t} {t}Amount{/t}</b></td>
                                                                            <td><input name="tax_amount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Gross Amount{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><input name="gross_amount" class="olotd5" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumberPeriod(event);"/></td>
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
                                                                    <tr>
                                                                        <td align="right"><b>{t}Items{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                        <td><textarea name="items" class="olotd5 mceCheckForContent" cols="50" rows="15"></textarea></td>
                                                                    </tr> 
                                                                    <tr>
                                                                        <td align="right"><b>{t}Notes{/t}</b></td>
                                                                        <td><textarea name="notes" class="olotd5" cols="50" rows="15"></textarea></td>
                                                                    </tr>                                                                                                                                       
                                                                    <tr>
                                                                        <td></td>
                                                                        <td>
                                                                            <input name="submit" class="olotd5" value="{t}Submit{/t}" type="submit">
                                                                            <input name="submitandnew" class="olotd5" value="{t}Submit and New{/t}" type="submit">
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