<!-- install_company_details.tpl -->
{*
 * @package   QWcrm
 * @author    Jon Brown https://quantumwarp.com/
 * @copyright Copyright (C) 2016 - 2017 Jon Brown, All rights reserved.
 * @license   GNU/GPLv3 or later; https://www.gnu.org/licenses/gpl.html
*}
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/jscal2.css" />
<link rel="stylesheet" href="{$theme_js_dir}jscal2/css/steel/steel.css" />
<script src="{$theme_js_dir}jscal2/jscal2.js"></script>
<script src="{$theme_js_dir}jscal2/unicode-letter.js"></script>
<script>{include file="../`$theme_js_dir_finc`jscal2/language.js"}</script>
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="../`$theme_js_dir_finc`editor-config.js"}</script>
<script>

    $(document).ready(function() {

        // Display the relevant Tax boxes
        taxSystemChange();
        
        // Bind an action to the VAT Tax Code dropdown to update the totals on change
        $('#tax_system').change(function() {            
            taxSystemChange();
        } );

    } );
    
    // When user changes Tax system, alter the options
    function taxSystemChange() { 
    
        var tax_system = document.getElementById('tax_system').value;  
        console.log(tax_system);

        if(tax_system === 'none') {        
            $('.sales_tax_rate').hide();
            $('.vat_number').hide();
            $('.vat_flat_rate').hide();
            $('.vat_tax_codes').hide();
        }

        if(tax_system === 'sales_tax_cash') {
            $('.sales_tax_rate').show();
            $('.vat_number').hide();
            $('.vat_flat_rate').hide();
            $('.vat_tax_codes').hide();
        }

        if(tax_system === 'vat_standard') {
            $('.sales_tax_rate').hide();
            $('.vat_number').show();
            $('.vat_flat_rate').hide();
            $('.vat_tax_codes').show();
        }
        
        if(tax_system === 'vat_flat') {
            $('.sales_tax_rate').hide();
            $('.vat_number').show();
            $('.vat_flat_rate').show();
            $('.vat_tax_codes').show();
        }
        
        if(tax_system === 'vat_cash') {
            $('.sales_tax_rate').hide();
            $('.vat_number').show();
            $('.vat_flat_rate').hide();
            $('.vat_tax_codes').show();
        }        

    }

</script>

<table width="100%" border="0" cellpadding="20" cellspacing="0">
    <tr>
        <td>
            <table width="700" cellpadding="5" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Company Details{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}COMPANY_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}COMPANY_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                            <tr>
                                <td width="100%" valign="top">                        
                                    <form method="post" action="index.php?component=setup&page_tpl=install" enctype="multipart/form-data">
                                        <table class="menutable" width="100%" border="0" cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td class="menutd">
                                                    <table width="100%" cellpadding="2" cellspacing="2" border="0" class="menutd2">
                                                        <tr>
                                                            <td>
                                                                <table class="olotable" width="100%" cellpadding="5" cellspacing="0" border="0">                                                        

                                                                    <!-- Details -->

                                                                    <tr class="row2">
                                                                        <td class="menuhead" width="100%">&nbsp;{t}Details{/t}</td>                                                                        
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td>
                                                                            <p>&nbsp;</p>                                                                
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td align="left">
                                                                            <table>
                                                                                <tbody align="left">
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Company Name{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="company_name" class="olotd5" value="{$company_details.company_name}" type="text" maxlength="50" required onkeydown="return onlyName(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Logo{/t}:</b></td>
                                                                                        <td>
                                                                                            <input name="logo" type="file" accept=".png, .jpg, .jpeg, .gif">
                                                                                            {if $company_details.logo}
                                                                                                <img src="{$company_logo}" height="50px" alt="{t}Company Logo{/t}">
                                                                                            {else}
                                                                                                {t}No company logo has been set!{/t}
                                                                                            {/if}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"></td>
                                                                                        <td>
                                                                                            <input type="checkbox" name="delete_logo" value="1">{t}Delete Logo{/t}
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Address{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" required onkeydown="return onlyAddress(event);">{$company_details.address}</textarea></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}City{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="city" class="olotd5" value="{$company_details.city}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}State{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="state" class="olotd5" value="{$company_details.state}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Zip{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="zip" class="olotd5" value="{$company_details.zip}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Country{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="country" class="olotd5" value="{$company_details.country}" type="text" maxlength="50" required onkeydown="return onlyAlpha(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Primary Phone{/t}:</b></td>
                                                                                        <td><input name="primary_phone" class="olotd5" value="{$company_details.primary_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Mobile Phone{/t}:</b></td>
                                                                                        <td><input name="mobile_phone" class="olotd5" value="{$company_details.mobile_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Fax{/t}:</b></td>
                                                                                        <td><input name="fax" class="olotd5" value="{$company_details.fax}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Email{/t}:</b></td>                                                                
                                                                                        <td><input name="email" class="olotd5" value="{$company_details.email}" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"/></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Website{/t}:</b></td>                                                                
                                                                                        <td><input name="website" class="olotd5" value="{$company_details.website}" size="50" type="text" maxlength="50" placeholder="https://quantumwarp.com/" pattern="{literal}^(https?:\/\/)?([a-z0-9]+\.)*([a-z0-9]+\.[a-z]+)(\/[a-zA-Z0-9#]+\/?)*${/literal}" onkeydown="return onlyURL(event);"/></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Company Number{/t}:</b></td>
                                                                                        <td><input name="company_number" class="olotd5" value="{$company_details.company_number}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                                                    </tr>                                                                                
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Tax System{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                                        <td>
                                                                                            <select class="olotd5" id="tax_system" name="tax_system">               
                                                                                                {section name=s loop=$tax_systems}
                                                                                                    <option value="{$tax_systems[s].type_key}"{if $company_details.tax_system == $tax_systems[s].type_key} selected{/if}>{t}{$tax_systems[s].display_name}{/t}</option>
                                                                                                {/section}
                                                                                            </select>
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr style="display: none;" class="sales_tax_rate">
                                                                                        <td align="right"><b>{t}Sales Tax Rate{/t}:</b></td>
                                                                                        <td><input id="sales_tax_rate" name="sales_tax_rate" class="olotd5" size="6" value="{$company_details.sales_tax_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);"/>%</td>
                                                                                    </tr>                                                                    
                                                                                    <tr class="vat_number">
                                                                                        <td align="right"><b>{t}VAT Number{/t}:</b></td>
                                                                                        <td><input name="vat_number" class="olotd5" value="{$company_details.vat_number}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                                                    </tr>
                                                                                    <tr class="vat_flat_rate">
                                                                                        <td align="right"><b>{t}VAT Flat Rate{/t}:</b></td>
                                                                                        <td><input name="vat_flat_rate" class="olotd5" value="{$company_details.vat_flat_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);">%</td>
                                                                                    </tr>
                                                                                    <tr class="vat_tax_codes">
                                                                                        <td align="right"><b>{t}VAT Tax Codes{/t}</b></td>
                                                                                        <td>&nbsp;</td>
                                                                                    </tr>
                                                                                    {section name=r loop=$vat_tax_codes}
                                                                                        <tr class="vat_tax_codes">
                                                                                            <td align="right"><b>{t}{$vat_tax_codes[r].display_name}{/t}:</b></td>
                                                                                            <td>
                                                                                                <input name="vat_tax_codes[{$vat_tax_codes[r].tax_key}]" class="olotd5" size="6" value="{$vat_tax_codes[r].rate}" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);" {if !$vat_tax_codes[r].editable} disabled{/if}/>%
                                                                                            </td>
                                                                                        </tr>
                                                                                    {/section}
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Financial Year Start{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td>
                                                                                            <input id="year_start" name="year_start" class="olotd4" size="10" value="{$company_details.year_start|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);">
                                                                                            <button type="button" id="year_start_button">+</button>
                                                                                            <script>                                                        
                                                                                                Calendar.setup( {
                                                                                                    trigger     : "year_start_button",
                                                                                                    inputField  : "year_start",
                                                                                                    dateFormat  : "{$date_format}"                                                                                            
                                                                                                } );                                                        
                                                                                            </script>                                                    
                                                                                        </td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Financial Year End{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td>
                                                                                            <input id="year_end" name="year_end" class="olotd4" size="10" value="{$company_details.year_end|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required onkeydown="return onlyDate(event);">
                                                                                            <button type="button" id="year_end_button">+</button>
                                                                                            <script>                                                        
                                                                                                Calendar.setup( {
                                                                                                    trigger     : "year_end_button",
                                                                                                    inputField  : "year_end",
                                                                                                    dateFormat  : "{$date_format}"                                                                                            
                                                                                                } );                                                        
                                                                                            </script>                                                    
                                                                                        </td>
                                                                                    </tr>                                                                                    
                                                                                    <tr>
                                                                                        <td>
                                                                                            <input type="hidden" name="welcome_msg" value="{$company_details.welcome_msg}">                                                                                                                                                                       
                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- Localisation -->
                                                                    
                                                                    <tr>
                                                                        <td>
                                                                            <p>&nbsp;</p>                                                                
                                                                        </td>
                                                                    </tr>

                                                                    <tr class="row2">
                                                                        <td class="menuhead" width="100%">&nbsp;{t}Localisation{/t}</td>
                                                                    </tr>
                                                                    
                                                                    <tr>
                                                                        <td>
                                                                            <p>&nbsp;</p>                                                                
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td align="left">
                                                                            <table>
                                                                                <tbody align="left">
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Currency Symbol{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="currency_symbol" class="olotd5" size="3" value="{$company_details.currency_symbol}" type="text" maxlength="1" placeholder="&pound;" required onkeydown="return onlyCurrencySymbol(event);"></td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Currency Code{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td><input name="currency_code" class="olotd5" size="5" value="{$company_details.currency_code}" type="text" maxlength="3" placeholder="GBP" required onkeydown="return onlyAlpha(event);">{t}eg: British Pound = GBP, Euro = EUR, US Dollars = USD, Australian Dollars = AUD{/t}</td>
                                                                                    </tr>
                                                                                    <tr>
                                                                                        <td align="right"><b>{t}Date Format{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                        <td>
                                                                                            <select name="date_format" class="olotd5"> 
                                                                                                {section name=d loop=$date_formats}    
                                                                                                    <option value="{$date_formats[d].date_format_key}"{if $company_details.date_format == $date_formats[d].date_format_key} selected{/if}>{t}{$date_formats[d].display_name}{/t}</option>
                                                                                                {/section}    
                                                                                            </select> 
                                                                                        </td>
                                                                                    </tr> 
                                                                                </tbody>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- Email Messages -->                                                                   
                                                                    
                                                                    <!-- Update Button -->
                                                                    
                                                                    <tr>
                                                                        <td>
                                                                            <p>&nbsp;</p>                                                                
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td colspan="2">
                                                                            <span style="color: #ff0000">*</span> {t}Mandatory{/t}
                                                                        </td>
                                                                    </tr>

                                                                    <tr>
                                                                        <td>
                                                                            <button class="olotd5" type="submit" name="submit" value="company_details">{t}Next{/t}</button>
                                                                        </td>
                                                                    </tr>

                                                                </table>                                                                
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