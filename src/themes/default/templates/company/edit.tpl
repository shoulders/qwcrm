<!-- edit.tpl -->
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
<script>{include file="`$theme_js_dir_finc`jscal2/language.js"}</script>
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script>{include file="`$theme_js_dir_finc`editor-config.js"}</script>
<script>

    $(document).ready(function() {

        // Display the correct tax boxes for page load
        taxSystemChange();
        
        // Bind an action to the VAT Tax Code dropdown to update the totals on change
        $('#tax_system').change(function() {            
            taxSystemChange();
            alert('{t}Are you sure you want to change the tax system?{/t}');
        } );

    } );
    
    // When user changes Tax system, alter the options
    function taxSystemChange() { 
    
        var tax_system = document.getElementById('tax_system').value;  
        
        if(tax_system === 'no_tax') {        
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
        
        if(tax_system === 'vat_cash') {
            $('.sales_tax_rate').hide();
            $('.vat_number').show();
            $('.vat_flat_rate').hide();
            $('.vat_tax_codes').show();
        }
        
        if(tax_system === 'vat_flat_basic') {
            $('.sales_tax_rate').hide();
            $('.vat_number').show();
            $('.vat_flat_rate').show();
            $('.vat_tax_codes').show();
        }
        
        if(tax_system === 'vat_flat_cash') {
            $('.sales_tax_rate').hide();
            $('.vat_number').show();
            $('.vat_flat_rate').show();
            $('.vat_tax_codes').show();
        }                

    }

</script>

<table width="100%" border="0" cellpadding="20" cellspacing="5">
    <tr>
        <td>
            <table width="700" cellpadding="4" cellspacing="0" border="0" >
                <tr>
                    <td class="menuhead2" width="80%">&nbsp;{t}Company Options{/t}</td>
                    <td class="menuhead2" width="20%" align="right" valign="middle">
                        <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}COMPANY_EDIT_HELP_TITLE{/t}</strong></div><hr><div>{t escape=js}COMPANY_EDIT_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                    </td>
                </tr>
                <tr>
                    <td class="menutd2" colspan="2">
                        <form method="post" action="index.php?component=company&page_tpl=edit" enctype="multipart/form-data">
                            <table width="100%" border="0" cellpadding="20" cellspacing="0">
                                <tr>
                                    <td>
                                        <div id="tabs_container">
                                            <ul class="tabs">
                                                <li class="active"><a href="javascript:void(0)" rel="#tab_1_contents" class="tab"><img src="{$theme_images_dir}icons/key.png" alt="" border="0" height="14" width="14"/>&nbsp;{t}Company Details{/t}</a></li>
                                                <li><a href="javascript:void(0)" rel="#tab_2_contents" class="tab"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14"/>&nbsp;{t}Financial Settings{/t}</a></li>                        
                                                <li><a href="javascript:void(0)" rel="#tab_3_contents" class="tab"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14"/>&nbsp;{t}Localisation Setup{/t}</a></li>                        
                                                <li><a href="javascript:void(0)" rel="#tab_4_contents" class="tab"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />&nbsp;{t}Email Messages{/t}</a></li>
                                            </ul>

                                            <!-- This is used so the contents don't appear to the right of the tabs -->
                                            <div class="clear"></div>

                                            <!-- This is a div that hold all the tabbed contents -->
                                            <div class="tab_contents_container">

                                                <!-- Tab 1 Contents -->
                                                <div id="tab_1_contents" class="tab_contents tab_contents_active">                    
                                                    <table width="100%" cellpadding="5" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="menuhead2" width="80%">&nbsp;{t}Company Options{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menutd2">
                                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td width="100%" valign="top">

                                                                            <table cellpadding="5" cellspacing="0">
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Name{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                    <td><input name="qform[company_name]" class="olotd5" value="{$company_details.company_name}" type="text" maxlength="50" required onkeydown="return onlyName(event);"></td>
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
                                                                                        <input type="checkbox" name="qform[delete_logo]" value="1">{t}Delete Logo{/t}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Address{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                    <td><textarea name="qform[address]" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" required onkeydown="return onlyAddress(event);">{$company_details.address}</textarea></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}City{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                    <td><input name="qform[city]" class="olotd5" value="{$company_details.city}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}State{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                    <td><input name="qform[state]" class="olotd5" value="{$company_details.state}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Zip{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                    <td><input name="qform[zip]" class="olotd5" value="{$company_details.zip}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Country{/t}:</b></td>
                                                                                    <td><input name="qform[country]" class="olotd5" value="{$company_details.country}" type="text" maxlength="50" onkeydown="return onlyAlpha(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Primary Phone{/t}:</b></td>
                                                                                    <td><input name="qform[primary_phone]" class="olotd5" value="{$company_details.primary_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Mobile Phone{/t}:</b></td>
                                                                                    <td><input name="qform[mobile_phone]" class="olotd5" value="{$company_details.mobile_phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Fax{/t}:</b></td>
                                                                                    <td><input name="qform[fax]" class="olotd5" value="{$company_details.fax}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Email{/t}:</b></td>                                                                
                                                                                    <td><input name="qform[email]" class="olotd5" value="{$company_details.email}" size="50" type="email" maxlength="50" placeholder="no-reply@quantumwarp.com" onkeydown="return onlyEmail(event);"/></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Website{/t}:</b></td>                                                                
                                                                                    <td><input name="qform[website]" class="olotd5" value="{$company_details.website}" size="50" type="text" maxlength="50" placeholder="https://quantumwarp.com/" pattern="{literal}^(https?:\/\/)?([a-z0-9_\-]+\.?)+(\/([a-zA-Z0-9_\-~#]+)*\/?)?{/literal}" onkeydown="return onlyURL(event);"/></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Company Number{/t}:</b></td>
                                                                                    <td><input name="qform[company_number]" class="olotd5" value="{$company_details.company_number}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                                                </tr>                                                                                
                                                                                <tr>
                                                                                    <td><b>{t}Welcome Message{/t}:</b> {t}(Dashboard){/t}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td colspan="2">
                                                                                        <textarea class="olotd5" cols="80" rows="5" name="qform[welcome_msg]">{$company_details.welcome_msg}</textarea>
                                                                                        <p>* {t}If there is no welcome message, the message box will not be displayed on the dashboard.{/t}</p>
                                                                                    </td>
                                                                                </tr>                                                                                                           
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                                                                
                                                <!-- Tab 2 Contents -->
                                                <div id="tab_2_contents" class="tab_contents">
                                                    <table width="100%" cellpadding="5" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="menuhead2" width="80%">&nbsp;{t}Edit your Companies Finance Settings{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menutd2">
                                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">                                                                    
                                                                    <tr>
                                                                        <td align="right"><b>{t}Financial Year Start{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                        <td>
                                                                            <input id="year_start" name="qform[year_start]" class="olotd4" size="10" value="{$company_details.year_start|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
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
                                                                            <input id="year_end" name="qform[year_end]" class="olotd4" size="10" value="{$company_details.year_end|date_format:$date_format}" type="text" maxlength="10" pattern="{literal}^[0-9]{2,4}(?:\/|-)[0-9]{2}(?:\/|-)[0-9]{2,4}${/literal}" required readonly onkeydown="return onlyDate(event);">
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
                                                                        <td align="right"><b>{t}Tax System{/t}</b><span style="color: #ff0000"> *</span></td>
                                                                        <td>
                                                                            <select class="olotd5" id="tax_system" name="qform[tax_system]">               
                                                                                {section name=s loop=$tax_systems}
                                                                                    <option value="{$tax_systems[s].type_key}"{if $company_details.tax_system == $tax_systems[s].type_key} selected{/if}>{t}{$tax_systems[s].display_name}{/t}</option>
                                                                                {/section}
                                                                            </select>
                                                                        </td>
                                                                    </tr>
                                                                    <tr style="display: none;" class="sales_tax_rate">
                                                                        <td align="right"><b>{t}Sales Tax Rate{/t}:</b></td>
                                                                        <td><input id="sales_tax_rate" name="qform[sales_tax_rate]" class="olotd5" size="6" value="{$company_details.sales_tax_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);"/>%</td>
                                                                    </tr>                                                                    
                                                                    <tr class="vat_number">
                                                                        <td align="right"><b>{t}VAT Number{/t}:</b></td>
                                                                        <td><input name="qform[vat_number]" class="olotd5" value="{$company_details.vat_number}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                                    </tr>
                                                                    <tr class="vat_flat_rate">
                                                                        <td align="right"><b>{t}VAT Flat Rate{/t}:</b></td>
                                                                        <td><input name="qform[vat_flat_rate]" class="olotd5" value="{$company_details.vat_flat_rate}" type="text" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);">%</td>
                                                                    </tr>
                                                                    <tr class="vat_tax_codes">
                                                                        <td align="right"><b>{t}VAT Tax Codes{/t}</b></td>
                                                                        <td>&nbsp;</td>
                                                                    </tr>
                                                                    {section name=r loop=$vat_tax_codes}
                                                                        <tr class="vat_tax_codes">
                                                                            <td align="right"><b>{t}{$vat_tax_codes[r].display_name}{/t} ({$vat_tax_codes[r].tax_key}):</b></td>
                                                                            <td>
                                                                                <input name="qform[vat_tax_codes][{$vat_tax_codes[r].tax_key}]" class="olotd5" size="6" value="{$vat_tax_codes[r].rate}" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" onkeydown="return onlyNumberPeriod(event);" {if !$vat_tax_codes[r].editable} disabled{/if}/>%
                                                                            </td>
                                                                        </tr>
                                                                    {/section}
                                                                    <tr>
                                                                        <td align="right"><b>{t}Credit Note Expiry Offset{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                        <td>
                                                                            <input name="qform[creditnote_expiry_offset]" class="olotd5" value="{$company_details.creditnote_expiry_offset}" type="text" maxlength="5" required onkeydown="return onlyNumber(event);">
                                                                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Credit Note Expiry Offset{/t}</strong></div><hr><div>{t escape=js}This is the number of days added to todays date to generate the credit notes default expiry date. This date can be changed.{/t}</div>');" onMouseOut="hideddrivetip();">
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="right"><b>{t}Voucher Expiry Offset{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                        <td>
                                                                            <input name="qform[voucher_expiry_offset]" class="olotd5" value="{$company_details.voucher_expiry_offset}" type="text" maxlength="5" required onkeydown="return onlyNumber(event);">
                                                                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=js}Voucher Expiry Offset{/t}</strong></div><hr><div>{t escape=js}This is the number of days added to todays date to generate the vouchers default expiry date. This date can be changed.{/t}<br><br>In the UK/EU, Gift vouchers must have no expiry date or be valid for at least 5 years.</div>');" onMouseOut="hideddrivetip();">
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                                                                                 
                                                    </table>
                                                </div>

                                                <!-- Tab 3 Contents -->
                                                <div id="tab_3_contents" class="tab_contents">
                                                    <table width="100%" cellpadding="5" cellspacing="0" border="0">
                                                        <tr>
                                                            <td class="menuhead2" width="80%">&nbsp;{t}Edit your Companies Currency Settings{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="menutd2">
                                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                                                    <tr>
                                                                        <td width="100%" valign="top">                                     
                                                                            <table cellpadding="5" cellspacing="0">                                                        
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Currency Symbol{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                    <td><input name="qform[currency_symbol]" class="olotd5" size="3" value="{$company_details.currency_symbol}" type="text" maxlength="1" placeholder="&pound;" required onkeydown="return onlyCurrencySymbol(event);"></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Currency Code{/t}:</b> <span style="color: #ff0000">*</span></td>
                                                                                    <td><input name="qform[currency_code]" class="olotd5" size="5" value="{$company_details.currency_code}" type="text" maxlength="3" placeholder="GBP" required onkeydown="return onlyAlpha(event);">{t}eg: British Pound = GBP, Euro = EUR, US Dollars = USD, Australian Dollars = AUD{/t}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="right"><b>{t}Date Format{/t}:</b></td>
                                                                                    <td>
                                                                                        <select name="qform[date_format]" class="olotd5"> 
                                                                                            {section name=d loop=$date_formats}    
                                                                                                <option value="{$date_formats[d].date_format_key}"{if $company_details.date_format == $date_formats[d].date_format_key} selected{/if}>{t}{$date_formats[d].display_name}{/t}</option>
                                                                                            {/section}    
                                                                                        </select> 
                                                                                    </td>
                                                                                </tr>                                                        
                                                                            </table>                                                                                               
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>

                                                <!-- Tab 4 Contents -->                        
                                                <div id="tab_4_contents" class="tab_contents">
                                                    <table width="100%" cellpadding="5" cellspacing="0" border="0">                                
                                                        <tr>
                                                            <td class="menutd2">                                        
                                                                <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">                                            
                                                                    <tr>
                                                                        <td class="menuhead2" width="80%">&nbsp;{t}Edit Email Messaging functions{/t}</td>
                                                                    </tr>

                                                                    <!-- Email Signature -->

                                                                    <tr>
                                                                        <td>
                                                                            <table cellpadding="5" cellspacing="0">
                                                                                <tr>
                                                                                    <td class="menuhead">{t}Email Signature{/t}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>                                                                
                                                                                        <strong>Placeholders</strong><br>                                                                                        
                                                                                        {literal}{company_logo}{/literal} = {t}Company logo{/t}<br>                                                                                    
                                                                                        {literal}{company_name}{/literal} = {t}Company name{/t}<br>
                                                                                        {literal}{company_address}{/literal} = {t}Company address{/t}<br>
                                                                                        {literal}{company_telephone}{/literal} = {t}Company telephone{/t}<br>
                                                                                        {literal}{company_website}{/literal} = {t}Company website{/t}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><textarea cols="80" rows="15" class="olotd5" name="qform[email_signature]">{$company_details.email_signature}</textarea></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left"><b>{t}Enabled{/t}:</b>
                                                                                        <select name="qform[email_signature_active]">                                                                    
                                                                                            <option value="1" {if $company_details.email_signature_active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                                            <option value="0" {if $company_details.email_signature_active == '0'} selected{/if}>{t}No{/t}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>                                                        
                                                                        </td>
                                                                    </tr>  

                                                                    <!-- Workorder -->

                                                                    <tr>
                                                                        <td>
                                                                            <input type="hidden" name="qform[email_msg_workorder]" value="">
                                                                            {*<table cellpadding="5" cellspacing="0">
                                                                                <tr>
                                                                                    <td class="menuhead">{t}Workorder Email Message{/t}:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>                                                                
                                                                                        <strong>Placeholders</strong><br>
                                                                                        {literal}{client_display_name}{/literal} = {t}Company or Client's name (automatic){/t}<br>
                                                                                        {literal}{client_first_name}{/literal} = {t}Client's/Contact's first name{/t}<br> 
                                                                                        {literal}{client_last_name}{/literal} = {t}Client's/Contact's last name{/t}<br> 
                                                                                        {literal}{client_credit_terms}{/literal} = {t}Client's credit terms{/t} 
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><textarea cols="80" rows="15" class="olotd5" name="qform[email_msg_workorder]">{$company_details.email_msg_workorder}</textarea></td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td align="left"><b>{t}Enabled{/t}:</b>
                                                                                        <select name="qform[email_msg_workorder_active]">                                                                    
                                                                                            <option value="1" {if $company_details.email_msg_workorder_active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                                            <option value="0" {if $company_details.email_msg_workorder_active == '0'} selected{/if}>{t}No{/t}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>*}                                                       
                                                                        </td>
                                                                    </tr>                                            

                                                                    <!-- Invoice -->

                                                                    <tr>
                                                                        <td>
                                                                            <table cellpadding="5" cellspacing="0">
                                                                                <tr>
                                                                                    <td class="menuhead">{t}Invoice Email Message{/t}:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>                                                                
                                                                                        <strong>Placeholders</strong><br>
                                                                                        {literal}{company_name}{/literal} = {t}This company's name{/t}<br>
                                                                                        {literal}{client_display_name}{/literal} = {t}Client's company name or contact name (automatic){/t}<br>
                                                                                        {literal}{client_first_name}{/literal} = {t}Client's/Contact's first name{/t}<br> 
                                                                                        {literal}{client_last_name}{/literal} = {t}Client's/Contact's last name{/t}<br> 
                                                                                        {literal}{client_credit_terms}{/literal} = {t}Client's credit terms{/t}
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><textarea cols="80" rows="15" class="olotd5" name="qform[email_msg_invoice]">{$company_details.email_msg_invoice}</textarea></td>
                                                                                </tr>
                                                                                {*<tr>
                                                                                    <td align="left"><b>{t}Enabled{/t}:</b>
                                                                                        <select name="qform[email_msg_invoice_active]">                                                                    
                                                                                            <option value="1" {if $company_details.email_msg_invoice_active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                                            <option value="0" {if $company_details.email_msg_invoice_active == '0'} selected{/if}>{t}No{/t}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>*}
                                                                            </table>                                                        
                                                                        </td>
                                                                    </tr>
                                                                    
                                                                    <!-- Voucher -->

                                                                    <tr>
                                                                        <td>
                                                                            <table cellpadding="5" cellspacing="0">
                                                                                <tr>
                                                                                    <td class="menuhead">{t}Voucher Email Message{/t}:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>                                                                
                                                                                        <strong>Placeholders</strong><br>
                                                                                        {literal}{company_name}{/literal} = {t}This company's name{/t}<br>
                                                                                        {literal}{client_display_name}{/literal} = {t}Client's company name or contact name (automatic){/t}<br>
                                                                                        {literal}{client_first_name}{/literal} = {t}Client's/Contact's first name{/t}<br> 
                                                                                        {literal}{client_last_name}{/literal} = {t}Client's/Contact's last name{/t}<br>                                                                                        
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><textarea cols="80" rows="15" class="olotd5" name="qform[email_msg_voucher]">{$company_details.email_msg_voucher}</textarea></td>
                                                                                </tr>
                                                                                {*<tr>
                                                                                    <td align="left"><b>{t}Enabled{/t}:</b>
                                                                                        <select name="qform[email_msg_voucher_active]">                                                                    
                                                                                            <option value="1" {if $company_details.email_msg_voucher_active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                                            <option value="0" {if $company_details.email_msg_voucher_active == '0'} selected{/if}>{t}No{/t}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>*}
                                                                            </table>                                                        
                                                                        </td>
                                                                    </tr> 
                                                                    
                                                                    <!-- Credit Note -->

                                                                    <tr>
                                                                        <td>
                                                                            <table cellpadding="5" cellspacing="0">
                                                                                <tr>
                                                                                    <td class="menuhead">{t}Credit Note Email Message{/t}:</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td>                                                                
                                                                                        <strong>Placeholders</strong><br>
                                                                                        {literal}{company_name}{/literal} = {t}This company's name{/t}<br>
                                                                                        {literal}{client_display_name}{/literal} = {t}Client's company name or contact name (automatic){/t}<br>
                                                                                        {literal}{client_first_name}{/literal} = {t}Client's/Contact's first name{/t}<br> 
                                                                                        {literal}{client_last_name}{/literal} = {t}Client's/Contact's last name{/t}<br>                                                                                        
                                                                                    </td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td><textarea cols="80" rows="15" class="olotd5" name="qform[email_msg_creditnote]">{$company_details.email_msg_creditnote}</textarea></td>
                                                                                </tr>
                                                                                {*<tr>
                                                                                    <td align="left"><b>{t}Enabled{/t}:</b>
                                                                                        <select name="qform[email_msg_creditnote_active]">                                                                    
                                                                                            <option value="1" {if $company_details.email_msg_creditnote_active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                                            <option value="0" {if $company_details.email_msg_creditnote_active == '0'} selected{/if}>{t}No{/t}</option>
                                                                                        </select>
                                                                                    </td>
                                                                                </tr>*}
                                                                            </table>                                                        
                                                                        </td>
                                                                    </tr> 

                                                                </table>
                                                            </td>
                                                        </tr>                                
                                                    </table>
                                                </div>

                                            </div><!-- EOF of tab_contents_container-->
                                        </div> <!-- EOF of tabs_container -->            
                                    </td>
                                </tr>        

                                <!-- Update Button -->
                                
                                <tr>
                                    <td colspan="2">
                                        <span style="color: #ff0000">*</span> {t}Mandatory{/t}
                                    </td>
                                </tr>

                                <tr>
                                    <td>
                                        <input class="olotd5" type="submit" name="submit" value="Submit">&nbsp;
                                        <button type="button" class="olotd4" onclick="window.location.href='index.php';">{t}Cancel{/t}</button>
                                    </td>
                                </tr>  

                            </table>
                        </form>                                                    
                    </td>
                </tr>
            </table>
        </tr>
    </td>
</table>