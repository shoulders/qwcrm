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
<script>    
    
    $(document).ready(function() {
        
        // Set the intial VAT rate from the selected VAT Tax Code
        var selected_vat_tax_code = $('#vat_tax_code').find('option:selected');
        var tcVatRate = selected_vat_tax_code.data('rate');            
        $('#unit_tax_rate').val(tcVatRate);
        calculateTotals('vat_tax_code');        
        
        // Bind an action to the VAT Tax Code dropdown to update the totals on change
        $('#vat_tax_code').change(function() {            
            var selected = $(this).find('option:selected');
            var tcVatRate = selected.data('rate');            
            $('#unit_tax_rate').val(tcVatRate);
            calculateTotals('vat_tax_code');
        } );
            
        {if !'/^vat_/'|preg_match:$qw_tax_system}

            // Non-VAT Auto Calculations - Automatically populate Net with the Gross figure
            $('#new_otherincome').submit(function( event ) {                   

                // Get input field values
                var unit_gross  = Number(document.getElementById('unit_gross').value);

                // Set the new unit_gross input value
                document.getElementById('unit_net').value = unit_gross.toFixed(2);

            } );

        {/if}
            
    } );  
        
    // VAT Auto Calculations - Automatically calculate totals
    function calculateTotals(fieldName) {

        // Get input field values
        var unit_net  = Number(document.getElementById('unit_net').value);
        var unit_tax_rate    = Number(document.getElementById('unit_tax_rate').value);
        var unit_tax  = Number(document.getElementById('unit_tax').value);

        // Calculations        
        var auto_unit_tax = (unit_net * (unit_tax_rate/100));        
        if(fieldName !== 'unit_tax') {
            var auto_unit_gross = (unit_net + auto_unit_tax);
        } else {            
            var auto_unit_gross = (unit_net + unit_tax);
        }

        // Set the new unit_tax input value if not editing the unit_tax input field
        if(fieldName !== 'unit_tax') {
            document.getElementById('unit_tax').value = auto_unit_tax.toFixed(2);
        }

        {if '/^vat_/'|preg_match:$qw_tax_system}
            // Set the new unit_gross input value
            document.getElementById('unit_gross').value = auto_unit_gross.toFixed(2);
        {/if}

    }    

</script>

<table width="100%"   border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>            
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">{t}New Other Income{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <a>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}OTHERINCOME_NEW_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}OTHERINCOME_NEW_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
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
                                                <form action="index.php?component=otherincome&page_tpl=new" method="post" name="new_otherincome" id="new_otherincome" autocomplete="off">                                                
                                                    <table width="100%" cellpadding="3" cellspacing="0" border="0">
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <table>
                                                                    <tbody align="left">
                                                                        <tr>
                                                                            <td class="menuhead" colspan="3">{t}First Group{/t}</td>
                                                                        </tr>
                                                                       <tr>
                                                                            <td align="right"><b>{t}Payee{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td colspan="3"><input id="payee" name="qform[payee]" class="olotd5" size="50" type="text" maxlength="50" required onkeydown="return onlyName(event);"></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Date{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td>
                                                                                <input id="date" name="qform[date]" class="olotd5" size="10" value="{$smarty.now|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
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
                                                                                <select id="item_type" name="qform[item_type]" class="olotd5" required>
                                                                                    <option selected hidden disabled></option>
                                                                                    {section name=s loop=$otherincome_types}    
                                                                                        <option value="{$otherincome_types[s].type_key}">{t}{$otherincome_types[s].display_name}{/t}</option>
                                                                                    {/section}    
                                                                                </select>
                                                                            </td>
                                                                        </tr>
                                                                        <tr{if !'/^vat_/'|preg_match:$qw_tax_system} hidden{/if}>
                                                                            <td align="right"><b>{t}Net{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><a><input id="unit_net" name="qform[unit_net]" class="olotd5" style="border-width: medium;" size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}"{if '/^vat_/'|preg_match:$qw_tax_system} required{/if} onkeydown="return onlyNumberPeriod(event);" onkeyup="calculateTotals('unit_net');"></b></a></td>
                                                                        </tr>
                                                                        <tr{if !'/^vat_/'|preg_match:$qw_tax_system} hidden{/if}>
                                                                            <td align="right"><b>{t}VAT Tax Code{/t}</b></td>
                                                                            <td>
                                                                                <select id="vat_tax_code" name="qform[vat_tax_code]" class="olotd5">
                                                                                    {if !'/^vat_/'|preg_match:$qw_tax_system}
                                                                                        <option value="TNA" data-rate="0.00"{if $default_vat_tax_code == 'TNA'} selected{/if}>{t}TNA{/t}</option>
                                                                                    {/if}
                                                                                    {section name=s loop=$vat_tax_codes}    
                                                                                        <option value="{$vat_tax_codes[s].tax_key}" data-rate="{$vat_tax_codes[s].rate}"{if $default_vat_tax_code == $vat_tax_codes[s].tax_key} selected{/if}>{$vat_tax_codes[s].tax_key} - {t}{$vat_tax_codes[s].display_name}{/t} @ {$vat_tax_codes[s].rate|string_format:"%.2f"}%</option>
                                                                                    {/section} 
                                                                                </select>   
                                                                            </td>
                                                                        </tr> 
                                                                        <tr{if !'/^vat_/'|preg_match:$qw_tax_system} hidden{/if}>
                                                                            <td align="right"><b>{t}VAT{/t} {t}Rate{/t}</td>
                                                                            <td><input id="unit_tax_rate" name="qform[unit_tax_rate]" class="olotd5" size="5" value="0.00" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);" onkeyup="calculateTotals('unit_tax_rate');" readonly/><b>%</b></td>
                                                                        </tr>
                                                                        <tr{if !'/^vat_/'|preg_match:$qw_tax_system} hidden{/if}>
                                                                            <td align="right"><b>{t}VAT{/t}</b></td>
                                                                            <td><input id="unit_tax" name="qform[unit_tax]" class="olotd5" size="10" value="0.00" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);" onkeyup="calculateTotals('unit_tax');"/></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Gross{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><input id="unit_gross" name="qform[unit_gross]" class="olotd5"{if !'/^vat_/'|preg_match:$qw_tax_system}style="border-width: medium;"{/if} size="10" type="text" maxlength="10" pattern="{literal}^[0-9]{1,7}(.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumberPeriod(event);"/></td>
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
                                                                            <td align="right"><b>{t}Items{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                            <td><textarea id="items" name="qform[items]" class="olotd5 mceCheckForContent" cols="50" rows="15"></textarea></td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td align="right"><b>{t}Note{/t}</b></td>
                                                                            <td><textarea id="note" name="qform[note]" class="olotd5" cols="50" rows="15"></textarea></td>
                                                                        </tr>                                                                        
                                                                    </tbody>
                                                                        <tr>
                                                                            <td colspan="2">
                                                                                <button type="submit" name="submit" value="submit" onclick="return confirm('{t}Are You sure you want to continue without payment?{/t}');">{t}Submit{/t}</button>
                                                                                <button type="submit" name="submitandnew" value="submitandnew" onclick="return confirm('{t}Are You sure you want to continue without payment?{/t}');">{t}Submit and New{/t}</button>
                                                                                <button type="submit" name="submit" value="submitandpayment">{t}Submit and Payment{/t}</button>
                                                                                <button type="button" class="olotd4" onclick="window.location.href='index.php?component=otherincome&page_tpl=search';">{t}Cancel{/t}</button>
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