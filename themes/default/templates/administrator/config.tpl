<!-- edit.tpl -->
<script src="{$theme_js_dir}tinymce/tinymce.min.js"></script>
<script src="{$theme_js_dir}editor-config.js"></script>

<form method="post" action="index.php?page=administrator:config">                   
    <table width="600" cellpadding="5" cellspacing="0" border="0">
        <tr>
            <td class="menuhead2" width="80%">&nbsp;{t}QWcrm Config Settings{/t}</td>
            <td class="menuhead2" width="20%" align="right" valign="middle">  <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}ADMINISTRATOR_CONFIG_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();"></td>
        </tr>
        <tr>
            <td class="menutd2">
                <table width="600" class="olotable" cellpadding="5" cellspacing="0" border="0">
                    
                    <!-- Database --> 
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Database{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Host{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td>
                            <input name="db_host" class="olotd5" value="{$qwcrm_config.db_host}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"/>
                            <img src="{$theme_images_dir}icons/16x16/help.gif" border="0" onMouseOver="ddrivetip('<div><strong>{t escape=tooltip}INVOICE_LABOUR_RATES_HELP_TITLE{/t}</strong></div><hr><div>{t escape=tooltip}INVOICE_LABOUR_RATES_HELP_CONTENT{/t}</div>');" onMouseOut="hideddrivetip();">
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Database Name{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td><input name="db_name" class="olotd5" value="{$qwcrm_config.db_name}" type="text" maxlength="20" required onkeydown="return onlyMysqlDatabaseName(event);"/></td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Database Username{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td><input name="db_user" class="olotd5" value="{$qwcrm_config.db_user}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"/></td>
                    </tr>
                    {* <tr>
                        <td align="right"><b>{t}Database Password{/t}</b></td>
                        <td><input name="db_pass" class="olotd5" value="{$qwcrm_config.db_pass}" type="password" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                    </tr> *}
                    <tr>
                        <td align="right"><b>{t}Database Tables Prefix{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td><input name="db_prefix" class="olotd5" value="{$qwcrm_config.db_prefix}" type="text" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"/></td>
                    </tr>
                    
                    
                    <!-- Other -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Other{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Gzip Page Compression{/t}</b></td>
                        <td>
                            <select class="olotd5" id="gzip" name="gzip">                                                       
                                <option value="0"{if $qwcrm_config.gzip == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.gzip == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Site Maintenance{/t}</b></td>
                        <td>
                            <select class="olotd5" id="maintenance" name="maintenance">                                                       
                                <option value="0"{if $qwcrm_config.maintenance == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.maintenance == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Theme Name{/t}</b></td>
                        <td>
                            {$qwcrm_config.theme_name}
                            <input name="theme_name" class="olotd5" value="{$qwcrm_config.theme_name}" type="hidden" maxlength="20" required onkeydown="return onlyAlphaNumeric(event);"/>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Default Language{/t}</b></td>
                        <td>
                            <select class="olotd5" id="default_language" name="default_language">                                                       
                                <option value="en_GB"{if $qwcrm_config.default_language == 'en_GB'} selected{/if}>{t}English{/t}</option>                                
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Autodetect Language{/t}</b></td>
                        <td>
                            <select class="olotd5" id="autodetect_language" name="autodetect_language">                                                       
                                <option value="0"{if $qwcrm_config.autodetect_language == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.autodetect_language == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    
                    <!-- Security -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Security{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Force SSL/HTTPS{/t}</b></td>
                        <td>
                            <select class="olotd5" id="force_ssl" name="force_ssl">                                                       
                                <option value="0"{if $qwcrm_config.force_ssl == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.force_ssl == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA{/t}</b></td>
                        <td>
                            <select class="olotd5" id="recaptcha" name="recaptcha">                                                       
                                <option value="0"{if $qwcrm_config.recaptcha == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.recaptcha == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA Site Key{/t}</b></td>
                        <td><input name="recaptcha_site_key" class="olotd5" value="{$qwcrm_config.recaptcha_site_key}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}reCAPTCHA Secret Key{/t}</b></td>
                        <td><input name="recaptcha_secret_key" class="olotd5" value="{$qwcrm_config.recaptcha_secret_key}" type="text" maxlength="20" onkeydown="return onlyAlphaNumeric(event);"/></td>
                    </tr>
                    
                    <!-- Session -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Session{/t}</td>
                    </tr>
                    
                    <tr>
                        <td align="right"><b>{t}Session Lifetime{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td><input name="session_lifetime" class="olotd5" value="{$qwcrm_config.session_lifetime}" type="text" maxlength="20" required onkeydown="return onlyNumbers(event);"/></td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}Remember Me{/t}</b></td>
                        <td>
                            <select class="olotd5" id="remember_me" name="remember_me">                                                       
                                <option value="0"{if $qwcrm_config.remember_me == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.remember_me == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Shared Session{/t}</b></td>
                        <td>
                            <select class="olotd5" id="shared_session" name="shared_session">                                                       
                                <option value="0"{if $qwcrm_config.shared_session == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.shared_session == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Session Handler{/t}</b></td>
                        <td>
                            <select class="olotd5" id="session_handler" name="session_handler">                                                       
                                <option value="none"{if $qwcrm_config.session_handler == 'none'} selected{/if}>{t}None{/t}</option>
                                <option value="database"{if $qwcrm_config.session_handler == 'database'} selected{/if}>{t}Database{/t}</option>
                            </select>
                        </td>
                    </tr>
                    
                    <!-- Cookie -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Cookie{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}Cookie Lifetime{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td><input name="cookie_lifetime" class="olotd5" value="{$qwcrm_config.cookie_lifetime}" type="text" maxlength="20" required onkeydown="return onlyNumbers(event);"/></td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Token Length{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td><input name="cookie_token_length" class="olotd5" value="{$qwcrm_config.cookie_token_length}" type="text" maxlength="20" required onkeydown="return onlyNumbers(event);"/></td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Domain{/t}</b></td>
                        <td><input name="cookie_domain" class="olotd5" value="{$qwcrm_config.cookie_domain}" type="text" maxlength="20" onkeydown="return onlyURL(event);"/></td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cookie Path{/t}</b></td>
                        <td><input name="cookie_path" class="olotd5" value="{$qwcrm_config.cookie_path}" type="text" maxlength="20" onkeydown="return onlyURL(event);"/></td>
                    </tr>
                    
                    <!-- Logging -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Logging{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}QWcrm Tracker{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_tracker" name="qwcrm_tracker">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_tracker == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_tracker == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}QWcrm Access Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_access_log" name="qwcrm_access_log">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_access_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_access_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}QWcrm Activity Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_activity_log" name="qwcrm_activity_log">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_activity_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_activity_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}QWcrm Error Log{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_error_log" name="qwcrm_error_log">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_error_log == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_error_log == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr> 
                    
                    <!-- Error Reporting -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Error Reporting{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}Error Reporting{/t}</b></td>
                        <td>
                            <select class="olotd5" id="error_reporting" name="error_reporting">                                
                                <option value="default"{if $qwcrm_config.error_reporting == 'default'} selected{/if}>{t}System Default{/t}</option>
                                <option value="none"{if $qwcrm_config.error_reporting == 'none'} selected{/if}>{t}None{/t}</option>
                                <option value="verysimple"{if $qwcrm_config.error_reporting == 'verysimple'} selected{/if}>{t}Very Simple{/t}</option>
                                <option value="simple"{if $qwcrm_config.error_reporting == 'simple'} selected{/if}>{t}Simple{/t}</option>
                                <option value="maximum"{if $qwcrm_config.error_reporting == 'maximum'} selected{/if}>{t}Maximum{/t}</option>
                                <option value="development"{if $qwcrm_config.error_reporting == 'development'} selected{/if}>{t}Development{/t}</option>                                
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Error Page Raw Output{/t}</b></td>
                        <td>
                            <select class="olotd5" id="error_page_raw_output" name="error_page_raw_output">                                                       
                                <option value="0"{if $qwcrm_config.error_page_raw_output == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.error_page_raw_output == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>                    
                    
                    <!-- Debugging -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Debugging{/t}</td>
                    </tr>
                                        
                    <tr>
                        <td align="right"><b>{t}QWcrm Debug{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_debug" name="qwcrm_debug">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_debug == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_debug == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>                    
                    <tr>
                        <td align="right"><b>{t}QWcrm Advanced Debug{/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_advanced_debug" name="qwcrm_advanced_debug">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_advanced_debug == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_advanced_debug == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Smarty Debugging (QWcrm){/t}</b></td>
                        <td>
                            <select class="olotd5" id="qwcrm_smarty_debugging" name="qwcrm_smarty_debugging">                                                       
                                <option value="0"{if $qwcrm_config.qwcrm_smarty_debugging == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.qwcrm_smarty_debugging == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Smarty Debugging{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_debugging" name="smarty_debugging">                                                       
                                <option value="0"{if $qwcrm_config.smarty_debugging == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_debugging == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    
                    <!-- Smarty -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;{t}Smarty{/t}</td>
                    </tr>                                        
                    
                    <tr>
                        <td align="right"><b>{t}Force Compile{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_force_compile" name="smarty_force_compile">                                                       
                                <option value="0"{if $qwcrm_config.smarty_force_compile == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_force_compile == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Template Caching{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_caching" name="smarty_caching">                                                       
                                <option value="0"{if $qwcrm_config.smarty_caching == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_caching == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cache Lifetime{/t}</b> <span style="color: #ff0000">*</span></td>
                        <td><input name="smarty_cache_lifetime" class="olotd5" value="{$qwcrm_config.smarty_cache_lifetime}" type="text" maxlength="20" required onkeydown="return onlyNumbers(event);"/></td>
                    </tr>
                    <tr>
                        <td align="right"><b>{t}Cache Modified Check{/t}</b></td>
                        <td>
                            <select class="olotd5" id="smarty_cache_modified_check" name="smarty_cache_modified_check">                                                       
                                <option value="0"{if $qwcrm_config.smarty_cache_modified_check == '0'} selected{/if}>{t}No{/t}</option>
                                <option value="1"{if $qwcrm_config.smarty_cache_modified_check == '1'} selected{/if}>{t}Yes{/t}</option>
                            </select>
                        </td>
                    </tr> 
                    
                    <!-- Update -->
                    
                    <tr class="row2">
                        <td class="menuhead" colspan="5" width="100%">&nbsp;</td>
                    </tr> 
                    
                    <tr>
                        <td colspan="2" style="text-align: center;"><button class="olotd5" type="submit" name="submit" value="Update">{t}Update{/t}</td>
                    </tr> 
                    
                </table>
            </td>
        </tr>
    </table>                        
</form>