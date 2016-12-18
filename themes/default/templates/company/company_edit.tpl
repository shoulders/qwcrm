<!-- company_edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<form method="POST" action="?page=company:company_edit" enctype="multipart/form-data">
    <table width="100%" border="0" cellpadding="20" cellspacing="0">
        <tr>
            <td>
                <div id="tabs_container">
                    <ul class="tabs">
                        <li class="active"><a href="#" rel="#tab_1_contents" class="tab"><img src="{$theme_images_dir}icons/key.png" alt="" border="0" height="14" width="14"/>&nbsp;Company Details</a></li>
                        <li><a href="#" rel="#tab_2_contents" class="tab"><img src="{$theme_images_dir}icons/money.png" alt="" border="0" height="14" width="14"/>&nbsp;Localisation Setup</a></li>
                        <li><a href="#" rel="#tab_3_contents" class="tab"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />&nbsp;Email Setup</a></li>
                        <li><a href="#" rel="#tab_4_contents" class="tab"><img src="{$theme_images_dir}icons/16x16/email.jpg" alt="" border="0" height="14" width="14" />&nbsp;Email Messages</a></li>
                    </ul>

                    <!-- This is used so the contents don't appear to the right of the tabs -->
                    <div class="clear"></div>

                    <!-- This is a div that hold all the tabbed contents -->
                    <div class="tab_contents_container">

                        <!-- Tab 1 Contents -->
                        <div id="tab_1_contents" class="tab_contents tab_contents_active">                    
                            <table width="100%" cellpadding="5" cellspacing="0" border="0">
                                <tr>
                                    <td class="menuhead2" width="80%">&nbsp;Edit The Company Information</td>
                                </tr>
                                <tr>
                                    <td class="menutd2">
                                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                            <tr>
                                                <td width="100%" valign="top">
                                                    {section name=q loop=$company}
                                                        <table cellpadding="5" cellspacing="0">
                                                            <tr>
                                                                <td align="right"><b>Company Name:</b></td>
                                                                <td><input name="company_name" class="olotd5" value="{$company[q].COMPANY_NAME}" type="text" maxlength="50" required onkeydown="return onlyAlpha(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Logo Filename:</b></td>
                                                                <td>
                                                                    <input type="file" name="file" id="file" />
                                                                    <img src="{$company[q].COMPANY_LOGO}" height="50px" alt="{$company[q].COMPANY_LOGO}"><br><a>Current Logo Path : {$company[q].COMPANY_LOGO}</a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Company ABN:</b></td>
                                                                <td><input name="company_abn" class="olotd5" value="{$company[q].COMPANY_ABN}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Address:</b></td>
                                                                <td><textarea name="address" class="olotd5 mceNoEditor" cols="30" rows="3" maxlength="100" required onkeydown="return onlyAddress(event);">{$company[q].COMPANY_ADDRESS}</textarea></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>City:</b></td>
                                                                <td><input name="city" class="olotd5" value="{$company[q].COMPANY_CITY}" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>State:</b></td>
                                                                <td><input name="state" class="olotd5" type="text" maxlength="20" required onkeydown="return onlyAlpha(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Zip:</b></td>
                                                                <td><input name="zip" class="olotd5" value="{$company[q].COMPANY_ZIP}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Country</b></td>
                                                                <td>
                                                                    <select name="country" class="olotd5">
                                                                        {section name=c loop=$country}
                                                                            <option value="{$country[c].code}" {if $company[q].COMPANY_COUNTRY == $country[c].code} selected {/if}>{$country[c].name}</option>
                                                                        {/section}
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Phone:</b></td>
                                                                <td><input name="phone" class="olotd5" value="{$company[q].COMPANY_PHONE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Mobile Phone:</b></td>
                                                                <td><input name="mobile_phone" class="olotd5" value="{$company[q].COMPANY_MOBILE}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Fax:</b></td>
                                                                <td><input name="fax" class="olotd5" value="{$company[q].COMPANY_FAX}" type="tel" maxlength="20" onkeydown="return onlyPhoneNumber(event);"></td>
                                                            </tr>
                                                        {/section}
                                                        {section name=w loop=$setup}
                                                            <tr>
                                                                <td align="right"><b>Tax Amount:</b></td>
                                                                <td><input name="inv_tax" class="olotd5" size="6" value="{$setup[w].INVOICE_TAX}" maxlength="5" pattern="{literal}\d{0,2}(\.\d{0,2})?{/literal}" required onkeydown="return onlyNumbersPeriod(event);"/>%</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Invoice Starting Number:</b></td>
                                                                <td><input class="olotd5" type="text" name="inv_number" value="{$setup[w].INVOICE_NUMBER_START}"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3" align="center"><b>eg: 2000 - this will start invoice increments from 2000 onwards</b><br></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Work Order Starting Number:</b></td>
                                                                <td><input class="olotd5" type="text" name="wo_number" value="{$setup[w].WO_NUMBER_START}"></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="3" align="center"><b>eg: 259 - this will start work order increments from 259 onwards</b><br></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><b>Invoice Thank You Note:</b><br>255 max characters. Displays at the bottom of each invoice.
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="inv_thank_you">{$setup[w].INV_THANK_YOU}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><b>Company Welcome Note</b><br>(home page)</td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><textarea class="olotd5" cols="80" rows="5" name="welcome">{$setup[w].WELCOME_NOTE}</textarea>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"><input class="olotd5" type="submit" name="submit" value="Update"></td>
                                                            </tr>
                                                        {/section}
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
                                    <td class="menuhead2" width="80%">&nbsp;Edit your Companies Currency Settings</td>
                                </tr>
                                <tr>
                                    <td class="menutd2">
                                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0">
                                            <tr>
                                                <td width="100%" valign="top">                                     
                                                    <table cellpadding="5" cellspacing="0">
                                                        {section name=q loop=$company}
                                                            <tr>
                                                                <td align="right"><b>Currency Symbol:</b></td>
                                                                <td><input name="currency_sym" class="olotd5" size="3" value="{$company[q].COMPANY_CURRENCY_SYMBOL}" type="text" maxlength="1" required onkeydown="return onlyCurrencySymbol(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Currency Code:</b></td>
                                                                <td><input name="currency_code" class="olotd5" size="5" value="{$company[q].COMPANY_CURRENCY_CODE}" type="text" maxlength="3" required onkeydown="return onlyAlpha(event);">eg: "USD" = US Dollars, "AUD" = Australian Dollars, "GBP" = British Pound.</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Date Formatting:</b></td>
                                                                <td>
                                                                    <select name="date_format" class="olotd5">
                                                                        <option value="%d/%m/%Y"{if $company[q].COMPANY_DATE_FORMAT == '%d/%m/%Y'} selected{/if}>dd/mm/yyyy</option>                                                            
                                                                        <option value="%m/%d/%Y"{if $company[q].COMPANY_DATE_FORMAT == '%m/%d/%Y'} selected{/if}>mm/dd/yyyy</option>
                                                                        <option value="%d/%m/%y"{if $company[q].COMPANY_DATE_FORMAT == '%d/%m/%y'} selected{/if}>dd/mm/yy</option>
                                                                        <option value="%m/%d/%y"{if $company[q].COMPANY_DATE_FORMAT == '%m/%d/%y'} selected{/if}>mm/dd/yy</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                        {/section} 
                                                        {section name=w loop=$setup}
                                                            <tr>
                                                                <td colspan="2"><input class="olotd5" type="submit" name="submit" value="Update"></td>
                                                            </tr>
                                                        {/section}
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
                                    <td class="menuhead2" width="80%">&nbsp;Edit your Companies Email Settings</td>
                                </tr>
                                <tr>
                                    <td class="menutd2">
                                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                            <tr>
                                                <td width="100%" valign="top">                                                    
                                                    <table  cellpadding="5" cellspacing="0">
                                                        {section name=q loop=$company}
                                                            <tr>
                                                                <td align="right"><b>Default No-Reply Email:</b></td>
                                                                <td><input name="email_from" class="olotd5" size="50" value="{$company[q].COMPANY_EMAIL_FROM}" type="email" onkeydown="return onlyEmail(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Email SMTP Server:</b></td>
                                                                <td><input name="email_server" class="olotd5" size="50" value="{$company[q].COMPANY_EMAIL_SERVER}" type="url" onkeydown="return onlyURL(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Email Port:</b></td>
                                                                <td><input name="email_port" class="olotd5" size="5" value="{$company[q].COMPANY_EMAIL_PORT}" type="text" onkeydown="return onlyNumbers(event);"></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>Connection Type:</b></td>
                                                                <td>
                                                                    <select class="olotd5" name="conn_type">
                                                                        <option value="SSL" {if $company[q].COMPANY_EMAIL_CONNECTION_TYPE == 'SSL' } selected{/if}>SSL</option>
                                                                        <option value="" {if $company[q].COMPANY_EMAIL_CONNECTION_TYPE != 'SSL' } selected{/if}>None</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>SMTP Login Name:</b></td>
                                                                <td><input name="email_login" class="olotd5" size="50" value="{$company[q].COMPANY_SMTP_USERNAME}" type="text" maxlength="50" onkeydown="return onlyUsername(event);"><font color="RED">*</font>Only required if authentication is needed</td>
                                                            </tr>
                                                            <tr>
                                                                <td align="right"><b>SMTP Server Password:</b></td>
                                                                <td><input name="email_password" class="olotd5" size="50" value="{$company[q].COMPANY_SMTP_PASSWORD}" type="password" onkeydown="return onlyPassword(event);"><font color="RED">*</font>Only required if authentication is needed</td>
                                                            </tr>
                                                        {/section}
                                                        {section name=w loop=$setup}
                                                            <tr>
                                                                 <td colspan="2"><input class="olotd5" type="submit" name="submit" value="Update"></td>
                                                            </tr>
                                                        {/section}
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
                                    <td class="menuhead2" width="80%">&nbsp;Edit Email Messaging functions</td>
                                </tr>
                                <tr>
                                    <td class="menutd2">
                                        <table width="100%" class="olotable" cellpadding="5" cellspacing="0" border="0" >
                                            <tr>
                                                <td width="100%" valign="top" >
                                                    {section name=e loop=$setup}
                                                        <table  cellpadding="5" cellspacing="0">
                                                            <tr>
                                                                <td align="left"><b>New Invoice Message:</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left"><b>Enabled:</b>
                                                                    <select id="new_invoice_enabled">
                                                                        <option value="1" {if $setup[e].EMAIL_MSG_NEW_INVOICE_ACTIVE == '1'} selected{/if}>Yes</option>
                                                                        <option value="0" {if $setup[e].EMAIL_MSG_NEW_INVOICE_ACTIVE == '0'} selected{/if}>No</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><textarea cols="80" rows="15" class="olotd5" name="new_invoice" >{$setup[e].EMAIL_MSG_NEW_INVOICE}</textarea></td>
                                                            </tr>                                                            
                                                                <tr>
                                                                    <td colspan="2"> <input class="olotd5" type="submit" name="submit" value="Update"></td>
                                                                </tr>                                                            
                                                        </table>
                                                        <table  cellpadding="5" cellspacing="0">
                                                            <tr>
                                                                <td align="left"><b>New Work Order Created Message:</b></td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left"><b>Enabled:</b>
                                                                    <select id="new_wo_enabled">
                                                                        <option value="1" {if $setup[e].EMAIL_MSG_WO_CREATED_ACTIVE == '1'} selected{/if}>Yes</option>
                                                                        <option value="0" {if $setup[e].EMAIL_MSG_WO_CREATED_ACTIVE == '0'} selected{/if}>No</option>
                                                                    </select>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td><textarea cols="80" rows="15" class="olotd5" name="new_wo" >{$setup[e].EMAIL_MSG_WO_CREATED}</textarea></td>
                                                            </tr>                                                            
                                                                <tr>
                                                                    <td colspan="2"> <input class="olotd5" type="submit" name="submit" value="Update"></td>
                                                                </tr>                                                            
                                                        </table>
                                                    {/section}
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