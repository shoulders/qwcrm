<!-- edit.tpl -->
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
<script>{include file="`$theme_js_dir_finc`components/expense_other.js"}</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Other Income Edit{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}OTHERINCOME_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}OTHERINCOME_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </a>
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                            <tr>
                                <td width="100%" valign="top" >
                                    <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0" >
                                        <tr>
                                            <td>                                          
                                                <table width="100%" cellpadding="2" cellspacing="2" border="0">  
                                                    
                                                    <form action="index.php?component=otherincome&page_tpl=edit&otherincome_id={$otherincome_id}" method="post" name="edit_otherincome" id="edit_otherincome" autocomplete="off" class="amend_values">                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Other Income ID{/t}</b></td>
                                                            <td colspan="3">{$otherincome_id}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Payee{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td colspan="3"><input id="payee" name="qform[payee]" class="olotd5" size="50" value="{$otherincome_details.payee}" type="text" maxlength="50" required onkeydown="return onlyName(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Date{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <input id="date" name="qform[date]" class="olotd5" size="10" value="{$otherincome_details.date|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
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
                                                            <td align="right"><b>{t}Item Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                <select id="type" name="qform[type]" class="olotd5"> 
                                                                    {section name=s loop=$otherincome_types}    
                                                                        <option value="{$otherincome_types[s].type_key}"{if $otherincome_details.type == $otherincome_types[s].type_key} selected{/if}>{t}{$otherincome_types[s].display_name}{/t}</option>
                                                                    {/section}    
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr{if !'/^vat_/'|preg_match:$otherincome_details.tax_system} hidden{/if}>
                                                            <td align="right"><b>{t}Net{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><input id="unit_net" name="qform[unit_net]" class="olotd5" style="border-width: medium;" size="10" value="{$otherincome_details.unit_net}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);" onkeyup="calculateTotals('unit_net');"></td>
                                                        </tr>                                                        
                                                        <tr{if !'/^vat_/'|preg_match:$otherincome_details.tax_system} hidden{/if}>
                                                            <td align="right"><b>{t}VAT{/t}</b></td>
                                                            <td><input id="unit_tax" name="qform[unit_tax]" class="olotd5" size="10" value="{$otherincome_details.unit_tax}" type="text" maxlength="10" pattern="{literal}[0-9]{1,7}(.[0-9]{0,2})?{/literal}" required onkeydown="return onlyNumberPeriod(event);" onkeyup="calculateTotals('unit_tax');"/></td>
                                                        </tr> 
                                                        <tr>
                                                            <td align="right"><b>{t}Gross{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><input id="unit_gross" name="qform[unit_gross]" class="olotd5"{if !'/^vat_/'|preg_match:$otherincome_details.tax_system}style="border-width: medium;"{/if} size="10" value="{$otherincome_details.unit_gross}" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumberPeriod(event);"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Status{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                {section name=s loop=$otherincome_statuses}    
                                                                    {if $otherincome_details.status == $otherincome_statuses[s].status_key}{t}{$otherincome_statuses[s].display_name}{/t}{/if}        
                                                                {/section} 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Items{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td><textarea class="olotd5 mceCheckForContent" id="items" name="qform[items]" cols="50" rows="15">{$otherincome_details.items}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Note{/t}</b></td>
                                                            <td><textarea class="olotd5" id="note" name="qform[note]" cols="50" rows="15">{$otherincome_details.note}</textarea></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td colspan="2">
                                                                <input type="hidden" name="qform[otherincome_id]" value="{$otherincome_details.otherincome_id}">
                                                                <button type="submit" name="submit" value="update">{t}Update{/t}</button>
                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=otherincome&page_tpl=details&otherincome_id={$otherincome_id}';">{t}Cancel{/t}</button>
                                                            </td>                                                            
                                                        </tr>
                                                    </form>
                                                    
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
        </td>
    </tr>
</table>