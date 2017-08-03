<!-- edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<form method="post" action="index.php?page=company:edit" enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="20" cellspacing="0">
        <tr>
            <td>
                <div id="tabs_container">
                    <ul class="tabs">
                        <li class="active"><a href="javascript: void(0)" rel="#tab_1_contents" class="tab"><img src="{$theme_images_dir}icons/key.png" alt="" border="0" height="14" width="14"/>&nbsp;{t}Company Details{/t}</a></li>
                        <li><a href="javascript: void(0)" rel="#tab_2_contents" class="tab"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14"/>&nbsp;{t}Localisation Setup{/t}</a></li>
                        <li><a href="javascript: void(0)" rel="#tab_3_contents" class="tab"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />&nbsp;{t}Email Setup{/t}</a></li>
                        <li><a href="javascript: void(0)" rel="#tab_4_contents" class="tab"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />&nbsp;{t}Email Messages{/t}</a></li>
                    </ul>

                    <!-- This is used so the contents don't appear to the right of the tabs -->
                    <div class="clear"></div>

                    <!-- This is a div that hold all the tabbed contents -->
                    <div class="tab_contents_container">

                        <!-- Tab 1 Contents -->
                        <div id="tab_1_contents" class="tab_contents tab_contents_active">                    
                            <table width="100%" cellpadding="5" cellspacing="0" border="0">
                                <tr>
                                    <td class="menuhead2" width="80%">&nbsp;{t}Edit The Company Information{/t}</td>
                                </tr>
                                <tr>
                                    <td class="menutd2">
                                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                            <tr>
                                                <td width="100%" valign="top">

                                                    <table cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td align="right"><b>{t}Company Name{/t}:</b></td>
                                                            <td><input name="name" class="olotd5" value="{$company_details.name}" type="text" maxlength="50" required onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Logo{/t}:</b></td>
                                                            <td>
                                                                <input type="file" name="logo">
                                                                <img src="{$company_details.logo}" height="50px" alt="Company Logo"><br>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Company Number{/t}:</b></td>
                                                            <td><input name="company_number" class="olotd5" value="{$company_details.company_number}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Address{/t}:</b></td>
                                                            <td><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" required onkeydown="return onlyAddress(event);">{$company_details.address}</textarea></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}City{/t}:</b></td>
                                                            <td><input name="city" class="olotd5" value="{$company_details.city}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}State{/t}:</b></td>
                                                            <td><input name="state" class="olotd5" value="{$company_details.state}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Zip{/t}:</b></td>
                                                            <td><input name="zip" class="olotd5" value="{$company_details.zip}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Country{/t}:</b></td>
                                                            <td>
                                                                <select name="country" class="olotd5">
                                                                    {section name=c loop=$country}
                                                                        <option value="{$country[c].code}" {if $country[c].code == $company_details.country}selected{/if}>{$country[c].name}</option>
                                                                    {/section}
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Phone{/t}:</b></td>
                                                            <td><input name="phone" class="olotd5" value="{$company_details.phone}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Mobile Phone{/t}:</b></td>
                                                            <td><input name="mobile" class="olotd5" value="{$company_details.mobile}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
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
                                                            <td><input name="website" class="olotd5" value="{$company_details.website}" size="50" type="url" maxlength="50" placeholder="https://quantumwarp.com/" pattern="^https?://.+" onkeydown="return onlyURL(event);"/></td>
                                                        </tr> 
                                                        <tr>
                                                            <td align="right"><b>{t}Tax Rate{/t}:</b></td>
                                                            <td><input name="tax_rate" class="olotd5" size="6" value="{$company_details.tax_rate}" maxlength="5" pattern="{literal}^[0-9]{0,2}(\.[0-9]{0,2})?${/literal}" required onkeydown="return onlyNumbersPeriod(event);"/>%</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>{t}Company Welcome Message{/t}:</b><br>{t}(home page){/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="welcome_msg">{$company_details.welcome_msg}</textarea></td>
                                                        </tr>                                                                                                                      
                                                        <tr>
                                                            <td colspan="2"><input class="olotd5" type="submit" name="submit" value="Update"></td>
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
                                    <td class="menuhead2" width="80%">&nbsp;{t}Edit your Companies Currency Settings{/t}</td>
                                </tr>
                                <tr>
                                    <td class="menutd2">
                                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                            <tr>
                                                <td width="100%" valign="top">                                     
                                                    <table cellpadding="5" cellspacing="0">                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Currency Symbol{/t}:</b></td>
                                                            <td><input name="currency_symbol" class="olotd5" size="3" value="{$company_details.currency_symbol}" type="text" maxlength="1" required onkeydown="return onlyCurrencySymbol(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Currency Code{/t}:</b></td>
                                                            <td><input name="currency_code" class="olotd5" size="5" value="{$company_details.currency_code}" type="text" maxlength="3" required onkeydown="return onlyAlpha(event);">{t}eg: British Pound = GBP, Euro = EUR, US Dollars = USD, Australian Dollars = AUD{/t}</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Date Formatting{/t}:</b></td>
                                                            <td>
                                                                <select name="date_format" class="olotd5">
                                                                    <option value="%d/%m/%Y"{if $company_details.date_format == '%d/%m/%Y'} selected{/if}>dd/mm/yyyy</option>                                                            
                                                                    <option value="%m/%d/%Y"{if $company_details.date_format == '%m/%d/%Y'} selected{/if}>mm/dd/yyyy</option>
                                                                    <option value="%d/%m/%y"{if $company_details.date_format == '%d/%m/%y'} selected{/if}>dd/mm/yy</option>
                                                                    <option value="%m/%d/%y"{if $company_details.date_format == '%m/%d/%y'} selected{/if}>mm/dd/yy</option>
                                                                </select>
                                                            </td>
                                                        </tr>                                                        
                                                        <tr>
                                                            <td colspan="2"><input class="olotd5" type="submit" name="submit" value="Update"></td>
                                                        </tr>                                                        
                                                    </table>                                                                                               
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </div>

                        <!-- Tab 3 Contents -->
                        <div id="tab_3_contents" class="tab_contents">                        
                            <table width="100%" cellpadding="5" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2" width="80%">&nbsp;{t}Edit your Companies Email Settings{/t}</td>
                                </tr>
                                <tr>
                                    <td class="menutd2">
                                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                            <tr>
                                                <td width="100%" valign="top">                                                    
                                                    <table cellpadding="5" cellspacing="0">                                                        
                                                        <tr>
                                                            <td align="right"><b>{t}Default No-Reply Email{/t}:</b></td>
                                                            <td><input name="email_from" class="olotd5" size="50" value="{$company_details.email_from}" type="email" onkeydown="return onlyEmail(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Email SMTP Server{/t}:</b></td>
                                                            <td><input name="email_server" class="olotd5" size="50" value="{$company_details.email_server}" type="url" onkeydown="return onlyURL(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Email Port{/t}:</b></td>
                                                            <td><input name="email_port" class="olotd5" size="5" value="{$company_details.email_port}" type="text" onkeydown="return onlyNumbers(event);"></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}Connection Type{/t}:</b></td>
                                                            <td>
                                                                <select class="olotd5" name="conn_type">
                                                                    <option value="SSL" {if $company_details.email_connection_type == 'SSL' } selected{/if}>SSL</option>
                                                                    <option value="" {if $company_details.email_connection_type != 'SSL' } selected{/if}>{t}None{/t}</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}SMTP Login Name{/t}:</b></td>
                                                            <td><input name="email_login" class="olotd5" size="50" value="{$company_details.smtp_username}" type="text" maxlength="50" onkeydown="return onlyUsername(event);"><font color="RED">*</font>Only required if authentication is needed</td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right"><b>{t}SMTP Server Password{/t}:</b></td>
                                                            <td><input name="email_password" class="olotd5" size="50" value="{$company_details.smtp_password}" type="password" onkeydown="return onlyPassword(event);"><font color="RED">*</font>Only required if authentication is needed</td>
                                                        </tr>                                                        
                                                        <tr>
                                                             <td colspan="2"><input class="olotd5" type="submit" name="submit" value="Update"></td>
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
                            <table width="100%" cellpadding="5" cellspacing="0" border="0" >
                                <tr>
                                    <td class="menuhead2" width="80%">&nbsp;{t}Edit Email Messaging functions{/t}</td>
                                </tr>
                                <tr>
                                    <td class="menutd2">
                                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                            <tr>
                                                <td width="100%" valign="top">                                                    
                                                    <table cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td align="left"><b>{t}New Work Order Created Message{/t}:</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left"><b>{t}Enabled{/t}:</b>
                                                                <select id="new_wo_enabled">
                                                                    <option value="1" {if $company_details.email_msg_wo_created_active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                    <option value="0" {if $company_details.email_msg_wo_created_active == '0'} selected{/if}>{t}No{/t}</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><textarea cols="80" rows="15" class="olotd5" name="new_wo" >{$company_details.email_msg_wo_created}</textarea></td>
                                                        </tr>                                                            
                                                        <tr>
                                                            <td colspan="2"> <input class="olotd5" type="submit" name="submit" value="Update"></td>
                                                        </tr>                                                            
                                                    </table>
                                                    <table cellpadding="5" cellspacing="0">
                                                        <tr>
                                                            <td align="left"><b>{t}New Invoice Message{/t}:</b></td>
                                                        </tr>
                                                        <tr>
                                                            <td align="left"><b>{t}Enabled{/t}:</b>
                                                                <select id="new_invoice_enabled">
                                                                    <option value="1" {if $company_details.email_msg_new_invoice_active == '1'} selected{/if}>{t}Yes{/t}</option>
                                                                    <option value="0" {if $company_details.email_msg_new_invoice_active == '0'} selected{/if}>{t}No{/t}</option>
                                                                </select>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><textarea cols="80" rows="15" class="olotd5" name="new_invoice" >{$company_details.email_msg_new_invoice}</textarea></td>
                                                        </tr>                                                            
                                                        <tr>
                                                            <td colspan="2"> <input class="olotd5" type="submit" name="submit" value="Update"></td>
                                                        </tr>                                                            
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
    </table>
</form>