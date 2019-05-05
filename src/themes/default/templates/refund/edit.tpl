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

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Refund Edit{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}REFUND_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}REFUND_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                    
                                                    <form action="index.php?component=refund&page_tpl=edit&refund_id={$refund_id}" method="post" name="edit_refund" id="edit_refund" autocomplete="off">                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Refund ID{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td colspan="3">{$refund_id}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Client{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td colspan="3"><a href="index.php?component=client&page_tpl=details&client_id={$refund_details.client_id}">{$client_display_name}</a></td>
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
                                                            <td colspan="3">{if $refund_details.invoice_id}<a href="index.php?component=invoice&page_tpl=details&invoice_id={$refund_details.invoice_id}">{$refund_details.invoice_id}</a>{else}{t}n/a{/t}{/if}</td>                                                            
                                                        </tr>                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Item Type{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>                                                                 
                                                                {section name=s loop=$refund_types}    
                                                                    {if $refund_details.item_type == $refund_types[s].type_key}{t}{$refund_types[s].display_name}{/t}{/if}                                                                                
                                                                {/section}                                                                
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"></td>
                                                            <td></td>
                                                        </tr>
                                                        <tr{if !'/^vat_/'|preg_match:$refund_details.tax_system} hidden{/if}>
                                                            <td align="right"><b>{t}Net{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>{$currency_sym}{$refund_details.unit_net|string_format:"%.2f"}</td>
                                                        </tr>
                                                        <tr{if $refund_details.tax_system == 'none'} hidden{/if}>
                                                            <td align="right"><b>{if '/^vat_/'|preg_match:$refund_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if} {t}Rate{/t}</b></td>
                                                            <td>{if $refund_details.vat_tax_code == 'vat_multi_tcode'}{t}n/a{/t}{else}{$currency_sym}{$refund_details.unit_tax_rate|string_format:"%.2f"}{/if}</td>
                                                        </tr>
                                                        <tr{if $refund_details.tax_system == 'none'} hidden{/if}>
                                                            <td align="right"><b>{if '/^vat_/'|preg_match:$refund_details.tax_system}{t}VAT{/t}{else}{t}Sales Tax{/t}{/if}</b></td>
                                                            <td>{$currency_sym}{$refund_details.unit_tax|string_format:"%.2f"}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Gross{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>{$currency_sym} {$refund_details.unit_gross|string_format:"%.2f"}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Status{/t}</b><span style="color: #ff0000"> *</span></td>
                                                            <td>
                                                                {section name=s loop=$refund_statuses}    
                                                                    {if $refund_details.status == $refund_statuses[s].status_key}{t}{$refund_statuses[s].display_name}{/t}{/if}        
                                                                {/section} 
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Note{/t}</b></td>
                                                            <td><textarea class="olotd5" id="note" name="note" cols="50" rows="15">{$refund_details.note}</textarea></td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td colspan="2">
                                                                <button type="submit" name="submit" value="update">{t}Update{/t}</button>
                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=refund&page_tpl=details&refund_id={$refund_id}';">{t}Cancel{/t}</button>
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